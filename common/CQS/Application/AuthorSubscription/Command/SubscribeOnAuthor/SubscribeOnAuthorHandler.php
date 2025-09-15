<?php
declare(strict_types=1);

namespace common\CQS\Application\AuthorSubscription\Command\SubscribeOnAuthor;

use common\CQS\Application\AuthorSubscription\Interface\SubscribeOnAuthorRepositoryInterface;
use common\CQS\Domain\Exception\ValidationException;
use Exception;
use Yii;

class SubscribeOnAuthorHandler
{
    public function __construct(
        private SubscribeOnAuthorRepositoryInterface $subscribeOnAuthorRepository,
    )
    {
    }

    public function run(SubscribeOnAuthorCommand $command): void
    {
        $transaction = Yii::$app->db->beginTransaction();

        try {
            if (!$command->validate()) {
                throw new ValidationException($command->getFirstErrorModel());
            }

            $this->subscribeOnAuthorRepository->createOrException($command);

            $transaction->commit();
        } catch (Exception $e) {
            $transaction->rollBack();

            Yii::debug("Error: " . self::class . ". Message: {$e->getMessage()}");

            throw new $e;
        }
    }
}