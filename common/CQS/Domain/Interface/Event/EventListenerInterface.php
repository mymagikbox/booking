<?php
declare(strict_types=1);

namespace common\CQS\Domain\Interface\Event;

interface EventListenerInterface
{
    public function handle(EventInterface $event): void;
}