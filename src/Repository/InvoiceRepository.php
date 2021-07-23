<?php

declare(strict_types=1);

namespace Cole\Projs\Repository;

use Cole\Projs\Model\Invoice;

interface InvoiceRepository
{
    public function createInvoice(Invoice $invoice): void;
}
