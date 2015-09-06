<?php

namespace Cloudson\SymfonyQueue\Transformer; 

use Cloudson\SymfonyQueue\Queue\Job;
use Symfony\Component\EventDispatcher\Event; 
use Symfony\Component\Serializer\SerializerInterface;

class DefaultTransformer implements TransformerInterface
{
    private $serializer; 

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer; 
    }

    public function transform(Event $event, $listener)
    {
        $valuesToJob = []; 
        $getters = $this->getGettersFromEvent($event); 
        $gettersToIgnore = $this->getGettersToIgnore();
        foreach ($getters as $getter) {
            if (in_array($getter, $gettersToIgnore)) {
                continue;
            }
            $valuesToJob[$getter] = $event->$getter();
        }
        
        $json = $this->serializer->serialize($valuesToJob, 'json');
        
        return new Job($json, get_class($listener));
    }

    private function getGettersToIgnore()
    {
        return $this->getGettersFromEvent(new Event);
    }

    private function getGettersFromEvent($event)
    {
        $methods = get_class_methods($event); 
        return array_filter($methods, function ($methodName) {
            return (bool) preg_match('/^get/', $methodName);
        });

    }
}
