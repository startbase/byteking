<?php
namespace startbase\ByteKing\Transport;

/**
 * Class TransportUDP
 * @package ByteKing\Transport
 */
class TransportUDP implements TransportInterface {
    private $server_ip;
    private $server_port;

    private static $socket = null;

    const MAX_LENGTH = 50000;
    const MAX_MULTI_PARTS_SEND = 999;
    const PART_PREFIX = '_p_';

    public function initConnection()
    {
        if(!static::$socket) {
            static::$socket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
            socket_set_nonblock(static::$socket);
        }
    }

    public function closeConnection()
    {
        socket_close(static::$socket);
    }

    public function send($data)
    {
        $this->initConnection();
        if(!$this->server_ip || !$this->server_port) {
            throw new \Exception('ip address and port are required');
        }

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
        socket_sendto(static::$socket, $data, mb_strlen($data), 0, $this->server_ip, $this->server_port);
    }

    /**
     * @param string $data
     * @return array
     */
    protected function createMultiUdpMsg($data) {

        $md5_msg = md5($data);
        $md5_hash = md5(uniqid($md5_msg)).'_'.$md5_msg;

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
            $line = static::PART_PREFIX.$parts_count_str.'_'.str_pad($i, $key_length, 0, STR_PAD_LEFT);
            $line .= '_'.$md5_hash.DIRECTORY_SEPARATOR.$part;
            $data[] = $line;
        }

        return $data;
    }

    /**
     * @param $server_ip
     * @param $server_port
     * @return $this
     */
    public function setConfiguration($server_ip, $server_port)
    {
        $this->server_ip = $server_ip;
        $this->server_port = $server_port;
        return $this;
    }
}