<?php
declare(strict_types=1);

namespace common\CQS\Application\Book\EventHandler;

use common\CQS\Application\AuthorSubscription\Interface\SubscribeOnAuthorRepositoryInterface;
use common\CQS\Application\Book\Event\BookCreatedEvent;
use common\CQS\Domain\Entity\AuthorSubscription;
use common\CQS\Domain\Interface\Event\EventInterface;
use common\CQS\Domain\Interface\Event\EventListenerInterface;
use common\CQS\Modules\Smspilot\Application\Command\SendSms\SendSmsCommand;
use common\CQS\Modules\Smspilot\Domain\Interface\SmspilotHttpClientInterface;
use Exception;
use Yii;

class ClientInformerEventListener implements EventListenerInterface
{
    public function __construct(
        private SubscribeOnAuthorRepositoryInterface $subscribeOnAuthorRepository,
        private SmspilotHttpClientInterface $smspilotHttpClient
    )
    {
    }

    /***
     * @param BookCreatedEvent $event
     * @return void
     * @throws Exception
     */
    public function handle(EventInterface $event): void
    {
        try {
            $this->subscribeOnAuthorRepository->getSubscribersByAuthor(
                $event->getAuthorIdList(),
                function (AuthorSubscription $subscriber) use ($event) {
                    $this->smspilotHttpClient->runSendSmsCommand(
                        new SendSmsCommand(
                            "Hey, new book with name {$event->getTitle()} was added!",
                            $subscriber->phone
                        )
                    );
                }
            );
        } catch (Exception $e) {
            Yii::debug("Error: " . self::class . ". Message: {$e->getMessage()}");

            throw new $e;
        }
    }
}