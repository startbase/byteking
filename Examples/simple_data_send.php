<?php
require_once(dirname(__FILE__) . "/../lib/ByteKingClient.php");

//initialising transmitter and client
$data_transfer = new TransportUDP();
$data_transfer->setConfiguration('127.0.0.1', '41452');
ByteKingClient::setTransport($data_transfer);

$api = 'Oa3JTG47gp9ew8ghp616zp';
ByteKingClient::setApiKey($api);


//php script
echo "Doing something\n";

//metric sending
ByteKingClient::send('data_type_1', 'Current date is '.date('Y-m-d H:i:s'));

echo "The end of test script\n";
