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
        $url = $this->getUrl($latitude, $longitude);
        $result = $this->client->get($url);
        $result = json_decode($result->getBody()->getContents());
        $dateTime = $result->dateTime;
        $timeZone = $result->timeZone;
        return Carbon::parse($dateTime, $timeZone);
    }

    private function getUrl(float $latitude, float $longitude): string
    {
        $params = [
            "latitude" => $latitude,
            "longitude" => $longitude
        ];

        return self::BASE_URL . http_build_query($params);
    }

}