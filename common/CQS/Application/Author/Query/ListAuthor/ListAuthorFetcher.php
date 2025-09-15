<?php
declare(strict_types=1);

namespace common\CQS\Application\Author\Query\ListAuthor;

use common\CQS\Application\Author\Interface\AuthorRepositoryInterface;
use common\CQS\Domain\Exception\ValidationException;

final class ListAuthorFetcher
{
    public function __construct(
        private AuthorRepositoryInterface $authorRepository,
    )
    {
    }

    public function fetch(ListAuthorQuery $query)
    {
        if(!$query->validate()) {
            throw new ValidationException($query->getFirstErrorModel());
        }
    }
}