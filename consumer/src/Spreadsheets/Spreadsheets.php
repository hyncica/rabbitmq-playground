<?php

/**
 * Author: Michal Hynčica <michal@hyncica.eu>
 * Date: 24.06.2025
 */

declare(strict_types=1);

namespace Hyncica\RabbitMqPlayground\Consumer\Spreadsheets;

use Google\Client;
use Google\Service\Sheets;

/**
 * Class that works with Google spreadsheets through API
 */
class Spreadsheets
{
    /**
     * @var \Google\Service\Sheets
     */
    private Sheets $service;
    private string $searchRange;
    private Client $client;
    private string $spreadsheetId;
    private string $listName;

    public function __construct()
    {
        $this->spreadsheetId = $_ENV['GOOGLE_SPREADSHEET_ID'];
        $this->client = new Client();
        $this->client->setApplicationName('RabbitMQ Playground Consumer');
        $this->client->useApplicationDefaultCredentials();
        $this->client->setScopes(Sheets::SPREADSHEETS);
        $this->listName = $_ENV['GOOGLE_LIST_NAME'];
        $this->searchRange = $this->listName . "!A:B";
        $this->service = new Sheets($this->client);
    }

    /**
     * Append data to google spreadsheet.
     *
     * Try to find a row with the same name.
     * If the row exists, update the value.
     * If the row doesn't exist, append new row.
     *
     * @param string $name
     * @param int $amount
     *
     * @return void
     * @throws \Google\Service\Exception
     */
    public function append(string $name, int $amount): void
    {
        [$index, $row] = $this->findRow($name);
        if ($index === null) {
            $this->appendNew($name, $amount);
        } else {
            $this->updateRow($index, $row[0], $amount + ($row[1] ?? 0));
        }
    }

    /**
     * Search for existing row.
     *
     * @param string $name
     *
     * @return array|null[]
     * @throws \Google\Service\Exception
     */
    private function findRow(string $name): array
    {
        $response = $this->service->spreadsheets_values->get($this->spreadsheetId, $this->searchRange);
        $values = $response->getValues();
        if (empty($values)) {
            return [null, null];
        }

        foreach ($values as $index => $row) {
            if (isset($row[0]) && $row[0] === $name) {
                return [$index, $row];
            }
        }

        return [null, null];
    }

    /**
     * Append new row.
     *
     * @param string $name
     * @param int $amount
     *
     * @return void
     * @throws \Google\Service\Exception
     */
    private function appendNew(string $name, int $amount): void
    {
        $row = new Sheets\ValueRange([
            'values' => [
                [$name, $amount]
            ],
        ]);
        $params = [
            'valueInputOption' => 'RAW',
        ];
        $this->service->spreadsheets_values->append(
            $this->spreadsheetId,
            $this->listName . "!A1",
            $row,
            $params
        );
    }

    /**
     * Update existing row.
     *
     * @param int $index
     * @param string $name
     * @param int $amount
     *
     * @return void
     * @throws \Google\Service\Exception
     */
    private function updateRow(int $index, string $name, int $amount): void
    {
        $targetRange = $this->listName . '!A' .  ($index + 1);
        $newRow = new Sheets\ValueRange([
            'values' => [
                [$name, $amount]
            ],
        ]);
        $params = [
            'valueInputOption' => 'RAW',
        ];
        $this->service->spreadsheets_values->update(
            $this->spreadsheetId,
            $targetRange,
            $newRow,
            $params
        );
    }
}
