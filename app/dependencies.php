<?php

// DIC configuration.

use Jgut\Slim\Doctrine\EntityManagerBuilder;

$container = $app->getContainer();

$controllers = [
    'App\Controller\HomeController' => 'App\Controller\HomeController',
];

foreach ($controllers as $controller) {
    $container[$controller] = function ($c) use ($controller) {
        return new $controller($c);
    };
}

$container['renderer'] = function ($c) {
    $settings = $c->get('settings')['renderer'];
    return new Slim\Views\PhpRenderer($settings['template_path']);
};

$container['view'] = function ($c) {
    $settings = $c->get('settings');
    $view = new \Slim\Views\Twig($settings['view']['template_path'], $settings['view']['twig']);
    $view->addExtension(new Slim\Views\TwigExtension($c->get('router'), $c->get('request')->getUri()));
    //$view->addExtension(new Twig_Extension_Debug());
    return $view;
};

$container['logger'] = function ($c) {
    $settings = $c->get('settings')['logger'];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], Monolog\Logger::DEBUG));
    return $logger;
};

$container['db'] = function ($c) {
    try {
        $settings = $c->get('settings')['doctrine']['connection'];
        $db = new PDO('mysql:host='.$settings['host'].';dbname='.$settings['dbname'].';charset=utf8mb4', $settings['user'], $settings['password'], [\PDO::ATTR_PERSISTENT => false]);
    } catch (PDOException $e) {
        die($e->getMessage());
    }
    return $db;
};

$container['em'] = function ($c) {
    return EntityManagerBuilder::build($c->get('settings')['doctrine']);
};

$container['flash'] = function ($c) {
    return new \Slim\Flash\Messages;
};
