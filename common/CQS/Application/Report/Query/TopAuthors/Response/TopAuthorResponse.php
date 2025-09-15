<?php
declare(strict_types=1);

namespace common\CQS\Application\Report\Query\TopAuthors\Response;

use common\CQS\Domain\Interface\ArrayableInterface;

final readonly class TopAuthorResponse implements ArrayableInterface
{
    public function __construct(
        private array $list = []
    )
    {
    }

    public static function fromRespond(?array $respondData = null): ?self
    {
        if (is_array($respondData) && count($respondData)) {
            $list = [];

            foreach ($respondData as $item) {
                $list[] = TopAuthorItem::fromRespond($item);
            }

            return new self($list);
        }

        return null;
    }

    public function getList(): array
    {
        return $this->list;
    }

    public function toArray(): array
    {
        return array_map(function (TopAuthorItem $item) {
            return $item->toArray();
        }, $this->getList());
    }
}