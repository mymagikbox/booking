<?php
declare(strict_types=1);

namespace common\CQS\Application\Book\Query\ListBook;

use common\CQS\Domain\Exception\ValidationException;

class ListBookFetcher
{
    public function fetch(ListBookQuery $query)
    {
        if(!$query->validate()) {
            throw new ValidationException($query->getFirstErrorModel());
        }


    }
}