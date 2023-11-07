<?php

declare(strict_types=1);

use App\Apis\RickAndMorty;
use App\Controllers\WeatherController;
use App\Response;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;


require_once "../vendor/autoload.php";

$episodeData = (new RickAndMorty)->fetch();
//var_dump(dirname(__DIR__, 1));
//die;
$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__, 1));
$dotenv->load();

$dispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $router) {
    $router->addRoute('GET', '/[...]', ['App\Controllers\EpisodeController', 'index']);
    $router->addRoute('GET', '/EpisodeIndex[...]', ['App\Controllers\EpisodeController', 'index']);
    $router->addRoute('GET', '/EpisodeIndex/page={page:\d+}[?city]', ['App\Controllers\EpisodeController', 'index']);
    $router->addRoute('GET', '/Episode/{id:\d+}[...]', ['App\Controllers\EpisodeController', 'show']);
    $router->addRoute('GET', '/Seasons[...]', ['App\Controllers\EpisodeController', 'seasonIndex']);
    $router->addRoute('GET', '/Season/{id:\d+}[...]', ['App\Controllers\EpisodeController', 'seasonShow']);
    $router->addRoute('GET', '/search?input={input}[...]', ['App\Controllers\EpisodeController', 'search']);

});

// Fetch method and URI from somewhere
$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

$pos = strpos($uri, '?');
if (false !== $pos && false === strpos($uri, 'search')) {
    $uri = substr($uri, 0, $pos);

}
$uri = rawurldecode($uri);

//var_dump($httpMethod);
//var_dump($uri);
//var_dump($_POST);
//var_dump($_GET);
$routeInfo = $dispatcher->dispatch($httpMethod, $uri);
switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        // ... 404 Not Found
        $loader = new FilesystemLoader('../app/views');
        $twig = new Environment($loader);
        echo $twig->render('episode/base.html' . '.twig');
        echo "</br><h1>404</h1>";
        var_dump($routeInfo);

        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        // ... 405 Method Not Allowed
        $loader = new FilesystemLoader('../app/views');
        $twig = new Environment($loader);
        echo $twig->render('episode/base.html' . '.twig');

        echo "</br><h1>405</h1>";

        break;
    case FastRoute\Dispatcher::FOUND:
        [$controller, $method] = $routeInfo[1];
        $vars = $routeInfo[2];
//        var_dump($_POST);
//        var_dump($vars);
        if (isset($_GET["city"])) $cityName = $_GET["city"];

        $response = (new $controller)->{$method}($episodeData, $vars);

        $loader = new FilesystemLoader('../app/views/');

        $twig = new Environment($loader);
        if (isset($cityName)) {
            WeatherController::update($twig, $_ENV["OPEN_API_KEY"], $cityName);
        } else {
            WeatherController::update($twig, $_ENV["OPEN_API_KEY"]);
        }
        /**
         * @var Response $response
         */
        echo $twig->render($response->getViewName() . '.twig', $response->getData());
        break;
}