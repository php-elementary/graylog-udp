<?php

namespace elementary\logger\tests;

use elementary\logger\graylog\udp\GraylogUdp;
use Gelf\PublisherInterface;
use Gelf\Transport\TransportInterface;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;
use Psr\Log\AbstractLogger;
use Psr\Log\LoggerInterface;

class GralogUdpTest extends TestCase
{
    /**
     * @test
     */
    public function convertContext()
    {
        $this->assertEquals(
            ['test', 123, json_encode(['test' => 'тест'], JSON_UNESCAPED_UNICODE)],
            $this->getClass()->convertContext(['test', 123, ['test' => 'тест']])
        );
    }

    /**
     * @test
     */
    public function log()
    {
        $testClass = new GraylogUdpForTest();
        $mock = $this->getClass();
        $mock->setLogger($testClass);

        $mock->log('testLevel', 'testMessage', ['test', ['test' => 'test']]);
        $this->assertEquals(['testLevel', 'testMessage', ['test', json_encode(['test' => 'test'])]], $testClass->getLog());
    }

    /**
     * @test
     */
    public function getGelfUdpTransport()
    {
        $this->assertInstanceOf(TransportInterface::class, $this->getClass()->getGelfUdpTransport(''));
    }

    /**
     * @test
     */
    public function getGelfPublisher()
    {
        /** @var TransportInterface $transport */
        $transport = $this->getMockForAbstractClass(TransportInterface::class);

        $this->assertInstanceOf(PublisherInterface::class, $this->getClass()->getGelfPublisher($transport));
    }

    /**
     * @test
     */
    public function getGelfLogger()
    {
        /** @var PublisherInterface $publisher */
        $publisher = $this->getMockForAbstractClass(PublisherInterface::class);

        $this->assertInstanceOf(LoggerInterface::class, $this->getClass()->getGelfLogger($publisher, 'test'));
    }

    /**
     * @test
     */
    public function checkConstruct()
    {
        $testClass = new GraylogUdpForTest();

        $mock = $this->getMockBuilder(GraylogUdp::class)
            ->setMethods(['getLogger'])
            ->setConstructorArgs(['test', ''])
            ->getMock();

        $this->assertInstanceOf(LoggerInterface::class, $mock);

        $mock->method('getLogger')
            ->willReturn($testClass);

        $mock->log('testLevel', 'testMessage', ['test', ['test' => 'test']]);
        $this->assertEquals(['testLevel', 'testMessage', ['test', json_encode(['test' => 'test'])]], $testClass->getLog());
    }

    /**
     * @return GraylogUdp|PHPUnit_Framework_MockObject_MockObject
     */
    public function getClass()
    {
        $mock = $this->getMockBuilder(GraylogUdp::class)
            ->disableOriginalConstructor()
            ->setMethods(null)
            ->getMock();

        return $mock;
    }
}

class GraylogUdpForTest extends AbstractLogger
{
    /** @var array */
    protected $log = [];

    /**
     * Logs with an arbitrary level.
     *
     * @param mixed  $level
     * @param string $message
     * @param array  $context
     *
     * @return void
     */
    public function log($level, $message, array $context = array())
    {
        $this->log = [$level, $message, $context];
    }

    public function getLog()
    {
        return $this->log;
    }
}