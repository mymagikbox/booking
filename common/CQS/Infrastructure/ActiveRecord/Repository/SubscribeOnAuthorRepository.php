<?php

namespace common\CQS\Infrastructure\ActiveRecord\Repository;

use common\CQS\Application\AuthorSubscription\Command\SubscribeOnAuthor\SubscribeOnAuthorCommand;
use common\CQS\Application\AuthorSubscription\Interface\SubscribeOnAuthorRepositoryInterface;
use common\CQS\Domain\Entity\AuthorSubscription;
use common\CQS\Domain\Exception\SaveException;
use TheSeer\Tokenizer\Exception;

class SubscribeOnAuthorRepository implements SubscribeOnAuthorRepositoryInterface
{
    public function createOrException(SubscribeOnAuthorCommand $command): AuthorSubscription
    {
        $model = new AuthorSubscription($command->getAttributes());

        if(!$model->save(false)) {
            throw new SaveException();
        }

        return $model;
    }

    public function getSubscribersByAuthor(array $authorIdList, callable $fn): void
    {
        $query = AuthorSubscription::find()
            ->where(['in', 'id', $authorIdList]);

        foreach ($query->each(30) as $subscriber) {
            $fn($subscriber);
        }
    }

}