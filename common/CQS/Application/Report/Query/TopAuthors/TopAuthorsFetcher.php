<?php
declare(strict_types=1);

namespace common\CQS\Application\Report\Query\TopAuthors;

use common\CQS\Application\Report\Interface\ReportRepositoryInterface;
use common\CQS\Application\Report\Query\TopAuthors\Response\TopAuthorResponse;
use common\CQS\Domain\Exception\ValidationException;

class TopAuthorsFetcher
{
    public function __construct(
        private ReportRepositoryInterface $reportRepository
    )
    {
    }

    public function fetch(TopAuthorsQuery $query): ?TopAuthorResponse
    {
        if(!$query->validate()) {
            throw new ValidationException($query->getFirstErrorModel());
        }

        return TopAuthorResponse::fromRespond(
            $this->reportRepository->getTopBookAuthors($query)
        );
    }
}