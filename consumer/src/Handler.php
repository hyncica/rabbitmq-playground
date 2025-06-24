<?php

/**
 * Author: Michal Hynčica <michal@hyncica.eu>
 * Date: 24.06.2025
 */

declare(strict_types=1);

namespace Hyncica\RabbitMqPlayground\Consumer;

use Hyncica\RabbitMqPlayground\Consumer\Spreadsheets\Spreadsheets;
use PhpAmqpLib\Message\AMQPMessage;

/**
 * Class for handling messages from queue.
 */
readonly class Handler
{
    public function __construct(
        private readonly Spreadsheets $spreadsheets,
    )
    {

    }

    /**
     * Incoming message handler.
     *
     * @param \PhpAmqpLib\Message\AMQPMessage $message
     *
     * @return void
     * @throws \Google\Service\Exception
     */
    public function handle(AMQPMessage $message): void
    {
        $data = json_decode($message->getBody(), true);
        $this->spreadsheets->append($data['name'], (int) $data['amount']);
    }
}
