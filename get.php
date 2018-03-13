<?php
require_once('path.inc)';
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');

use PhpAmqpLib\Connection\AMQPStreamConnection;
$connection = new AMQPStreamConnection("testRabbitMQ.ini", "testServer");
$channel = $connection->channel();
$channel->queue_declare('hello', false, false, false, false);
echo ' [*] Waiting for messages. To exit press CTRL+C', "\n";
$callback = function($msg) {
  echo " [x] Received ", $msg->body, "\n";
};
$channel->basic_consume('hello', '', false, true, false, false, $callback);
while(count($channel->callbacks)) {
    $channel->wait();
}
$channel->close();
$connection->close();
?>
