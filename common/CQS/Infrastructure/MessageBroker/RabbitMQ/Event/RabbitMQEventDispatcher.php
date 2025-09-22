<?php
declare(strict_types=1);

namespace common\CQS\Infrastructure\MessageBroker\RabbitMQ\Event;

use common\CQS\Domain\Event\Trait\DomainEventTrait;
use common\CQS\Domain\Interface\Event\AsyncEventDispatcherInterface;
use common\CQS\Domain\Interface\Event\EventInterface;
use common\CQS\Infrastructure\MessageBroker\RabbitMQ\RabbitMQConnection;
use PhpAmqpLib\Message\AMQPMessage;
use ReflectionClass;
use Yii;

final class RabbitMQEventDispatcher implements AsyncEventDispatcherInterface
{
    use DomainEventTrait;

    public function __construct(
        private RabbitMQConnection $rabbitMQConnection,
    )
    {
    }

    public function dispatch(EventInterface $event, string $eventName = null): void
    {
        $connection = $this->rabbitMQConnection->getConnection();
        $channel = $connection->channel();

        // Создаем обменник
        $this->prepareChanelAndQueue($channel);

        // Создаем сообщение
        $messageBody = json_encode([
            'event_class' => get_class($event),
            'payload' => $this->extractPayload($event),
            'occurred_at' => date('c')
        ]);

        Yii::info("Event: {$eventName}, message: {$messageBody}");

        $message = new AMQPMessage(
            $messageBody,
            ['delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT]
        );

        $channel->basic_publish($message, $this->exchangeName, $this->routingKey);

        $channel->close();
        $connection->close();
    }

    private function extractPayload(EventInterface $event): array
    {
        $reflection = new ReflectionClass($event);
        $properties = $reflection->getProperties();

        $payload = [];
        foreach ($properties as $property) {
            // $property->setAccessible(true);
            $value = $property->getValue($event);

            // Сериализуем простые типы
            if (is_scalar($value) || is_array($value) || is_null($value)) {
                $payload[$property->getName()] = $value;
            } elseif (is_object($value) && method_exists($value, '__toString')) {
                $payload[$property->getName()] = (string)$value;
            }
        }

        return $payload;
    }
}