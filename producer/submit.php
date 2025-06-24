<?php

/**
 * Author: Michal Hynčica <michal@hyncica.eu>
 * Date: 24.06.2025
 */

declare(strict_types=1);

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

require_once __DIR__ . '/vendor/autoload.php';

$connection = new AMQPStreamConnection('rabbit', 5672, 'guest', 'guest');
$channel = $connection->channel();

$data =[
    'name' => trim($_POST['name']),
    'amount' => (int) $_POST['amount'],
];

$msg = new AMQPMessage(json_encode($data));
$channel->basic_publish($msg, '', 'test');
$channel->close();
$connection->close();

header('Location: index.php?sent=1');
