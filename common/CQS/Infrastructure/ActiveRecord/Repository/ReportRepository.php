<?php
declare(strict_types=1);

namespace common\CQS\Infrastructure\ActiveRecord\Repository;

use common\CQS\Application\Report\Interface\ReportRepositoryInterface;
use common\CQS\Application\Report\Query\TopAuthors\TopAuthorsQuery;
use Yii;

final class ReportRepository implements ReportRepositoryInterface
{
    public function getTopBookAuthors(TopAuthorsQuery $query): array
    {
        $sql = <<<SQL
    SELECT 
        a.*,
        t.count
    FROM (
        SELECT 
                ba.author_id,
                count(*) as count
        FROM book as t
        LEFT JOIN book_author_assign as ba ON t.id = ba.book_id
        WHERE t.year=:year
        GROUP BY ba.author_id
    ) as t
    LEFT JOIN author as a ON a.id = t.author_id
    ORDER BY t.count DESC
    LIMIT :limit
SQL;

        return Yii::$app->db->createCommand($sql, [
            ':year' => $query->year,
            ':limit' => $query->limit
        ])
            ->queryAll();

    }
}