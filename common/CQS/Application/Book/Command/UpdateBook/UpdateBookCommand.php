<?php
declare(strict_types=1);

namespace common\CQS\Application\Book\Command\UpdateBook;

use common\CQS\Application\Book\Command\CreateBook\CreateBookCommand;

class UpdateBookCommand extends CreateBookCommand
{
    public int $id; // updated ID

    public static function create(int $id): static
    {
        $command = new static();
        $command->id = $id;

        return $command;
    }
}