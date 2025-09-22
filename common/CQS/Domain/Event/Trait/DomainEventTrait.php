<?php
declare(strict_types=1);

namespace common\CQS\Domain\Event\Trait;

use PhpAmqpLib\Exchange\AMQPExchangeType;
use PhpAmqpLib\Channel\AMQPChannel;

trait DomainEventTrait
{
    protected string $exchangeName = 'domainEvents';
    protected string $queueName = 'domainEventsQueue';
    protected string $routingKey = '#';

    protected function prepareChanelAndQueue(
        AMQPChannel $channel
    ): void
    {
        // Declare exchange
        $channel->exchange_declare(
            $this->exchangeName,
            AMQPExchangeType::DIRECT,
            false,
            true,
            false
        );

        // Declare queue
        $channel->queue_declare(
            $this->queueName,
            false,
            true,
            false,
            false
        );

        // Bind all events
        $channel->queue_bind(
            $this->queueName,
            $this->exchangeName,
            $this->routingKey
        );
    }
}