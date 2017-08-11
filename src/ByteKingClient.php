<?php
namespace startbase\ByteKing;

use startbase\ByteKing\Transport\TransportInterface;

/**
 * Class ByteKingClient
 */
class ByteKingClient {
    /** @var null|string */
    protected $api_key;

    /** @var TransportInterface */
    private $transport;

    /**
     * @param $type
     * @param $data
     * @throws \Exception
     */
    public function send($type, $data) {
        if (!$this->api_key) {
            throw new \Exception('Wrong api key');
        }

        if (!$this->transport) {
            throw new \Exception('Transport not set');
        }

        $message = $this->getPreparedData($type, $data);
        $this->transport->send($message);
    }

    /**
     * @param string|int $data_type
     * @param mixed $data
     * @return string
     */
    private function getPreparedData($data_type, $data) {
        $message = [
            'api_key' => $this->api_key,
            'type' => $data_type,
            'data' => $data
        ];

        return json_encode($message);
    }

    /**
     * @param string $api_key
     */
    public function setApiKey($api_key) {
        $this->api_key = $api_key;
    }

    /**
     * @param TransportInterface $transport
     */
    public function setTransport(TransportInterface $transport) {
        $this->transport = $transport;
    }

}