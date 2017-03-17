<?php
// DIC configuration

$container = $app->getContainer();

// view renderer
$container['renderer'] = function ($c) {
    $settings = $c->get('settings')['renderer'];
    return new Slim\Views\PhpRenderer($settings['template_path']);
};

// monolog
$container['logger'] = function ($c) {
    $settings = $c->get('settings')['logger'];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], $settings['level']));
    return $logger;
};

spl_autoload_register(function ($classname) {
    require ("../src/" . str_replace("\\", "/", $classname) . ".php");
});

$container['\App\Controller\Controller'] = function($c) {
    $settings = $c->get('settings')['renderer'];
    return new \App\Controller\Controller(new Slim\Views\PhpRenderer($settings['template_path']));
};

$container['db'] = function ($c) {
    $db = $c['settings']['db'];
    $pdo = new PDO("mysql:host=" . $db['host'] . ";dbname=" . $db['dbname'],
        $db['user'], $db['pass']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    return $pdo;
};

$container['\App\Controller\MeetingController'] = function($c) {
    $settings = $c->get('settings')['renderer'];
    return new \App\Controller\MeetingController(new Slim\Views\PhpRenderer($settings['template_path']));
};

$container['Analyzer'] = function() {
    return new \App\Controller\Analyzer();
};
