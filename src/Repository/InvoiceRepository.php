<?php

declare(strict_types=1);

namespace Cole\Projs\Repository;

use Cole\Projs\Model\Invoice;

interface InvoiceRepository
{
    public function createInvoice(Invoice $invoice): void;
    public function getAllInvoices(); 
    public function deleteInvoice(int $invoiceId): bool;
    public function updateInvoice(int $invoiceId, Invoice $invoice): bool;
    public function getInvoiceById($id);
}
