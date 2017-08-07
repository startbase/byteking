<?php
use ByteKing\Transport\TransportUDP;
use ByteKing\ByteKingClient;

require_once(dirname(__FILE__) . "/../lib/ByteKingClient.php");
require_once(dirname(__FILE__) . "/../lib/Transport/TransportInterface.php");
require_once(dirname(__FILE__) . "/../lib/Transport/TransportUDP.php");

//initialising transmitter and client
$data_transfer = new TransportUDP();
$data_transfer->setConfiguration('127.0.0.1', '4000');
ByteKingClient::setTransport($data_transfer);

$api = 'your_key';
ByteKingClient::setApiKey($api);


//php script
echo "Doing something\n";

//metric sending

$msg = 'Current date is '.date('Y-m-d H:i:s').' ';

// uncomment for long msg
/*
for ($i = 0; $i < 300000; $i++) {
    $msg .= rand(0, 9);
}*/

ByteKingClient::send('long_type', 'Msg: '.$msg);

echo "The end of test script\n";
