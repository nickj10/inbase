<?php
declare(strict_types=1);

namespace Cole\Projs\Controller;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use Slim\Views\Twig;

class HomeController
{
    public function __construct(private Twig $twig)
    {}

    public function apply(Request $request, Response $response): Response {
        return $this->twig->render($response, 'home.twig');
    }
}