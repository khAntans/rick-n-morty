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

        $location = file_get_contents("https://api.openweathermap.org/geo/1.0/direct?q=$city&limit=1&appid=$apiKey");
        $location_data = json_decode($location);
        $cityName = $location_data[0]->name;
        $country = $location_data[0]->country;
        $latitude = round($location_data[0]->lat, 2);
        $longitude = round($location_data[0]->lon, 2);
        return new Location($cityName, $country, $latitude, $longitude);
    }

}