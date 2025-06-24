<?php

/**
 * Author: Michal Hynčica <michal@hyncica.eu>
 * Date: 24.06.2025
 */

declare(strict_types=1);

require __DIR__ . '/vendor/autoload.php';

$app = new \Hyncica\RabbitMqPlayground\Consumer\Application();
$app->run();

