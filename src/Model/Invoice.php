<?php

declare(strict_types=1);

namespace Cole\Projs\Model;

use DateTime;

final class Invoice
{
    private int $invoiceId;
    private string $invoiceNumber;
    private string $clientName;
    private float $baseAmount;
    private float $iva;
    private float $totalAmount;
    private DateTime $invoiceDate;
    private DateTime $dueDate;
    private DateTime $paymentDate;
    private DateTime $createdAt;
    private bool $paid;

    public function __construct(
        string $clientName,
        float $baseAmount,
        float $iva,
        float $totalAmount,
        DateTime $invoiceDate,
        DateTime $dueDate,
        $paymentDate,
        DateTime $createdAt,
        bool $paid
    ) {
        $this->clientName = $clientName;
        $this->baseAmount = $baseAmount;
        $this->iva = $iva;
        $this->totalAmount = $totalAmount;
        $this->invoiceDate = $invoiceDate;
        $this->dueDate = $dueDate;
        if (!empty($paymentDate)) {
            $this->paymentDate = $paymentDate;
        }
        $this->createdAt = $createdAt;
        $this->paid = $paid;
    }

    public static function fromDatabase(
        int $invoiceId,
        string $invoiceNumber,
        string $clientName,
        float $baseAmount,
        float $iva,
        float $totalAmount,
        DateTime $invoiceDate,
        DateTime $dueDate,
        $paymentDate,
        DateTime $createdAt,
        bool $paid
    ) {
        $instance = new self(
            $clientName,
            $baseAmount,
            $iva,
            $totalAmount,
            $invoiceDate,
            $dueDate,
            $paymentDate,
            $createdAt,
            $paid);
        $instance->invoiceNumber = $invoiceNumber;
        $instance->invoiceId = $invoiceId;
        return $instance;
    }

    public function generateInvoiceNumber()
    {
        $this->invoiceNumber = uniqid('', true); // Test purposes only
    }

    public function getInvoiceId(): int
    {
        return $this->invoiceId;
    }

    public function getInvoiceNumber(): string
    {
        return $this->invoiceNumber;
    }

    public function getClientName(): string
    {
        return $this->clientName;
    }

    public function getBaseAmount(): float
    {
        return $this->baseAmount;
    }

    public function getIVA(): float
    {
        return $this->iva;
    }
    public function getTotalAmount(): float
    {
        return $this->totalAmount;
    }

    public function invoiceDate(): DateTime
    {
        return $this->createdAt;
    }

    public function dueDate(): DateTime
    {
        return $this->createdAt;
    }

    public function paymentDate(): DateTime
    {
        return $this->createdAt;
    }

    public function createdAt(): DateTime
    {
        return $this->createdAt;
    }

    public function isPaid(): bool
    {
        return $this->paid;
    }

    public function setInvoiceNumber(string $invoiceNumber)
    {
        $this->invoiceNumber = $invoiceNumber;
    }
}
