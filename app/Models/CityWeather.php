<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\Carbon;

class CityWeather
{
    private Location $city;
    private float $temperature;
    private float $feelsLike;
    private float $windSpeed;
    private string $windDirection;
    private int $humidity;
    private string $sunrise;
    private string $sunset;
    private string $imgName;

    public function __construct(Location $city,
                                float    $temperature,
                                float    $feelsLike,
                                float    $windSpeed,
                                string   $windDirection,
                                int      $humidity,
                                string   $sunrise,
                                string   $sunset,
                                string   $imgName
    )
    {
        $this->city = $city;
        $this->temperature = $temperature;
        $this->feelsLike = $feelsLike;
        $this->windSpeed = $windSpeed;
        $this->windDirection = $windDirection;
        $this->humidity = $humidity;
        $this->sunrise = $sunrise;
        $this->sunset = $sunset;
        $this->imgName = $imgName;
    }

    public function getCity(): Location
    {
        return $this->city;
    }

    public function getTemperature(): int
    {
        return (int)round($this->temperature);
    }

    public function getFeelsLike(): int
    {
        return (int)round($this->feelsLike);
    }

    public function getWindSpeed(): float
    {
        return $this->windSpeed;
    }

    public function getWindDirection(): string
    {
        return $this->windDirection;
    }

    public function getHumidity(): int
    {
        return $this->humidity;
    }

    public function getSunrise(): string
    {
        return $this->sunrise;
    }

    public function getSunset(): string
    {
        return $this->sunset;
    }

    public function getImgName(): string
    {
        return $this->imgName;
    }

    public function getLocalSunriseTime(): Carbon
    {
        $timeZone = $this->getCity()->getLocalTime()->timezone->getName();
        return Carbon::parse($this->getSunrise(), 'Europe/London')->tz($timeZone);
    }

    public function getLocalSunsetTime(): Carbon
    {
        $timeZone = $this->getCity()->getLocalTime()->timezone->getName();
        return Carbon::parse($this->getSunset(), 'Europe/London')->tz($timeZone);
    }

}