<?php
declare(strict_types=1);

namespace common\CQS\Application\Report\Query\TopAuthors\Response;

use common\CQS\Domain\Interface\ArrayableInterface;

readonly class TopAuthorItem implements ArrayableInterface
{
    public function __construct(
        private int $id,
        private string $username,
        private int $count = 0,
    )
    {
    }

    public static function fromRespond(?array $respondData = null): ?self
    {
        if(is_array($respondData) && count($respondData)) {
            return new self(
                $respondData['id'],
                $respondData['username'],
                $respondData['count'],
            );
        }

        return null;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getCount(): int
    {
        return $this->count;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'username' => $this->getUsername(),
            'count' => $this->getCount(),
        ];
    }
}