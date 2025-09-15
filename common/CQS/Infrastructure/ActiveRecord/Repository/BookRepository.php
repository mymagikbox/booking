<?php
declare(strict_types=1);

namespace common\CQS\Infrastructure\ActiveRecord\Repository;

use common\CQS\Application\Book\Command\CreateBook\CreateBookCommand;
use common\CQS\Application\Book\Command\UpdateBook\UpdateBookCommand;
use common\CQS\Application\Book\Interface\BookRepositoryInterface;
use common\CQS\Domain\Entity\Book;
use common\CQS\Domain\Exception\DeleteException;
use common\CQS\Domain\Exception\SaveException;

final class BookRepository implements BookRepositoryInterface
{
    public function createOrException(CreateBookCommand $command): Book
    {
        $model = new Book($command->getAttributes());

        if(!$model->save(false)) {
            throw new SaveException();
        }

        return $model;
    }

    public function updateOrException(Book $model, UpdateBookCommand $command): void
    {
        $model->setAttributes($command->getAttributes());

        if(!$model->save(false)) {
            throw new SaveException();
        }
    }

    public function deleteOrException(Book $model): void
    {
        $delRows = $model->delete();

        if(!$delRows) {
            throw new DeleteException();
        }
    }

    public function findOneById(int $id): ?Book
    {
        return Book::find()
            ->where(['id' => $id])
            ->with('bookAuthorAssign')
            ->one();
    }
}