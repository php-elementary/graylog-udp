<?php

namespace elementary\logger\graylog\udp;

use elementary\loggertrait\LoggerGetInterface;
use elementary\loggertrait\LoggerTrait;
use Gelf\Logger;
use Gelf\Publisher;
use Gelf\Transport\UdpTransport;
use Psr\Log\AbstractLogger;
use Psr\Log\LoggerAwareInterface;

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
        $transport = new UdpTransport($host, $port, UdpTransport::CHUNK_SIZE_LAN);
        $publisher = new Publisher();
        $publisher->addTransport($transport);

        $this->setLogger(new Logger($publisher, $facility));
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
        foreach ($context as $key=>$val) {
            if (is_array($val)) {
                $context[$key] = json_encode($val, JSON_UNESCAPED_UNICODE);
            }
        }

        $this->getLogger()->log($level, $message, $context);
    }

}