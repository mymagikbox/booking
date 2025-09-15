<?php
declare(strict_types=1);

namespace common\CQS\Domain\Interface\Event;

interface AsyncEventDispatcherInterface
{
    public function dispatch(EventInterface $event, string $eventName = null): void;
}