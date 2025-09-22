<?php
declare(strict_types=1);

namespace common\CQS\Application\Book\Command\CreateBook;

use common\core\helper\ValidationHelper;
use common\core\model\BaseModel;
use common\CQS\Domain\Entity\Author;
use yii\web\UploadedFile;

class CreateBookCommand extends BaseModel
{
    public $title;
    public $year;
    public $description;
    public $isbn;
    public array $authorIdList = [];

    public ?int $created_by = null;
    public ?int $updated_by = null;
    public ?UploadedFile $coverImageFile = null;
    public ?string $cover_image = null;

    public function rules(): array
    {
        return [
            [['title', 'year', 'description', 'isbn'], 'required'],
            [['title', 'description'], 'string', 'max' => 255],
            [['isbn'], 'string', 'max' => 20],
            [['year', 'created_by', 'updated_by'], 'integer'],
            ['year', 'validateCorrectYear'],
            ['isbn', 'validateCorrectIsbn'],
            [
                ['authorIdList'],
                'each',
                'rule' => ['exist', 'skipOnError' => true, 'targetClass' => Author::class, 'targetAttribute' => 'id']
            ],
            [['coverImageFile',], 'file',
                'skipOnEmpty' => true,
                'extensions' => 'jpg, jpeg, png, gif, svg',
            ],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'title' => 'название',
            'year' => 'год выпуска',
            'description' => 'описание',
            'isbn' => 'isbn',
            'authorIdList' => 'Авторы',
            'coverImageFile' => 'Фото главной страницы',
            'cover_image' => 'Фото главной страницы',
        ];
    }

    public function validateCorrectYear($attribute, $params): void
    {
        if (!$this->hasErrors()) {
            $correctPublishDate = 1990; // same date on business logic
            $currentDate = (int)date('Y');

            if (
                !$this->year ||
                ($this->year > $currentDate || $this->year < $correctPublishDate)
            ) {
                $this->addError($attribute, "Incorrect date year");
            }
        }
    }

    public function validateCorrectIsbn($attribute, $params): void
    {
        if (!$this->hasErrors()) {
            if (!ValidationHelper::isValidIsbn10($this->isbn)) {
                $this->addError($attribute, "Isbn not correct");
            }
        }
    }
}