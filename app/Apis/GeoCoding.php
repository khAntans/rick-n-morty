<?php

declare(strict_types=1);

namespace App\Apis;

use App\Models\Location;
use GuzzleHttp\Client;

class GeoCoding
{
    private Client $client;
    private const BASE_URL = "http://api.openweathermap.org/geo/1.0/direct?";

    public function __construct()
    {
        $this->client = new Client();
    }

    public function fetch(string $city, string $apiKey): Location
    {
        $url = $this->getUrl($city, $apiKey);
        $result = $this->client->get($url);
        $location_data = json_decode($result->getBody()->getContents());

        $cityName = $location_data[0]->name;
        $country = $location_data[0]->country;
        $latitude = round($location_data[0]->lat, 2);
        $longitude = round($location_data[0]->lon, 2);
        return new Location($cityName, $country, $latitude, $longitude);
    }

    private function getUrl(string $city, string $apiKey): string
    {
        $params = [
            "q" => $city,
            "limit" => 1,
            "appid" => $apiKey
        ];

        return self::BASE_URL . http_build_query($params);
    }

}