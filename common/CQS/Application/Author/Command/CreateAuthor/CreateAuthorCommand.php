<?php
declare(strict_types=1);

namespace common\CQS\Application\Author\Command\CreateAuthor;

use common\core\model\BaseModel;

class CreateAuthorCommand extends BaseModel
{
    public $username;

    public function rules(): array
    {
        return [
            [['username'], 'required'],
            [['username'], 'string', 'max' => 255],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'username' => 'ФИО',
        ];
    }
}