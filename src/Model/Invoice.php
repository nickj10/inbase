<?php

declare(strict_types=1);

namespace Cole\Projs\Model;

use DateTime;

final class Invoice
{
    private int $invoiceId;
    private string $invoiceNumber;
    private string $clientName;
    private string $clientAddress;
    private float $totalAmount;

    public function __construct(
        string $clientName,
        string $clientAddress,
        float $totalAmount,
        DateTime $createdAt
    ) {
        $this->clientName = $clientName;
        $this->clientAddress = $clientAddress;
        $this->totalAmount = $totalAmount;
        $this->createdAt = $createdAt;
    }

    public static function fromDatabase(
        int $invoiceId,
        string $invoiceNumber,
        string $clientName,
        string $clientAddress,
        float $totalAmount,
        DateTime $createdAt
    ) {
        $instance = new self($clientName, $clientAddress, $totalAmount, $createdAt);
        $instance->invoiceNumber = $invoiceNumber;
        $instance->invoiceId = $invoiceId;
        return $instance;
    }

    public function generateInvoiceNumber()
    {
        $this->invoiceNumber = uniqid('', true);
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

    public function getClientAddress(): string
    {
        return $this->clientAddress;
    }

    public function getTotalAmount(): float
    {
        return $this->totalAmount;
    }

    public function createdAt(): DateTime
    {
        return $this->createdAt;
    }

    public function setInvoiceNumber(string $invoiceNumber)
    {
        $this->invoiceNumber = $invoiceNumber;
    }
}
