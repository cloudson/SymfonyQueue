<?php

namespace Cloudson\SymfonyQueue\Queue;

class JobTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @dataProvider provideInvalidStrings
     * @expectedException \InvalidArgumentException
     */ 
    public function isImpossibleCreateAjobWithoutAJsonString($nonString)
    {
        new Job($nonString, 'queue-name'); 
    }

    /**
     * @test
     */
    public function itsNormalCreateAJobWithAString()
    {
        new Job('', 'queue-name');
    }

    /**
     * @test
     */
    public function toStringIsTheJsonItSelf()
    {
        $json = json_encode(['gender' => 'whatever']); 
        $job = new Job($json, 'queue-name');

        $this->assertEquals($json, (string) $job);
    }

    public function provideInvalidStrings()
    {
        return [
            [null],
            [true],
            [new \stdClass]
        ];
    }
    
    /**
     * @test
     */ 
    public function queueNameShouldBeValid()
    {
        $job = new Job('{}', 'queueName');

        $this->assertEquals('queueName', $job->getQueueName());
    }
}
