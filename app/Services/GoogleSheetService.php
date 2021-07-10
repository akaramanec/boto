<?php

namespace App\Services;

use Google\Service\Sheets;
use Google\Service\Drive;
use Google\Client;

class GoogleSheetService
{
    /** @var Sheets */
    private $service;
    /** @var string */
    private $spreadsSheetUrl;
    /** @var Client */
    private $client;

    public function __construct($spreadsSheetUrl = null)
    {
        if (is_null($spreadsSheetUrl)) {
        $this->spreadsSheetUrl = config('google.sheets.post_spreadsheet_url');
        } else {
            $this->spreadsSheetUrl = $spreadsSheetUrl;
        }
        $this->spreadsSheetId = $this->getSpreadsSheetIdFromUrl();
        $this->client = new Client();
        $this->client->setAuthConfig(base_path( ) . '/storage/credentials-boto-telegrambot.json');
        $this->client->addScope(Drive::DRIVE);
        $this->service = new Sheets($this->client);
    }

    public function getDataFromSheet(string $sheetTitle = 'DataSheet')
    {
        return $this->service->spreadsheets_values->get($this->spreadsSheetId, $sheetTitle);
    }

    public function getSpreadsSheetIdFromUrl()
    {
        $spreadsSheetPattern = '~\/d\/(.*?)(\/|$)~';
        preg_match($spreadsSheetPattern, $this->spreadsSheetUrl, $matches);
        return $matches[1];
    }
}
