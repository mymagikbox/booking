<?php

namespace common\CQS\Domain\Event;

use common\CQS\Domain\Interface\Event\SyncEventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;

class SyncEventDispatcher extends EventDispatcher implements SyncEventDispatcherInterface
{

}