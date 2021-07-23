<?php
declare(strict_types=1);

use Cole\Projs\Controller\InvoiceController;
use Cole\Projs\Controller\HomeController;

$app->get('/', HomeController::class . ':apply')->setName('home');
$app->get('/invoices', InvoiceController::class . ':getAllInvoices')->setName('invoiceList');
$app->get('/invoices/add', InvoiceController::class . ':showCreateInvoicePage')->setName('invoiceCreateForm');
$app->post('/invoices/add', InvoiceController::class . ':createNewInvoice')->setName('invoiceCreate');
$app->delete('/invoices/{invoiceId}', InvoiceController::class . ':deleteInvoice')->setName('invoiceDelete');
$app->put('/invoices/{invoiceId}', InvoiceController::class . ':updateInvoice')->setName('invoiceUpdate');
$app->get('/invoices/{invoiceId}', InvoiceController::class . ':getInvoiceById')->setName('invoiceInfo');