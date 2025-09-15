<?php

namespace common\CQS\Application\Author\Command\DeleteAuthor;

use common\core\model\BaseModel;

class DeleteAuthorCommand extends BaseModel
{
    public int $id;

    public static function create(int $id): static
    {
        $command = new static();
        $command->id = $id;

        return $command;
    }
}