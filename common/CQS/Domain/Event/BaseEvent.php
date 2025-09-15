<?php
declare(strict_types=1);

namespace common\CQS\Domain\Event;

use common\CQS\Domain\Interface\Event\EventInterface;
use Symfony\Contracts\EventDispatcher\Event;

abstract class BaseEvent extends Event implements EventInterface
{
    abstract public static function eventName(): string;
}