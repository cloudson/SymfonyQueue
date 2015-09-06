<?php

namespace Cloudson\SymfonyQueue; 

use Cloudson\SymfonyQueue\Transformer\TransformerInterface;
use Cloudson\SymfonyQueue\Queue\QueueInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface; 
use Symfony\Component\EventDispatcher\Event; 

class QueueManager extends EventDispatcher
{
    private $eventDispatcher; 
    private $queue; 
    private $transformer; 

    public function __construct(
        EventDispatcherInterface $eventDispatcher, 
        QueueInterface $queue, 
        TransformerInterface $transformerStrategy 
    ) {
        $this->eventDispatcher = $eventDispatcher; 
        $this->queue = $queue;
        $this->transformer = $transformerStrategy; 
    }

    protected function doDispatch($listeners, $eventName, Event $event)
    {
        foreach ($listeners as $listener) {
            $listenerObject = (is_array($listener)) ? $listener[0] : $listener; 
            $job = $this->transformer->transform($event, $listenerObject); 
            if (null === $job) {                
                call_user_func($listener, $event, $eventName, $this);
                continue; 
            } 

            $this->queue->add($job); 
        }

    }

    public function dispatch($eventName, Event $event = null)
    {
        if (null === $event) {
            $event = new Event();
        }

        $event->setDispatcher($this);
        $event->setName($eventName);

        $listeners = $this->getListeners($eventName);
        if (!$listeners) {
            return $event; 
        }

        $this->doDispatch($listeners, $eventName, $event);

        return $event;
    }
    
    public function addListener($eventName, $listener, $priority = 0)
    {
        $this->eventDispatcher->addListener($eventName, $listener, $priority); 
    }

    public function addSubscriber(EventSubscriberInterface $subscriber)
    {
        $this->eventDispatcher->addSubscriber($subscriber); 
    }

    public function removeListener($eventName, $listener)
    {
        $this->eventDispatcher->removeListener($eventName, $listener); 
    }

    public function removeSubscriber(EventSubscriberInterface $subscriber)
    {
        $this->eventDispatcher->removeSubscriber($subscriber); 
    }

    public function getListeners($eventName = null)
    {
        return $this->eventDispatcher->getListeners($eventName);
    }

    public function hasListeners($eventName = null)
    {
        return $this->eventDispatcher->hasListeners($eventName);
    }
}
