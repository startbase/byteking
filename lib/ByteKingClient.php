<?php
namespace ByteKing;

use ByteKing\Transport\TransportInterface;

/**
 * Class ByteKingClient
 */
class ByteKingClient {
    /** @var null | string */
    protected static $api_key = null;
    /** @var TransportInterface */
    private static $transport;

    /**
     * @param $type
     * @param $data
     * @throws \Exception
     */
    public static function send($type, $data)
    {
        if(!static::$api_key) {
            throw new \Exception('Wrong api key');
        }

        $message = static::getPreparedData($type, $data);
        static::$transport->send($message);
    }

    /**
     * @param string|int $data_type
     * @param mixed $data
     * @return string
     */
    private static function getPreparedData($data_type, $data) {
        $message = [
            'api_key' => static::$api_key,
            'type' => $data_type,
            'data' => $data
        ];

        return json_encode($message);
    }

    /**
     * @param string $api_key
     */
    public static function setApiKey($api_key)
    {
        static::$api_key = $api_key;
    }

    /**
     * @param TransportInterface $transport
     */
    public static function setTransport(TransportInterface $transport)
    {
        static::$transport = $transport;
    }

}