<?php
declare(strict_types=1);

namespace common\CQS\Infrastructure\MessageBroker\RabbitMQ;

use PhpAmqpLib\Connection\AMQPStreamConnection;

class RabbitMQConnection
{
    private AMQPStreamConnection $connection;
    private string $host;
    private int $port;
    private string $user;
    private string $password;
    private string $vhost;

    public function __construct(
        string $host,
        int    $port,
        string $user,
        string $password,
        string $vhost = '/'
    )
    {
        $this->host = $host;
        $this->port = $port;
        $this->user = $user;
        $this->password = $password;
        $this->vhost = $vhost;
    }

    public function getConnection(): AMQPStreamConnection
    {
        if (!isset($this->connection)) {
            $this->connection = new AMQPStreamConnection(
                $this->host,
                $this->port,
                $this->user,
                $this->password,
                $this->vhost
            );
        }

        return $this->connection;
    }

    public function close(): void
    {
        if (isset($this->connection)) {
            $this->connection->close();
        }
    }
}