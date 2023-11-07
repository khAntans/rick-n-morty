<?php

declare(strict_types=1);

namespace App\Apis;

use App\Models\CityWeather;
use App\Models\Location;
use GuzzleHttp\Client;

class OpenWeather
{
    private Client $client;

    private const BASE_URL = "https://api.openweathermap.org/data/3.0/onecall?";

    public function __construct()
    {
        $this->client = new Client();
    }

    public function fetch(Location $location, string $apiKey): CityWeather
    {
        $latitude = $location->getLatitude();
        $longitude = $location->getLongitude();
        $weather = file_get_contents(self::BASE_URL . "lat=$latitude&lon=$longitude&units=metric&appid=$apiKey");
        $weatherData = json_decode($weather);
        $temp = round($weatherData->hourly[0]->temp);
        $feelsLike = round($weatherData->hourly[0]->feels_like);
        $windSpeed = round($weatherData->hourly[0]->wind_speed, 1);
        $windDirection = $this->windDirection($weatherData->current->wind_deg);
        $humidity = $weatherData->hourly[0]->humidity;
        $sunrise = date("H:i:s", $weatherData->current->sunrise);
        $sunset = date("H:i:s", $weatherData->current->sunset);
        $img = $weatherData->current->weather[0]->icon . ".png";

        return new CityWeather($location, $temp, $feelsLike, $windSpeed, $windDirection, $humidity, $sunrise, $sunset, $img);

    }

    private function windDirection(float $windDeg): string
    {
        if ($windDeg > 22.5 && $windDeg <= 67.5) {
            return "NE wind";
        } else if ($windDeg > 67.5 && $windDeg <= 112.5) {
            return "E wind";
        } else if ($windDeg > 112.5 && $windDeg <= 157.5) {
            return "SE wind";
        } else if ($windDeg > 157.5 && $windDeg <= 202.5) {
            return "S wind";
        } else if ($windDeg > 202.5 && $windDeg <= 247.5) {
            return "SW wind";
        } else if ($windDeg > 247.5 && $windDeg <= 292.5) {
            return "W wind";
        } else if ($windDeg > 292.5 && $windDeg <= 337.5) {
            return "NW wind";
        } else {
            return "N wind";
        }
    }

}