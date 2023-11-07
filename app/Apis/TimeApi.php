<?php

declare(strict_types=1);

namespace App\Apis;

use Carbon\Carbon;
use GuzzleHttp\Client;

class TimeApi
{
    private Client $client;
    private const BASE_URL = "https://timeapi.io/api/Time/current/coordinate?";

    public function __construct()
    {
        $this->client = new Client();
    }

    public function fetch(float $latitude, float $longitude): Carbon
    {
        $result = json_decode(file_get_contents("https://timeapi.io/api/Time/current/coordinate?latitude=$latitude&longitude=$longitude"));
        $dateTime = $result->dateTime;
        $timeZone = $result->timeZone;
        return Carbon::parse($dateTime, $timeZone);
    }

}