<?php

namespace Cloudson\SymfonyQueue\Transformer; 

use Cloudson\SymfonyQueue\Transformer\EventJob; 
use Cloudson\SymfonyQueue\Queue\Job;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\Serializer\SerializerInterface; 

class ByInterfaceTransformer implements TransformerInterface
{
    private $serializer; 

    private $defaultTransformer; 

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer; 
    }

    public function transform(Event $event, $listener)
    {
        if (!($event instanceof EventJob)) {
            return null; 
        }
        $json = $this->serializer->serialize(
            $event->getJobPayload(), 
            'json'
        );
        
        return new Job($json, get_class($listener));
    }
}
