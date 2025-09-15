<?php
declare(strict_types=1);

namespace common\CQS\Domain\Interface\Event;

interface AsyncEventConsumerInterface
{
    public function consume(): void;
}