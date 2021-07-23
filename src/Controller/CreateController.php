<?php
declare(strict_types=1);

namespace Cole\Projs\Controller;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

use Slim\Views\Twig;

final class CreateController
{

    public function __construct(private Twig $twig)
    {
    }

    public function apply(Request $request, Response $response): Response
    {
        return $this->twig->render($response, 'create.twig');
    }
}