<?php

class TransportUDP implements TransportInterface {
    private $server_ip = '127.0.0.1';
    private $server_port = '41452';

    private static $socket = null;

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