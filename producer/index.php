<?php

/**
 * Author: Michal Hynčica <michal@hyncica.eu>
 * Date: 24.06.2025
 */

declare(strict_types=1);
?>
<html lang="en">
    <head>
        <title>Basic RabbitMQ message producer</title>
    </head>
    <body>
        <?php if($_GET['sent'] ?? false): ?>
            <p>Message has been sent to queue</p>
        <?php endif; ?>
        <form method="post" action="submit.php">
            <p>
                <label for="name">Name:</label>
                <input type="text" id="name" name="name">
            </p>
            <p>
                <label for="amount">Amount:</label>
                <input id="amount" type="number" step="1" name="amount">
            </p>
            <p>
                <button type="submit">Submit</button>
            </p>
        </form>
    </body>
</html>

