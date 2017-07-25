<?php
namespace ByteKing\Transport;

/**
 * Class TransportUDP
 * @package ByteKing\Transport
 */
class TransportUDP implements TransportInterface {
    private $server_ip;
    private $server_port;

    private static $socket = null;

    public function initConnection()
    {
        if(!static::$socket) {
            static::$socket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
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

        socket_sendto(static::$socket, $data, mb_strlen($data), 0, $this->server_ip, $this->server_port);
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