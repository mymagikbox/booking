<?php

namespace common\CQS\Infrastructure\ActiveRecord\Repository;

use common\CQS\Application\Book\Interface\BookAuthorAssignRepositoryInterface;
use common\CQS\Domain\Entity\BookAuthorAssign;
use common\CQS\Domain\Exception\DeleteException;
use common\CQS\Domain\Exception\SaveException;

final class BookAuthorAssignRepository implements BookAuthorAssignRepositoryInterface
{
    public function createOrException(int $authorId, int $bookId): BookAuthorAssign
    {
        $model = BookAuthorAssign::create($authorId, $bookId);

        if (!$model->save(false)) {
            throw new SaveException();
        }

        return $model;
    }

    public function clearBookAuthors(int $bookId): void
    {
        $delRows = BookAuthorAssign::deleteAll([
            'book_id' => $bookId
        ]);

        if (!$delRows) {
            throw new DeleteException();
        }
    }

    public function clearAuthorFromBooks(int $authorId): void
    {
        $delRows = BookAuthorAssign::deleteAll([
            'author_id' => $authorId
        ]);

        if (!$delRows) {
            throw new DeleteException();
        }
    }

}