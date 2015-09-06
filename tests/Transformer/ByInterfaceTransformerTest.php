<?php

namespace Cloudson\SymfonyQueue\Transformer; 

use Cloudson\SymfonyQueue\Queue\Job;
use Symfony\Component\Serializer\SerializerInterface; 
use Symfony\Component\EventDispatcher\Event; 

class ByInterfaceTransformerTest extends \PHPUnit_Framework_TestCase
{
    private $serializer; 
    private $transformer; 

    public function setUp()
    {
        $this->serializer = $this->getMockBuilder(SerializerInterface::class)->disableOriginalConstructor()->getMock();
        $this->transformer = new ByInterfaceTransformer(
            $this->serializer
        );
    }

    public function testCommonEventShouldNotBeTransformedToJob()
    {
        $emptyListener = function(){}; 
        $event = new Event; 
        $this->serializer->expects($this->never())->method('serialize'); 
        $job = $this->transformer->transform($event, $emptyListener); 

        $this->assertNull($job);
    }

    public function testEventToJobShouldBeTransformedToJob()
    {
        $emptyListener = function(){};
        $event = new EventUsingInterface; 
        $this->serializer->method('serialize')->willReturn(json_encode(['just' => 'do it'])); 

        $job = $this->transformer->transform($event, $emptyListener);

        $this->assertInstanceOf(
            Job::class, 
            $job
        ); 
    }
}

class EventUsingInterface extends Event implements EventJob
{
    public function getJobPayload()
    {
        return [
            'just' => 'do it',
        ];
    }
}
