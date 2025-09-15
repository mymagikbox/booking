<?php
declare(strict_types=1);

namespace common\CQS\Application\Book\Interface;

use common\CQS\Domain\Entity\BookAuthorAssign;

interface BookAuthorAssignRepositoryInterface
{
    public function createOrException(int $authorId, int $bookId): BookAuthorAssign;
    public function clearBookAuthors(int $bookId): void;
    public function clearAuthorFromBooks(int $authorId): void;
}