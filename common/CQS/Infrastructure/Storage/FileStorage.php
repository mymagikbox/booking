<?php
declare(strict_types=1);

namespace common\CQS\Infrastructure\Storage;

use common\core\helper\TransliteratorHelper;
use common\CQS\Domain\Interface\Storage\FileStorageInterface;
use RuntimeException;
use Yii;

class FileStorage implements FileStorageInterface
{
    /**
     * @var string storage root directory
     */
    private string $baseDir = '';
    /**
     * @var string storage root url
     */
    private string $baseUrl = '';
    /**
     * @var boolean use subdirectories to store files
     */
    private bool $subDirectory = true;

    private int $subDirectoryLevel = 2;

    private int $subDirectoryChunk = 4;
    private int $fileMode = 0666;

    private int $fileNameLengthMax = 64;

    private array $rewriteExtensions = [
        'pl' => 'txt',
        'php' => 'txt',
        'php3' => 'txt',
        'php4' => 'txt',
        'php5' => 'txt',
        'phtml' => 'txt',
    ];

    private string $noImage = '/images/no_img.jpg';

    public function __construct(
        string $baseDir = '',
        string $baseUrl = ''
    )
    {
        if ($this->subDirectoryChunk < 1) {
            $this->subDirectoryChunk = 1;
        }

        if (empty($baseDir)) {
            throw new RuntimeException('CONFIGURATION_VARIABLE_DIRECTORY_MUST_BE_SET_FOR_STORAGE_COMPONENT');
        }

        $this->baseDir = Yii::getAlias($baseDir);
        $this->baseUrl = Yii::getAlias($baseUrl);
    }

    public function put(
        string $file,
        ?string $origin = null,
        bool $delete = true
    ): string
    {
        if (!$origin) {
            $origin = $this->getName($file) . '.' . $this->getExtension($file);
        }

        if (!file_exists($file) || !is_file($file)) {
            throw new RuntimeException("FILE {$file} NOT_FOUND");
        }

        if (!is_readable($file)) {
            throw new RuntimeException("FILE {$file} NOT_ACCESSIBLE");
        }

        $name = $this->createFileName($origin);
        $dir = $this->createFileDir($name);
        $path = $dir . $name;

        $result = false;
        if ($delete) {
            $result = rename($file, $path);
        }

        if (!$result) {
            if (!copy($file, $path)) {
                throw new RuntimeException("CAN_NOT_COPY_FILE {$file} TO {$path}");
            }
        }

        chmod($path, $this->fileMode);

        if ($delete && file_exists($file)) {
            @unlink($file);
        }

        if (!file_exists($path) || !is_readable($path)) {
            throw new RuntimeException("NEW_FILE {$path} INTO_DIRECTORY {$path} NOT_ACCESSIBLE");
        }

        return $name;
    }

    public function exists(string $name): bool
    {
        return file_exists($this->getFilePath($name));
    }

    public function delete(string $name): bool
    {
        if (!$name) {
            return false;
        }
        $path = $this->getFilePath($name);
        if (file_exists($path)) {
            if (is_writable(dirname($path)) && is_writable($path)) {
                @unlink($path);
            } else {
                return false;
            }
        }
        $dir = dirname($path);
        for ($i = 0; $i < $this->subDirectoryLevel; $i++) {
            if ($this->isDirEmpty($dir)) {
                rmdir($dir);
            }
            $dir = dirname($dir);
        }

        return true;
    }

    public function get(string $name, bool $absolute = false): string
    {
        $baseUrl = ($absolute ? $_ENV['FRONTEND_URL'] : '');

        if (!$name) {
            return $baseUrl . $this->getNoImageFile();
        }

        return $baseUrl . $this->baseUrl . '/' . strtr($this->getFileDir($name), [DIRECTORY_SEPARATOR => '/']) . $name;
    }

    private function createFileDir(
        string $name,
        ?string $baseDir = null,
        bool $autoCreate = true
    ): string
    {
        $dir = $baseDir === null ? $this->baseDir . DIRECTORY_SEPARATOR : $baseDir;
        if ($autoCreate) {
            $this->checkDir($dir);
        }
        if ($this->subDirectory) {
            for ($i = 0; $i < $this->subDirectoryLevel; $i++) {
                $chunk = mb_substr($name, $i * $this->subDirectoryChunk, $this->subDirectoryChunk, Yii::$app->charset);
                $dir .= $chunk . DIRECTORY_SEPARATOR;
                if ($autoCreate) {
                    $this->checkDir($dir);
                }
            }
        }
        if ($autoCreate) {
            $this->checkDir(dirname($dir . DIRECTORY_SEPARATOR . $name));
        }
        return $dir;
    }

    private function getFileDir(string $name): string
    {
        return $this->createFileDir($name, '', false);
    }

    public function getNoImageFile(): string
    {
        return $this->noImage;
    }

    public function getFilePath(string $name): string
    {
        return $this->baseDir . DIRECTORY_SEPARATOR . $this->getFileDir($name) . $name;
    }

    public function getFileSize(string $name): int
    {
        return ($path = $this->getFilePath($name)) && file_exists($path) ? filesize($path) : 0;
    }

    /**
     * Create new file name for storage (may contain part of the path)
     * @param string $origin origin file name
     * @return string
     */
    private function createFileName(string $origin): string
    {
        return $this->getName($origin) . '_' . time() . '.' . $this->getExtension($origin);
    }

    /**
     * Get file name exposed from it's path
     * @param string $path file path
     * @return string
     */
    private function getName(string $path): string
    {
        $name = strtr(TransliteratorHelper::process(pathinfo($path, PATHINFO_FILENAME)), [
            '/' => '',
            '.' => '',
            '\\' => '',
            ' ' => '',
            '+' => '',
        ]);
        $name = mb_convert_case($name, MB_CASE_LOWER, Yii::$app->charset);
        return mb_strlen($name, Yii::$app->charset) > $this->fileNameLengthMax ? mb_substr($name, 0, $this->fileNameLengthMax, Yii::$app->charset) : $name;
    }

    private function getExtension(string $path): string
    {
        $ext = pathinfo($path, PATHINFO_EXTENSION);
        if (isset($this->rewriteExtensions[$ext])) {
            $ext = $this->rewriteExtensions[$ext];
        }
        return empty($ext) ? '' : mb_convert_case($ext, MB_CASE_LOWER, Yii::$app->charset);
    }

    private function isDirEmpty(string $dir): ?bool
    {
        if (!file_exists($dir) || !is_dir($dir) || !is_readable($dir)) {
            return null;
        }
        $content = glob(rtrim($dir, ' /\\') . DIRECTORY_SEPARATOR . '*');
        if ($content && is_array($content) && count($content)) {
            return false;
        }
        return true;
    }

    private function checkDir(string $dir): void
    {
        if (!file_exists($dir)) {
            mkdir($dir, 0777);
            chmod($dir, 0777);
        }
        if (!file_exists($dir) || !is_dir($dir)) {
            throw new RuntimeException("STORAGE_DIRECTORY {$dir} NOT_FOUND_AND_CAN_NOT_CREATED");
        }
        if (!is_writable($dir)) {
            chmod($dir, 0777);
        }
        if (!is_writable($dir)) {
            throw new RuntimeException("STORAGE_DIRECTORY {$dir} NOT_WRITEABLE");
        }
    }
}