<?php

declare(strict_types=1);

namespace Cole\Projs\Controller;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Http\Response as NewResponse;

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

    private function parseDate($date)
    {
        $timestamp = strtotime($date); 
        $day = intval(date('d',$timestamp));
        $month = intval(date('m',$timestamp));
        $year = intval(date('Y',$timestamp));
        $newDate = new DateTime();
        $newDate->setDate($year, $month, $day);
        return $newDate;
    }

    public function createNewInvoice(Request $request, Response $response): Response
    {
        try {
            $data = $request->getParsedBody();

            $errors = [];

            if (empty($data['clientName'])) {
                $errors['clientName'] = 'You must provide a client name';
            }
            
            if (!empty($data['paymentDate'])) {
                $paymentDate = self::parseDate($data['paymentDate']);
            } else {
                $paymentDate = null;
            }

            // TODO: Validate the fields

            $invoice = new Invoice(
                $data['clientName'] ?? '',
                floatval($data['baseAmount']),
                floatval($data['iva']) == 0 ? 21 : floatval($data['iva']),
                floatval($data['totalAmount']),
                self::parseDate($data['invoiceDate']),
                self::parseDate($data['dueDate']),
                $paymentDate,
                new DateTime(), // createdAt,
                !empty($data['paid'])
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

    public function getInvoiceById(Request $request, NewResponse $response): NewResponse
    {
        $id = $request->getAttribute('invoiceId');
        $invoice = $this->db->getInvoiceById($id);
        if($invoice == null) {
            $responseMessage = 'There is no invoice with id ' . $id; // TODO: Add this to flash messages and show invoice details page
            return $response->withJson($responseMessage, 404);
        }
        // return $this->twig->render(
        //     $response,
        //     'invoice-details.twig',
        //     [
        //         'invoices' => $invoices
        //     ]
        // );

        return $response->withJson($invoice, 200);
    }

    public function deleteInvoice(Request $request, Response $response): Response
    {
        $id = $request->getAttribute('invoiceId');
        if ($id != null) {
            $deleted = $this->db->deleteInvoice(intval($id));
            return $response->withStatus(200);
        }
        return $reponse->withStatus(404);
    }

    public function updateInvoice(Request $request, Response $response): Response
    {
        $id = $request->getAttribute('invoiceId');
        $data = $request->getParsedBody();

        $errors = [];

        if ($id != null) { 
            $invoice = new Invoice(
                $data['clientName'] ?? '',
                $data['clientAddress'] ?? '',
                floatval($data['totalAmount']),
                new DateTime() // Just to put a value
            );
            $invoiceNum = $data['invoiceNumber'] ?? '';
            $invoice->setInvoiceNumber($invoiceNum);
            $deleted = $this->db->updateInvoice(intval($id), $invoice);
            return $response->withHeader('Location', '/invoices')->withStatus(301);
        }
        return $reponse->withStatus(500);
    }
}
