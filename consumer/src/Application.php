<?php

/**
 * Author: Michal Hynčica <michal@hyncica.eu>
 * Date: 24.06.2025
 */

declare(strict_types=1);

namespace Hyncica\RabbitMqPlayground\Consumer;

use Hyncica\RabbitMqPlayground\Consumer\Spreadsheets\Spreadsheets;
use PhpAmqpLib\Connection\AMQPStreamConnection;

/**
 * Class that represents an application.
 */
class Application
{
    private Handler $handler;
    private Spreadsheets $spreadsheets;
    public function __construct()
    {
        $this->spreadsheets = new Spreadsheets();
        $this->handler = new Handler($this->spreadsheets);
    }

    /**
     * Consume messages from queue and call handler when a message is received.
     *
     * @return void
     * @throws \Exception
     */
    public function run(): void
    {
        $connection = new AMQPStreamConnection('rabbit', 5672, 'guest', 'guest');
        $channel = $connection->channel();
        $channel->queue_declare('test', false, false, false, false);
        $channel->basic_consume(
            'test',
            '',
            false,
            true,
            false,
            false,
            [$this->handler, 'handle']
        );
        try {
            $channel->consume();
        } catch (\Throwable $e) {
            echo $e->getMessage();
        }
    }
}
