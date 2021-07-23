<?php
declare(strict_types=1);

namespace Cole\Projs\Repository;

use PDO;
use Cole\Projs\Model\Invoice;
use Cole\Projs\Repository\InvoiceRepository;

final class MySQLInvoiceRepository implements InvoiceRepository
{
    private const DATE_FORMAT = 'Y-m-d H:i:s';

    private PDOSingleton $database;

    public function __construct(PDOSingleton $database)
    {
        $this->database = $database;
    }

    public function createInvoice(Invoice $invoice): void
    {
        $query = <<<'QUERY'
        INSERT INTO invoices(invoiceNumber, clientName, clientAddress, totalAmount, createdAt)
        VALUES(:invoiceNumber, :clientName, :clientAddress, :totalAmount, :createdAt)
QUERY;
        $statement = $this->database->connection()->prepare($query);

        $invoiceNumber = $invoice->getInvoiceNumber();
        $clientName = $invoice->getClientName();
        $clientAddress = $invoice->getClientAddress();
        $totalAmount = $invoice->getTotalAmount();
        $createdAt = $invoice->createdAt()->format(self::DATE_FORMAT);

        $statement->bindParam('invoiceNumber', $invoiceNumber, PDO::PARAM_STR);
        $statement->bindParam('clientName', $clientName, PDO::PARAM_STR);
        $statement->bindParam('clientAddress', $clientAddress, PDO::PARAM_STR);
        $statement->bindParam('totalAmount', $totalAmount, PDO::PARAM_STR);
        $statement->bindParam('createdAt', $createdAt, PDO::PARAM_STR);

        $statement->execute();
    }
}