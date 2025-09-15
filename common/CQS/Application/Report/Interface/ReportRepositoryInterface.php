<?php
declare(strict_types=1);

namespace common\CQS\Application\Report\Interface;

use common\CQS\Application\Report\Query\TopAuthors\TopAuthorsQuery;

interface ReportRepositoryInterface
{
    public function getTopBookAuthors(TopAuthorsQuery $query): array;
}