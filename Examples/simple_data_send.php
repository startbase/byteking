<?php
use startbase\ByteKing\Transport\TransportUDP;
use startbase\ByteKing\ByteKingClient;

require_once(dirname(__FILE__) . "/../src/ByteKingClient.php");
require_once(dirname(__FILE__) . "/../src/Transport/TransportInterface.php");
require_once(dirname(__FILE__) . "/../src/Transport/TransportUDP.php");

// initialising transport and client
$transport = new TransportUDP('127.0.0.1', '4000');

$bk_client = new ByteKingClient();
$bk_client->setTransport($transport);

$bk_client->setApiKey('your_key');

// php script
echo "Doing something\n";

// metric sending

$msg = 'Current date is '.date('Y-m-d H:i:s').' ';

// uncomment for long msg
/*
for ($i = 0; $i < 300000; $i++) {
    $msg .= rand(0, 9);
}*/

$bk_client->send('long_type', 'Msg: '.$msg);

echo "The end of test script\n";