<?php
declare(strict_types=1);

namespace common\CQS\Domain\Interface\Event;

use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

interface SyncEventDispatcherInterface extends EventDispatcherInterface
{

}