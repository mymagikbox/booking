<?php
declare(strict_types=1);

namespace common\CQS\Application\Book\Command\CreateBook;

use common\CQS\Application\Book\Interface\BookAuthorAssignRepositoryInterface;
use common\CQS\Domain\Interface\Storage\FileStorageInterface;
use common\CQS\Domain\Exception\ValidationException;
use common\CQS\Application\Book\Interface\BookRepositoryInterface;
use Exception;
use Yii;
use yii\web\UploadedFile;

final class CreateBookHandler
{
    public function __construct(
        private BookRepositoryInterface $bookRepository,
        private BookAuthorAssignRepositoryInterface $bookAuthorAssignRepository,
        private FileStorageInterface $storage
    )
    {
    }

    /**
     * @throws \yii\db\Exception
     */
    public function run(CreateBookCommand $command): void
    {
        $transaction = Yii::$app->db->beginTransaction();

        try {
            $command->coverImageFile = UploadedFile::getInstance($command, 'coverImageFile');

            if(!$command->validate()) {
                throw new ValidationException($command->getFirstErrorModel());
            }

            if ($command->coverImageFile && file_exists($command->coverImageFile->tempName)) {
                $command->cover_image = $this->storage->put(
                    $command->coverImageFile->tempName,
                    $command->coverImageFile->name
                );
            }

            $model = $this->bookRepository->createOrException($command);

            foreach ($command->authorIdList as $authorId) {
                $this->bookAuthorAssignRepository->createOrException($authorId, $model->id);
            }

            // may be Event CreateBookEvent for client notice


            $transaction->commit();
        } catch (Exception $e) {
            $transaction->rollBack();

            Yii::debug("Error: " . self::class . ". Message: {$e->getMessage()}");

            throw new $e;
        }
    }
}