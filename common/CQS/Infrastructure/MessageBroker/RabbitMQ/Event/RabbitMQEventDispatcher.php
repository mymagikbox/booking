<?php
declare(strict_types=1);

namespace common\CQS\Infrastructure\MessageBroker\RabbitMQ\Event;

use common\CQS\Domain\Interface\Event\AsyncEventDispatcherInterface;
use common\CQS\Domain\Interface\Event\EventInterface;
use common\CQS\Infrastructure\MessageBroker\RabbitMQ\RabbitMQConnection;
use PhpAmqpLib\Message\AMQPMessage;
use ReflectionClass;

final class RabbitMQEventDispatcher implements AsyncEventDispatcherInterface
{
    public function __construct(
        private RabbitMQConnection $rabbitMQConnection,
        private readonly string    $exchangeName = 'domain_events',
    )
    {}

    public function dispatch(EventInterface $event, string $eventName = null): void
    {
        $connection = $this->rabbitMQConnection->getConnection();
        $channel = $connection->channel();

        // Создаем обменник
        $channel->exchange_declare(
            $this->exchangeName,
            'direct',
            false,
            true,
            false
        );

        // Создаем сообщение
        $messageBody = json_encode([
            'event_class' => get_class($event),
            'payload' => $this->extractPayload($event),
            'occurred_at' => date('c')
        ]);

        $message = new AMQPMessage(
            $messageBody,
            ['delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT]
        );

        $routingKey = $eventName;

        $channel->basic_publish($message, $this->exchangeName, $routingKey);

        $channel->close();
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