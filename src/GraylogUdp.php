<?php

namespace elementary\logger\graylog\udp;

use elementary\logger\traits\LoggerGetInterface;
use elementary\logger\traits\LoggerTrait;
use Gelf\Logger;
use Gelf\Publisher;
use Gelf\PublisherInterface;
use Gelf\Transport\TransportInterface;
use Gelf\Transport\UdpTransport;
use Psr\Log\AbstractLogger;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;

class GraylogUdp extends AbstractLogger implements LoggerGetInterface, LoggerAwareInterface
{
    use LoggerTrait;

    /**
     * @param string $facility
     * @param string $host
     * @param int    $port
     */
    public function __construct($facility, $host, $port=12201)
    {
        $transport = $this->getGelfUdpTransport($host, $port);
        $publisher = $this->getGelfPublisher($transport);
        $logger    = $this->getGelfLogger($publisher, $facility);

        $this->setLogger($logger);
    }

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
        $this->getLogger()->log($level, $message, $this->convertContext($context));
    }

    /**
     * @param array $context
     *
     * @return array
     */
    public function convertContext(array $context)
    {
        foreach ($context as $key=>$val) {
            if (is_array($val)) {
                $context[$key] = json_encode($val, JSON_UNESCAPED_UNICODE);
            }
        }

        return $context;
    }

    /**
     * @param string $host
     * @param int    $port
     *
     * @return TransportInterface
     */
    public function getGelfUdpTransport($host, $port=12201)
    {
        return new UdpTransport($host, $port, UdpTransport::CHUNK_SIZE_LAN);
    }

    /**
     * @param TransportInterface $transport
     *
     * @return PublisherInterface
     */
    public function getGelfPublisher(TransportInterface $transport)
    {
        $publisher = new Publisher();
        $publisher->addTransport($transport);

        return $publisher;
    }

    /**
     * @param PublisherInterface $publisher
     * @param string             $facility
     *
     * @return LoggerInterface
     */
    public function getGelfLogger(PublisherInterface $publisher, $facility)
    {
        return new Logger($publisher, $facility);
    }
}