<?php
declare(strict_types=1);

namespace common\CQS\Application\Book\Event;

use common\CQS\Domain\Event\BaseEvent;

class BookCreatedEvent extends BaseEvent
{
    public function __construct(
        private int $bookId,
        private string $title,
        private array $authorIdList = [],
    )
    {
    }

    public function getBookId(): int
    {
        return $this->bookId;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getAuthorIdList(): array
    {
        return $this->authorIdList;
    }

    public static function eventName(): string
    {
        return 'book.created';
    }
}