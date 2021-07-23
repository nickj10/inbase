<?php
declare(strict_types=1);

namespace Cole\Projs\Controller;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use Slim\Views\Twig;

final class CreateFormController
{
    public function __construct(private Twig $twig)
    {
    }

    /**
     * Renders the form
     */
    public function apply(Request $request, Response $response): Response
    {
        return $this->twig->render($response, 'create.twig');
    }
}