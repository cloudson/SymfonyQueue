<?php

namespace Cloudson\SymfonyQueue\Queue; 

class Job
{
    private $json; 
    private $queueName; 

    public function __construct($json, $queueName)
    {
        if (false === is_string($json)) {
            throw new \InvalidArgumentException(
                'A job expects a string'
            ); 
        }
        $this->json = $json; 
        $this->queueName = $queueName; 
    }

    public function getQueueName()
    {
        return $this->queueName;
    }

    public function __toString()
    {
        return (string) $this->json; 
    }
}
