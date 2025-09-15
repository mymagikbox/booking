<?php

namespace common\CQS\Application\AuthorSubscription\Interface;

use common\CQS\Application\AuthorSubscription\Command\SubscribeOnAuthor\SubscribeOnAuthorCommand;
use common\CQS\Domain\Entity\AuthorSubscription;

interface SubscribeOnAuthorRepositoryInterface
{
    public function createOrException(SubscribeOnAuthorCommand $command): AuthorSubscription;
}