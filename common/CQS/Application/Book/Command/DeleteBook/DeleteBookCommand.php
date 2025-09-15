<?php

namespace common\CQS\Application\Book\Command\DeleteBook;

use common\core\model\BaseModel;

class DeleteBookCommand extends BaseModel
{
    public int $id;

    public static function create(int $id): static
    {
        $command = new static();
        $command->id = $id;

        return $command;
    }
}