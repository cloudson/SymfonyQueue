<?php

namespace Cloudson\SymfonyQueue\Transformer;

use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\EventDispatcher\Event; 

class DefaultTransformerTest extends \PHPUnit_Framework_TestCase
{
    private $transformer; 
    private $serializer; 

    public function setUp()
    {
        $this->serializer = $this->getMockBuilder(SerializerInterface::class)->disableOriginalConstructor()->getMock(); 
        $this->transformer = new DefaultTransformer(
            $this->serializer
        );
    }

    /**
     * @test
     */
    public function transformAnEventWithPrimitiveValuesShouldGeneratesAJson()
    {
        $event = new PrimitiveEvent;
        $emptyListener = function(){}; 

        $json = json_encode(['name' => 'cloud', 'year' => 2015]); 
        $this->serializer->method('serialize')->willReturn($json); 
        $job = $this->transformer->transform($event, $emptyListener);

        $this->assertEquals(
            $json, 
            (string) $job
        );
    }
}

class PrimitiveEvent extends Event
{
    public function getName()
    {
        return 'cloud'; 
    }

    public function getYear()
    {
        return 2015;
    }
}
