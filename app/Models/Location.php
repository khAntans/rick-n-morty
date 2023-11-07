<?php

declare(strict_types=1);

namespace App\Models;

use App\Apis\TimeApi;
use Carbon\Carbon;

class Location
{
    private string $cityName;
    private string $country;
    private float $latitude;
    private float $longitude;
    private Carbon $localTime;

    public function __construct(string $cityName, string $country, float $latitude, float $longitude)
    {
        $this->cityName = trim($cityName);
        $this->country = $country;
        $this->latitude = $latitude;
        $this->longitude = $longitude;
        $timeApi = new TimeApi();
        $this->localTime = $timeApi->fetch($latitude, $longitude);
    }

    public function getCityName(): string
    {
        return $this->cityName;
    }

    public function getCountry(): string
    {
        return $this->country;
    }

    public function getLatitude(): float
    {
        return $this->latitude;
    }

    public function getLongitude(): float
    {
        return $this->longitude;
    }

    public function getLocalTime(): Carbon
    {
        return $this->localTime;
    }

}