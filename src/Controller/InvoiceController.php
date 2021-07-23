<?php

declare(strict_types=1);

namespace Cole\Projs\Controller;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

use Slim\Views\Twig;
use DateTime;
use Cole\Projs\Repository\InvoiceRepository;
use Cole\Projs\Model\Invoice;

final class InvoiceController
{

    public function __construct(private Twig $twig, private InvoiceRepository $db)
    {
    }

    public function showCreateInvoicePage(Request $request, Response $response): Response
    {
        return $this->twig->render($response, 'create-invoice.twig');
    }

    public function createNewInvoice(Request $request, Response $response): Response
    {
        try {
            $data = $request->getParsedBody();

            $errors = [];

            if (empty($data['clientName'])) {
                $errors['clientName'] = 'You must provide a client name';
            }

            $invoice = new Invoice(
                $data['clientName'] ?? '',
                $data['clientAddress'] ?? '',
                floatval($data['totalAmount']),
                new DateTime()
            );
            if ($data['invoiceNumber'] != '') {
                $invoice->setInvoiceNumber($data['invoiceNumber']);
            } else {
                $invoice->generateInvoiceNumber();
            }

            if (count($errors) > 0) {
                return $this->twig->render(
                    $response,
                    'create-invoice.twig',
                    [
                        'formErrors' => $errors
                    ]
                );
            } else {
                $this->db->createInvoice($invoice);
                return $response->withHeader('Location', '/invoices')->withStatus(301);
            }
        } catch (Exception $exception) {
            // You could render a .twig template here to show the error
            $response->getBody()
                ->write('Unexpected error: ' . $exception->getMessage());
            return $response->withStatus(500);
        }
    }

    public function getAllInvoices(Request $request, Response $response): Response
    {
        $invoices = $this->db->getAllInvoices();
        return $this->twig->render(
            $response,
            'invoice-list.twig',
            [
                'invoices' => $invoices
            ]
        );
    }
}
