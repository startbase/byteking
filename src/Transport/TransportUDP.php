<?php
namespace startbase\ByteKing\Transport;

/**
 * Class TransportUDP
 * @package ByteKing\Transport
 */
class TransportUDP implements TransportInterface {
    private $host;
    private $port;

    private $socket;

    const MAX_LENGTH = 50000;
    const MAX_MULTI_PARTS_SEND = 999;
    const PART_PREFIX = '_p_';

    /**
     * TransportUDP constructor.
     * @param $host
     * @param $port
     */
    public function __construct($host, $port) {
        $this->setConfiguration($host, $port);
        return $this;
    }

    /**
     * @return $this
     */
    public function initConnection() {
        if ($this->socket) {
            return $this;
        }

        $this->socket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
        socket_set_nonblock($this->socket);
        return $this;
    }

    /**
     * @return $this
     */
    public function closeConnection() {
        if ($this->socket) {
            socket_close($this->socket);
        }
        return $this;
    }

    public function send($data) {
        $this->initConnection();

        if (mb_strlen($data) > static::MAX_LENGTH) {
            $data = $this->createMultiUdpMsg($data);
            if ($data) {
                foreach ($data as $part) {
                    $this->_send($part);
                }
            }
            return;
        }

        $this->_send($data);
    }

    protected function _send($data) {
        socket_sendto($this->socket, $data, mb_strlen($data), 0, $this->host, $this->port);
    }

    /**
     * @param string $data
     * @return array
     */
    protected function createMultiUdpMsg($data) {

        $md5_msg = md5($data);
        $md5_hash = md5(uniqid($md5_msg)) . '_' . $md5_msg;

        $parts = str_split($data, static::MAX_LENGTH);

        $parts_count = count($parts);

        if ($parts_count > static::MAX_MULTI_PARTS_SEND) {
            return [];
        }

        $data = [];
        $i = 0;
        $key_length = strlen(static::MAX_MULTI_PARTS_SEND);
        $parts_count_str = str_pad($parts_count, $key_length, 0, STR_PAD_LEFT);
        foreach ($parts as $part) {
            $i++;
            $line = static::PART_PREFIX . $parts_count_str . '_' . str_pad($i, $key_length, 0, STR_PAD_LEFT);
            $line .= '_' . $md5_hash . DIRECTORY_SEPARATOR . $part;
            $data[] = $line;
        }

        return $data;
    }

    /**
     * @param $host
     * @param $port
     * @return $this
     */
    public function setConfiguration($host, $port) {
        $this->host = $host;
        $this->port = $port;
        return $this;
    }
}