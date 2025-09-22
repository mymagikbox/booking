<?php
declare(strict_types=1);

namespace common\CQS\Infrastructure\MessageBroker\RabbitMQ\Event;

use common\CQS\Domain\Event\Trait\DomainEventTrait;
use common\CQS\Domain\Interface\Event\AsyncEventConsumerInterface;
use common\CQS\Domain\Interface\Event\EventInterface;
use common\CQS\Domain\Interface\Event\SyncEventDispatcherInterface;
use common\CQS\Infrastructure\MessageBroker\RabbitMQ\RabbitMQConnection;
use PhpAmqpLib\Message\AMQPMessage;
use ReflectionClass;
use ReflectionException;
use Throwable;
use Yii;

final class RabbitMQEventConsumer implements AsyncEventConsumerInterface
{
    use DomainEventTrait;

    public function __construct(
        private RabbitMQConnection $rabbitMQConnection,
        private SyncEventDispatcherInterface $eventDispatcher,
    )
    {}

    public function consume(): void
    {
        $connection = $this->rabbitMQConnection->getConnection();
        $channel = $connection->channel();

        $this->prepareChanelAndQueue($channel);

        Yii::debug("[*] Waiting for messages. To exit press CTRL+C\n");

        $callback = function (AMQPMessage $message) {
            $this->processMessage($message);
        };

        $channel->basic_consume(
            $this->queueName,
            '',
            false,
            false,
            false,
            false,
            $callback
        );

        while ($channel->is_consuming()) {
            $channel->wait();
        }

        $channel->close();
        $connection->close();
    }

    private function processMessage(AMQPMessage $message): void
    {
        try {
            $body = json_decode($message->getBody(), true, 512, JSON_THROW_ON_ERROR);

            $eventClass = $body['event_class'];
            $payload = $body['payload'];

            if (!class_exists($eventClass)) {
                Yii::error("[x] Event class {$eventClass} not found\n");
                $message->nack();
                return;
            }

            // Восстанавливаем событие
            $event = $this->reconstituteEvent($eventClass, $payload);

            if (!$event instanceof EventInterface) {
                Yii::error("[x] Invalid event type\n");
                $message->nack();
                return;
            }

            // Диспетчеризуем событие
            $this->eventDispatcher->dispatch($event);

            Yii::info("[x] Processed event: {$eventClass}\n");

            $message->ack();
        } catch (Throwable $e) {
            Yii::error("[x] Error processing message: " . $e->getMessage() . "\n");
            $message->nack();
        }
    }

    /**
     * @throws ReflectionException
     */
    private function reconstituteEvent(string $eventClass, array $payload): object
    {
        $reflection = new ReflectionClass($eventClass);
        $event = $reflection->newInstanceWithoutConstructor();

        foreach ($payload as $property => $value) {
            if ($reflection->hasProperty($property)) {
                $propertyReflection = $reflection->getProperty($property);
                // $propertyReflection->setAccessible(true);
                $propertyReflection->setValue($event, $value);
            }
        }

        return $event;
    }
}