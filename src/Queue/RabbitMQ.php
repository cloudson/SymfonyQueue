<?php

namespace Cloudson\SymfonyQueue\Queue; 

use PhpAmqpLib\Connection\AMQPConnection;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Message\AMQPMessage;

class RabbitMq implements QueueInterface
{
    private $channel; 
    private $connection; 

    public function __construct($params)
    {
        $this->connection = new AMQPConnection(
            $params['host'],
            $params['port'],
            $params['username'],
            $params['password']
        ); 
        $this->channel = $this->connection->channel(); 
    }

    public function add(Job $job)
    {
        $this->channel->queue_declare(
            $job->getQueueName(),
            false,
            true,
            false,
            false
        );
        $message = new AMQPMessage((string) $job);
        $this->channel->basic_publish($message, '', $job->getQueueName()); 
    }
}
