<?php
declare(strict_types=1);

namespace common\CQS\Application\Book\Interface;

use common\CQS\Application\Book\Command\CreateBook\CreateBookCommand;
use common\CQS\Application\Book\Command\UpdateBook\UpdateBookCommand;
use common\CQS\Domain\Entity\Book;

interface BookRepositoryInterface
{
    public function createOrException(CreateBookCommand $command): Book;
    public function updateOrException(Book $model, UpdateBookCommand $command): void;
    public function deleteOrException(Book $model): void;
    public function findOneById(int $id): ?Book;
}