<?php

namespace Lcs13761\EredeLaravel\DTOs;

use ArrayIterator;
use DateTime;
use Exception;
use InvalidArgumentException;

class TransactionDTO
{
    public const CREDIT = 'credit';
    public const PIX = 'pix';

    private int|float|null $amount = null;
    private ?string $reference = null;
    private ?AuthorizationDTO $authorization = null;
    private ?string $authorizationCode = null;
    private ?BrandDTO $brand = null;
    private ?string $cancelId = null;
    private bool|CaptureDTO|null $capture = null;
    private ?string $cardBin = null;
    private ?DateTime $dateTime = null;
    private ?int $distributorAffiliation = null;
    private ?int $installments = null;
    private ?string $kind = null;
    public ?string $last4 = null;
    private ?string $nsu = null;
    private ?DateTime $refundDateTime = null;
    private ?string $refundId = null;
    private array $refunds = [];
    private ?DateTime $requestDateTime = null;
    private array|QrCodeDTO $qrCode = [];
    private ?string $returnCode = null;
    private ?string $returnMessage = null;
    private ?string $softDescriptor = null;
    private ?int $storageCard = null;
    private ?string $tid = null;
    private array $urls = [];
    private array $securityAuthentication = [];
    private array $transactionCredentials = [];
    private ?ThreeDSecureDTO $threeDSecure = null;
    private ?bool $subscription = null;
    private ?string $tokenCryptogram = null;

    private array $creditCard = [];


    // Métodos fluent para configuração
//    public function addUrl(string $url, string $kind = Url::CALLBACK): static
//    {
//        $this->urls[] = new Url($url, $kind);
//        return $this;
//    }

    // Getters usando __get para reduzir código
    public function __get(string $property): mixed
    {
        if (property_exists($this, $property)) {
            return $this->{$property};
        }
        throw new InvalidArgumentException("Property {$property} does not exist");
    }

    public function creditCard(string $cardNumber, string $cardCvv, int|string $expirationMonth, int|string $expirationYear, string $holderName): static
    {
        $this->creditCard = [
            'cardholderName' => $holderName,
            'cardNumber' => $cardNumber,
            'expirationMonth' => $expirationMonth,
            'expirationYear' => $expirationYear,
            'securityCode' => $cardCvv,
        ];

        $this->kind = self::CREDIT;

        return $this;
    }

    public function setTokenCryptogram(string $tokenCryptogram): static
    {
        $this->tokenCryptogram = $tokenCryptogram;
        return $this;

    }

    public function pix(): static
    {
        $this->kind = self::PIX;

        return $this;
    }


    public function getTid(): ?string
    {
        return $this->tid;
    }

    public function getCancelId(): ?string
    {
        return $this->cancelId;
    }

    public function getCardBin(): ?string
    {
        return $this->cardBin;
    }

    public function getDateTime(): ?DateTime
    {
        return $this->dateTime;
    }

    public function getNsu(): ?string
    {
        return $this->nsu;
    }

    public function getRefundDateTime(): ?DateTime
    {
        return $this->refundDateTime;
    }

    public function getRefundId(): ?string
    {
        return $this->refundId;
    }

    public function getRequestDateTime(): ?DateTime
    {
        return $this->requestDateTime;
    }

    public function getReturnCode(): ?string
    {
        return $this->returnCode;
    }

    public function getReturnMessage(): ?string
    {
        return $this->returnMessage;
    }

    /**
     * @return ThreeDSecureDTO
     */
    public function getThreeDSecure(): ThreeDSecureDTO
    {
        return $this->threeDSecure ?? new ThreeDSecureDTO();
    }

    public function capture(bool $capture = true): static
    {
        $this->capture = $capture;
        return $this;
    }

    public function setAmount(int|float $amount): static
    {
        $this->amount = (int)round($amount * 100);
        return $this;
    }

    public function setReference(string $reference): static
    {
        $this->reference = $reference;
        return $this;
    }

    public function setQrCode($value): static
    {
        $this->qrCode = $value;
        return $this;
    }

    public function setTransactionCredentials(?string $credentialId): static
    {
        $this->transactionCredentials = array_filter(['credentialId' => $credentialId]);
        return $this;
    }

    /**
     * @param DeviceDTO $device
     * @param string $onFailure
     * @param string $mpi
     * @param string $directoryServerTransactionId
     * @param string|null $userAgent
     * @param int $threeDIndicator
     *
     * @return $this
     */
    public function threeDSecure(
        DeviceDTO $device,
        string    $onFailure = ThreeDSecureDTO::DECLINE_ON_FAILURE,
        string    $mpi = ThreeDSecureDTO::MPI_REDE,
        string    $directoryServerTransactionId = '',
        ?string   $userAgent = null,
        int       $threeDIndicator = 2
    ): static
    {
//        $threeDSecure = new ThreeDSecureDTO($device, $onFailure, $mpi, $userAgent);
//        $threeDSecure->setThreeDIndicator($threeDIndicator);
//        $threeDSecure->setDirectoryServerTransactionId($directoryServerTransactionId);
//
//        $this->threeDSecure = $threeDSecure;

        return $this;
    }


    public function setSecurityAuthentication(?int $sai): static
    {
        $this->securityAuthentication = ['sai' => $sai];
        return $this;
    }

    // Métodos de conveniência
    public function getUrlsIterator(): ArrayIterator
    {
        return new ArrayIterator($this->urls);
    }

    public function getQrCode(): QrCodeDTO|array|null
    {
        return !empty($this->qrCode) ? $this->qrCode : null;
    }

    public function isCredit(): bool
    {
        return $this->kind === self::CREDIT;
    }

    public function isPix(): bool
    {
        return $this->kind === self::PIX;
    }

    public function isApproved(): bool
    {
        return $this->authorizationCode ?? ($this->authorization?->isApproved() ?? false);
    }

    public function setInstallments(?int $installments): TransactionDTO
    {
        $this->installments = $installments;

        return $this;
    }

//    public function isCancelled(): bool
//    {
//        return $this->authorization?->isCancelled() ?? false;
//    }

    public function hasQrCode(): bool
    {
        return !empty($this->qrCode);
    }

    /**
     * @return bool
     */
    public function isSubscription(): bool
    {
        return $this->subscription ?? false;
    }

    /**
     * @param bool $subscription
     *
     * @return $this
     */
    public function setSubscription(bool $subscription): static
    {
        $this->subscription = $subscription;
        return $this;
    }

    public function resetCreditCardInformation(): void
    {
        $this->creditCard = [];
    }

    // Serialização otimizada
    public function jsonSerialize(): array
    {
        $capture = is_bool($this->capture) ? ($this->capture ? 'true' : 'false') : null;

        return array_filter([
            'capture' => $capture,
            'kind' => $this->kind,
            'reference' => $this->reference,
            'threeDSecure' => $this->threeDSecure,
            'amount' => $this->amount,
            'installments' => $this->installments,
            'subscription' => $this->subscription,
            'softDescriptor' => $this->softDescriptor,
            'distributorAffiliation' => $this->distributorAffiliation,
            'securityAuthentication' => $this->securityAuthentication,
            'storageCard' => $this->storageCard,
            'transactionCredentials' => $this->transactionCredentials,
            'tokenCryptogram' => $this->tokenCryptogram,
            'qrCode' => $this->getQrCode(),
            ...$this->creditCard
        ], fn($value) => !empty($value));
    }

    /**
     * @throws Exception
     */
    public function toObject(array $properties): static
    {
        $this->resetCreditCardInformation();

        foreach ($properties as $property => $value) {
            if ($property === 'links') continue;

            $this->unserializeProperty($property, $value);
        }

        return $this;
    }

    /**
     * @throws Exception
     */
    private function unserializeProperty(string $property, mixed $value): void
    {
        match ($property) {
            'refunds' => $this->unserializeRefunds($value),
            'urls' => $this->unserializeUrls($value),
            'capture' => $this->unserializeCapture($value),
            'authorization' => $this->unserializeAuthorization($value),
            'requestDateTime', 'dateTime', 'refundDateTime' => $this->unserializeDateTime($property, $value),
            'brand' => $this->unserializeBrand($value),
            'qrCodeResponse' => $this->unserializeQrCodeResponse($value),
            'threeDSecure' => $this->unserializeThreeDSecure($value),
            default => $this->{$property} = $value,
        };
    }

    private function unserializeThreeDSecure(mixed $value): void
    {
        if (!empty($value)) {
            $dataArray = is_object($value) ? get_object_vars($value) : $value;
            $this->threeDSecure =  ThreeDSecureDTO::fromArray($dataArray);
        }
    }


    private function unserializeRefunds(mixed $value): void
    {
        if (is_array($value)) {
            $this->refunds = array_map(fn($refundValue) => RefundDTO::create($refundValue), $value);
        }
    }

    private function unserializeUrls(mixed $value): void
    {
        if (is_array($value)) {
            $this->urls = array_map(function ($refundValue) {
                $refundArray = is_object($refundValue) ? get_object_vars($refundValue) : $refundValue;
                return RefundDTO::fromArray($refundArray);
            }, $value);
        }
    }

    /**
     * @throws Exception
     */
    private function unserializeCapture(mixed $value): void
    {
        if (is_object($value)) {
            $this->capture = CaptureDTO::create($value);
        }
    }

    /**
     * @throws Exception
     */
    private function unserializeAuthorization(mixed $value): void
    {
        if (is_object($value)) {
            $this->authorization = AuthorizationDTO::create($value);
            if ($value->brand) {
                $this->brand = BrandDTO::create($value->brand);
            }
        }
    }

    /**
     * @throws Exception
     */
    private function unserializeDateTime(string $property, mixed $value): void
    {
        if (in_array($property, ['requestDateTime', 'dateTime', 'refundDateTime'])) {
            $this->{$property} = new DateTime($value);
        }
    }

    /**
     * @throws Exception
     */
    private function unserializeBrand(mixed $value): void
    {
        $this->brand = BrandDTO::create($value);
    }

    /**
     * @throws Exception
     */
    private function unserializeQrCodeResponse(mixed $value): void
    {
        if (is_object($value)) {
            $this->qrCode = QrCodeDTO::create($value);
        }
    }
}
