<?php

namespace Lcs13761\EredeLaravel;

use Lcs13761\EredeLaravel\Traits\CreateTrait;

class QrCode
{
    use CreateTrait  { create as createBase; }

    private ?string $affiliation = null;

    private ?int $amount = null;

    private ?string $reference = null;

    private ?string $status = null;

    private ?string $tid = null;

    private ?string $expirationQrCode = null;

    private ?string $qrCodeImage = null;

    private ?string $qrCodeData = null;

    private ?string $returnCode = null;

    private ?string $returnMessage = null;

    // Sobrescrever o método create da trait para adicionar lógica específica
    public static function create(object $data): object
    {
        // Usar a lógica da trait primeiro
        $object = static::createBase($data);

        $object->expirationQrCode ??= $data->dateTimeExpiration;

        return $object;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function getQrCodeImage(): ?string
    {
        return $this->qrCodeImage;
    }

    public function getQrCodeData(): ?string
    {
        return $this->qrCodeData;
    }

    public function getAffiliation(): ?string
    {
        return $this->affiliation;
    }

    public function getAmount(): ?int
    {
        return $this->amount;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function getTid(): ?string
    {
        return $this->tid;
    }

    public function getReturnCode(): ?string
    {
        return $this->returnCode;
    }

    public function getReturnMessage(): ?string
    {
        return $this->returnMessage;
    }

    public function getExpirationQrCode(): ?string
    {
        return $this->expirationQrCode;
    }
}
