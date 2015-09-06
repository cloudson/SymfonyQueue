<?php

namespace Cloudson\SymfonyQueue; 

use Cloudson\SymfonyQueue\Transformer\TransformerInterface; 
use Cloudson\SymfonyQueue\Queue\QueueInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\Event;

class QueueManagerTest extends \PHPUnit_Framework_TestCase
{
    private $queueManager; 
    private $eventDispatcher; 
    private $transformer; 
    private $queue; 

    public function setUp()
    {
        $this->eventDispatcher = $this->getMock(EventDispatcherInterface::class); 
        $this->transformer = $this->getMock(TransformerInterface::class); 
        $this->queue = $this->getMock(QueueInterface::class); 
        $this->queueManager = new QueueManager(
            $this->eventDispatcher,
            $this->queue,
            $this->transformer
        ); 
    }

    /**
     * @test
     */ 
    public function shouldCallDecorateEventDispatcherIfIsNotPossibleConvertEventToJob()
    {
        $eventName = 'foo'; 
        $event = new Event; 

        $this->queue->expects($this->never())->method('add');

        $this->queueManager->dispatch($eventName, $event);
    }
}
