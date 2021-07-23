<?php
declare(strict_types=1);

use Cole\Projs\Controller\CreateController;
use Cole\Projs\Controller\CreateFormController;
use Cole\Projs\Controller\HomeController;

$app->get('/', HomeController::class . ':apply')->setName('home');
$app->post('/create', CreateController::class . ':apply');