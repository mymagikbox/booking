<?php
declare(strict_types=1);

namespace common\CQS\Application\AuthorSubscription\Command\SubscribeOnAuthor;

use common\core\helper\ValidationHelper;
use common\core\model\BaseModel;
use common\CQS\Domain\Entity\Author;

class SubscribeOnAuthorCommand extends BaseModel
{
    public $author_id;
    public $phone;

    public function rules(): array
    {
        return [
            [['author_id', 'phone'], 'required'],
            [['author_id'], 'exist', 'targetClass' => Author::class, 'targetAttribute' => 'id'],

            [['phone'], 'integer',],
            [['phone'], 'validatePhone',],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'author_id' => 'Автор',
            'phone' => 'Телефон',
        ];
    }

    public function validateCorrectIsbn($attribute, $params): void
    {
        if (!$this->hasErrors()) {
            if (!ValidationHelper::isValidPhoneNumber($this->phone)) {
                $this->addError($attribute, "");
            }
        }
    }
}