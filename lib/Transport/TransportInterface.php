<?php

/**
 * Interface TransportInterface
 */
interface TransportInterface {

    public function initConnection();

    public function closeConnection();

    public function send($data);
}