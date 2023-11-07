<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Apis\GeoCoding;
use App\Apis\OpenWeather;
use Twig\Environment;


class WeatherController
{
    public static function update(Environment $twig, string $apiKey, string $input = 'los angeles'): void
    {
        if (!$input) $input = 'los angeles';
        $location = (new GeoCoding())->fetch($input, $apiKey);
        $cityWeather = (new OpenWeather())->fetch($location, $apiKey);

        $twig->addGlobal('cityName', $location->getCityName());
        $twig->addGlobal('country', $location->getCountry());
        $twig->addGlobal('temperature', $cityWeather->getTemperature());
        $twig->addGlobal('feelsLike', $cityWeather->getFeelsLike());
        $twig->addGlobal('windDir', $cityWeather->getWindDirection());
        $twig->addGlobal('windSpeed', $cityWeather->getWindSpeed());
        $twig->addGlobal('humidity', $cityWeather->getHumidity());
        $twig->addGlobal('sunrise', $cityWeather->getLocalSunriseTime()->format("H:i:s"));
        $twig->addGlobal('sunset', $cityWeather->getLocalSunsetTime()->format("H:i:s"));
        $twig->addGlobal('imgName', $cityWeather->getImgName());
        $twig->addGlobal('localTime', $location->getLocalTime()->format('Y-m-d H:i'));
    }

}