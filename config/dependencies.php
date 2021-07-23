<?php
declare(strict_types=1);

use DI\Container;
use Cole\Projs\Controller\CreateController;
use Cole\Projs\Controller\CreateFormController;
use Cole\Projs\Controller\HomeController;
use Cole\Projs\Repository\PDOSingleton;
use Psr\Container\ContainerInterface;
use Slim\Views\Twig;

$container = new Container();

$container->set(
    'view',
    function () {
        return Twig::create(__DIR__ . '/../templates', ['cache' => false]);
    }
);

$container->set('db', function () {
    return PDOSingleton::getInstance(
        $_ENV['MYSQL_USER'],
        $_ENV['MYSQL_PASSWORD'],
        $_ENV['MYSQL_HOST'],
        $_ENV['MYSQL_PORT'],
        $_ENV['MYSQL_DATABASE']
    );
});

$container->set(
    HomeController::class,
    function(ContainerInterface $c) {
        return new HomeController($c->get('view'));
    }
);

$container->set(
    CreateController::class,
    function(ContainerInterface $c) {
        return new CreateController($c->get('view'));
    }
);

$container->set(
    CreateFormController::class,
    function(ContainerInterface $c) {
        return new CreateController($c->get('view'));
    }
);