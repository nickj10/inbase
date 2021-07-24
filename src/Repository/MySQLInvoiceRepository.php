<?php

declare(strict_types=1);

namespace Cole\Projs\Repository;

use PDO;
use DateTime;
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
        INSERT INTO invoices(invoiceNumber, clientName, baseAmount, iva, totalAmount, invoiceDate, dueDate, paymentDate, createdAt, paid)
        VALUES(:invoiceNumber, :clientName, :baseAmount,  :iva, :totalAmount, :invoiceDate, :dueDate, :paymentDate, :createdAt, :paid)
        QUERY;

        $statement = $this->database->connection()->prepare($query);

        $invoiceNumber = $invoice->getInvoiceNumber();
        $clientName = $invoice->getClientName();
        $baseAmount = $invoice->getBaseAmount();
        $iva = $invoice->getIVA();
        $totalAmount = $invoice->getTotalAmount();
        $invoiceDate = $invoice->invoiceDate()->format(self::DATE_FORMAT);
        $dueDate = $invoice->dueDate()->format(self::DATE_FORMAT);
        $paymentDate = $invoice->paymentDate()->format(self::DATE_FORMAT);
        $createdAt = $invoice->createdAt()->format(self::DATE_FORMAT);
        $paid = 0;
        if ($invoice->isPaid()) {
            $paid = 1;
        }

        $statement->bindParam('invoiceNumber', $invoiceNumber, PDO::PARAM_STR);
        $statement->bindParam('clientName', $clientName, PDO::PARAM_STR);
        $statement->bindParam('baseAmount', $baseAmount, PDO::PARAM_STR);
        $statement->bindParam('iva', $iva, PDO::PARAM_STR);
        $statement->bindParam('totalAmount', $totalAmount, PDO::PARAM_STR);
        $statement->bindParam('invoiceDate', $createdAt, PDO::PARAM_STR);
        $statement->bindParam('dueDate', $createdAt, PDO::PARAM_STR);
        $statement->bindParam('paymentDate', $createdAt, PDO::PARAM_STR);
        $statement->bindParam('createdAt', $createdAt, PDO::PARAM_STR);
        $statement->bindParam('paid', $paid, PDO::PARAM_STR);

        $statement->execute();
    }

    public function getAllInvoices()
    {
        $query = <<<'QUERY'
        SELECT * FROM invoices
        QUERY;

        $statement = $this->database->connection()->prepare($query);
        $statement->execute();

        $data = $statement->fetchAll(PDO::FETCH_OBJ);

        $invoices = [];
        if ($data != NULL) {
            for ($i = 0; $i < count($data); $i++) {
                $invoices[$i] = Invoice::fromDatabase(
                    intval($data[$i]->invoiceId),
                    $data[$i]->invoiceNumber,
                    $data[$i]->clientName,
                    floatval($data[$i]->baseAmount),
                    floatval($data[$i]->iva),
                    floatval($data[$i]->totalAmount),
                    DateTime::createFromFormat(self::DATE_FORMAT, $data[$i]->invoiceDate),
                    DateTime::createFromFormat(self::DATE_FORMAT, $data[$i]->dueDate),
                    DateTime::createFromFormat(self::DATE_FORMAT, $data[$i]->paymentDate),
                    DateTime::createFromFormat(self::DATE_FORMAT, $data[$i]->createdAt),
                    $data[$i]->paid == 1
                );
            }
        }

        return $invoices;
    }

    public function getInvoiceById($id)
    {
        $query = "SELECT * FROM invoices WHERE invoiceId = :id;";
        $statement = $this->database->connection()->prepare($query);
        $statement->bindParam(':id', $id, PDO::PARAM_STR);

        $statement->execute();
        $count = $statement->rowCount();
        if ($count > 0) {
            $row = $statement->fetch(PDO::FETCH_OBJ);
            return $row;
        }
        return null;
    }

    public function deleteInvoice(int $invoiceId): bool {
        $query = <<<'QUERY'
        DELETE FROM invoices WHERE invoiceId=:id
        QUERY;

        $statement = $this->database->connection()->prepare($query);

        $statement->bindParam('id', $invoiceId, PDO::PARAM_STR);
        $statement->execute(); 
        return true;
    }

    public function updateInvoice(int $invoiceId, Invoice $invoice): bool {
        $query = <<<'QUERY'
        UPDATE invoices
        SET invoiceNumber=:invoiceNumber, clientName=:clientName,
        baseAmount=:baseAmount, iva=:iva, totalAmount=:totalAmount,
        invoiceDate=:invoiceDate, dueDate=:dueDate, paymentDate=:paymentDate,
        paid=:paid
        WHERE invoiceId=:id
        QUERY;

        $statement = $this->database->connection()->prepare($query);

        $invoiceNumber = $invoice->getInvoiceNumber();
        $clientName = $invoice->getClientName();
        $clientAddress = $invoice->getClientAddress();
        $totalAmount = $invoice->getTotalAmount();

        $statement->bindParam('invoiceNumber', $invoiceNumber, PDO::PARAM_STR);
        $statement->bindParam('clientName', $clientName, PDO::PARAM_STR);
        $statement->bindParam('baseAmount', $baseAmount, PDO::PARAM_STR);
        $statement->bindParam('iva', $iva, PDO::PARAM_STR);
        $statement->bindParam('totalAmount', $totalAmount, PDO::PARAM_STR);
        $statement->bindParam('invoiceDate', $createdAt, PDO::PARAM_STR);
        $statement->bindParam('dueDate', $createdAt, PDO::PARAM_STR);
        $statement->bindParam('paymentDate', $createdAt, PDO::PARAM_STR);
        $statement->bindParam('paid', $paid, PDO::PARAM_STR);
        $statement->bindParam('id', $invoiceId, PDO::PARAM_STR);

        $statement->execute();
        return true;
    }
}
