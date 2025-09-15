<?php
declare(strict_types=1);

namespace common\CQS\Application\Author\Interface;

use common\CQS\Application\Author\Command\CreateAuthor\CreateAuthorCommand;
use common\CQS\Application\Author\Command\UpdateAuthor\UpdateAuthorCommand;
use common\CQS\Domain\Entity\Author;

interface AuthorRepositoryInterface
{
    public function getAllAuthorIdNameList(): array;
    public function findOneById(int $id): ?Author;

    public function createOrException(CreateAuthorCommand $command): Author;
    public function updateOrException(Author $model, UpdateAuthorCommand $command): void;
    public function deleteOrException(Author $model): void;

}