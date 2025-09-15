<?php
declare(strict_types=1);

namespace common\CQS\Application\Author\Command\UpdateAuthor;

use common\CQS\Application\Author\Command\CreateAuthor\CreateAuthorCommand;

class UpdateAuthorCommand extends CreateAuthorCommand
{
    public int $id; // updated ID

    public static function create(int $id): static
    {
        $command = new static();
        $command->id = $id;

        return $command;
    }
}