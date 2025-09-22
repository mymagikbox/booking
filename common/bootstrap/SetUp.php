<?php
declare(strict_types=1);

namespace common\bootstrap;

use common\CQS\Application\Author\Interface\AuthorRepositoryInterface;
use common\CQS\Application\AuthorSubscription\Interface\SubscribeOnAuthorRepositoryInterface;
use common\CQS\Application\Book\Event\BookCreatedEvent;
use common\CQS\Application\AuthorSubscription\EventListener\ClientInformerEventListener;
use common\CQS\Application\Book\Interface\BookAuthorAssignRepositoryInterface;
use common\CQS\Application\Book\Interface\BookRepositoryInterface;
use common\CQS\Application\Report\Interface\ReportRepositoryInterface;
use common\CQS\Domain\Event\SyncEventDispatcher;
use common\CQS\Domain\Interface\Event\AsyncEventConsumerInterface;
use common\CQS\Domain\Interface\Event\AsyncEventDispatcherInterface;
use common\CQS\Domain\Interface\Event\SyncEventDispatcherInterface;
use common\CQS\Domain\Interface\Storage\FileStorageInterface;
use common\CQS\Infrastructure\ActiveRecord\Repository\AuthorRepository;
use common\CQS\Infrastructure\ActiveRecord\Repository\BookAuthorAssignRepository;
use common\CQS\Infrastructure\ActiveRecord\Repository\BookRepository;
use common\CQS\Infrastructure\ActiveRecord\Repository\ReportRepository;
use common\CQS\Infrastructure\ActiveRecord\Repository\SubscribeOnAuthorRepository;
use common\CQS\Infrastructure\MessageBroker\RabbitMQ\Event\RabbitMQEventConsumer;
use common\CQS\Infrastructure\MessageBroker\RabbitMQ\Event\RabbitMQEventDispatcher;
use common\CQS\Infrastructure\MessageBroker\RabbitMQ\RabbitMQConnection;
use common\CQS\Infrastructure\Storage\FileStorage;
use common\CQS\Modules\Smspilot\Domain\Interface\SmspilotHttpClientInterface;
use common\CQS\Modules\Smspilot\Infrastructure\Adapter\Http\SmspilotHttpClient;
use Yii;
use yii\base\BootstrapInterface;
use Symfony\Component\HttpClient\HttpClient as SymfonyClient;

final class SetUp implements BootstrapInterface
{
    public function bootstrap($app): void
    {
        $container = Yii::$container;

        $container->set(BookRepositoryInterface::class, function () use ($app) {
            return new BookRepository();
        });

        $container->set(BookAuthorAssignRepositoryInterface::class, function () use ($app) {
            return new BookAuthorAssignRepository();
        });

        $container->set(AuthorRepositoryInterface::class, function () use ($app) {
            return new AuthorRepository();
        });

        $container->set(ReportRepositoryInterface::class, function () use ($app) {
            return new ReportRepository();
        });

        $container->set(SubscribeOnAuthorRepositoryInterface::class, function () use ($app) {
            return new SubscribeOnAuthorRepository();
        });

        $container->set(SmspilotHttpClientInterface::class, function () use ($app) {
            $httpClient = SymfonyClient::create([
                'base_uri' => $_ENV['SMSPILOT_API_HOST'],
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
            ]);

            return new SmspilotHttpClient(
                $httpClient,
                $_ENV['SMSPILOT_API_KEY']
            );
        });

        $container->set(SyncEventDispatcherInterface::class, function () use ($app) {
            $dispatcher = new SyncEventDispatcher();

            // added listing listeners...
            $dispatcher->addListener(BookCreatedEvent::eventName(), [
                Yii::createObject(ClientInformerEventListener::class), 'handle'
            ]);

            return $dispatcher;
        });

        $container->set(RabbitMQConnection::class, function () use ($app) {
            return new RabbitMQConnection(
                $_ENV['RABBITMQ_HOST'],
                (int) $_ENV['RABBITMQ_PORT'],
                $_ENV['RABBITMQ_USER'],
                $_ENV['RABBITMQ_PASS'],
            );
        });

        $container->set(AsyncEventDispatcherInterface::class, function () use ($app, $container) {
            return new RabbitMQEventDispatcher(
                $container->get(RabbitMQConnection::class),
            );
        });

        $container->set(AsyncEventConsumerInterface::class, function () use ($app, $container) {
            return new RabbitMQEventConsumer(
                $container->get(RabbitMQConnection::class),
                $container->get(SyncEventDispatcherInterface::class)
            );
        });

        $container->set(FileStorageInterface::class, function () use ($app) {
            return new FileStorage(
                '@frontend/web/files/storage',
                '/files/storage'
            );
        });
    }
}