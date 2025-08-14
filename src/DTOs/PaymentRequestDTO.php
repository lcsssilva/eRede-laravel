<?php

namespace Lcs13761\EredeLaravel\DTOs;

use ArrayIterator;
use DateTime;
use Exception;
use Lcs13761\EredeLaravel\Contracts\DTOToArray;

class PaymentRequestDTO implements DTOToArray
{
    public const CREDIT = 'credit';
    public const PIX = 'pix';

    // === Dados básicos da transação ===
    private int|float|null $amount = null;
    private ?string $reference = null;
    private ?string $kind = null;
    private ?int $installments = null;
    private bool|CaptureDTO|null $capture = null;
    private ?bool $subscription = null;
    private ?string $softDescriptor = null;

    // === Informações do cartão ===
    private array $creditCard = [];
    private ?string $tokenCryptogram = null;
    public ?string $last4 = null;
    private ?string $cardBin = null;

    // === Dados de resposta da API ===
    private ?string $tid = null;
    private ?string $nsu = null;
    private ?string $authorizationCode = null;
    private ?string $returnCode = null;
    private ?string $returnMessage = null;

    // === DTOs relacionados ===
    private ?AuthorizationDTO $authorization = null;
    private ?BrandDTO $brand = null;
    private ?CryptogramDTO $cryptogram = null;
    private ?ThreeDSecureDTO $threeDSecure = null;
    private array|QrCodeDTO $qrCode = [];

    // === Dados temporais ===
    private ?DateTime $dateTime = null;
    private ?DateTime $requestDateTime = null;
    private ?DateTime $refundDateTime = null;

    // === Cancelamento/Estorno ===
    private ?string $cancelId = null;
    private ?string $refundId = null;
    private array $refunds = [];

    // === Configurações adicionais ===
    private array $urls = [];
    private array $securityAuthentication = [];
    private array $transactionCredentials = [];
    private ?int $distributorAffiliation = null;
    private ?int $storageCard = null;
    private ?bool $embeddedZeroDollar = null;
    private ?string $email = null;

    // === Tokenização ===
    private ?string $tokenizationId = null;
    private ?string $tokenizationStatus = null;
    private ?int $reason = null;

    public function addUrl(string $url, string $kind = UrlDTO::CALLBACK): static
    {
        $this->urls[] = new UrlDTO($url, $kind);

        return $this;
    }

    /**
     * @param string $cardNumber
     * @param string $cardCvv
     * @param int|string $expirationMonth
     * @param int|string $expirationYear
     * @param string $holderName
     * @return $this
     */
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



    /**
     * @return $this
     */
    public function pix(): static
    {
        $this->kind = self::PIX;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getKind(): ?string
    {
        return $this->kind;
    }

    /**
     * @return string|null
     */
    public function getCancelId(): ?string
    {
        return $this->cancelId;
    }

    /**
     * @return string|null
     */
    public function getLast4(): ?string
    {
        return $this->last4;
    }

    /**
     * @return string|null
     */
    public function getCardBin(): ?string
    {
        return $this->cardBin;
    }

    /**
     * @return string|null
     */
    public function getTid(): ?string
    {
        return $this->tid;
    }

    /**
     * @return string|null
     */
    public function getNsu(): ?string
    {
        return $this->nsu;
    }

    /**
     * @return string|null
     */
    public function getRefundId(): ?string
    {
        return $this->refundId;
    }

    /**
     * @return string|null
     */
    public function getReturnCode(): ?string
    {
        return $this->returnCode;
    }

    /**
     * @return string|null
     */
    public function getReturnMessage(): ?string
    {
        return $this->returnMessage;
    }

    /**
     * @return bool|CaptureDTO|null
     */
    public function getCapture(): bool|CaptureDTO|null
    {
        return $this->capture;
    }

    /**
     * @param bool $capture
     * @return $this
     */
    public function capture(bool $capture = true): static
    {
        $this->capture = $capture;
        return $this;
    }

    /**
     * @return int|float|null
     */
    public function getAmount(): int|float|null
    {
        return $this->amount;
    }

    /**
     * @param int|float $amount
     * @return $this
     */
    public function setAmount(int|float $amount): static
    {
        $this->amount = (int)round($amount * 100);
        return $this;
    }

    /**
     * @return string|null
     */
    public function getReference(): ?string
    {
        return $this->reference;
    }

    /**
     * @param string $reference
     * @return $this
     */
    public function setReference(string $reference): static
    {
        $this->reference = $reference;
        return $this;
    }

    /**
     * @return bool
     */
    public function isCredit(): bool
    {
        return $this->kind === self::CREDIT;
    }

    /**
     * @return bool
     */
    public function isPix(): bool
    {
        return $this->kind === self::PIX;
    }

    /**
     * @return bool
     */
    public function isApproved(): bool
    {
        return $this->authorizationCode ?? ($this->authorization?->isApproved() ?? false);
    }

    /**
     * @return string|null
     */
    public function getAuthorizationCode(): ?string
    {
        return $this->authorizationCode;
    }

    /**
     * @return int|null
     */
    public function getInstallments(): ?int
    {
        return $this->installments;
    }

    /**
     * @param int|null $installments
     * @return $this
     */
    public function setInstallments(?int $installments): PaymentRequestDTO
    {
        $this->installments = $installments;

        return $this;
    }

    /**
     * @return bool
     */
    public function isSubscription(): bool
    {
        return $this->subscription ?? false;
    }

    /**
     * @return bool|null
     */
    public function getSubscription(): ?bool
    {
        return $this->subscription;
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


    /**
     * @return string|null
     */
    public function getSoftDescriptor(): ?string
    {
        return $this->softDescriptor;
    }


    /**
     * @return array
     */
    public function getRefunds(): array
    {
        return $this->refunds;
    }

    /**
     * @return AuthorizationDTO|null
     */
    public function getAuthorization(): ?AuthorizationDTO
    {
        return $this->authorization;
    }

    /**
     * @return BrandDTO|null
     */
    public function getBrand(): ?BrandDTO
    {
        return $this->brand;
    }

    /**
     * @return CryptogramDTO|null
     */
    public function getCryptogram(): ?CryptogramDTO
    {
        return $this->cryptogram;
    }

    /**
     * @return string|null
     */
    public function getTokenCryptogram(): ?string
    {
        return $this->tokenCryptogram;
    }

    /**
     * @param string $tokenCryptogram
     * @return $this
     */
    public function setTokenCryptogram(string $tokenCryptogram): static
    {
        $this->tokenCryptogram = $tokenCryptogram;
        return $this;

    }

    /**
     * @return ThreeDSecureDTO
     */
    public function getThreeDSecure(): ThreeDSecureDTO
    {
        return $this->threeDSecure ?? new ThreeDSecureDTO();
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
        $this->threeDSecure = new ThreeDSecureDTO(
            threeDIndicator: $threeDIndicator,
            DirectoryServerTransactionId: $directoryServerTransactionId,
            userAgent: $userAgent,
            embedded: $mpi,
            Device: $device,
            onFailure: $onFailure
        );

        return $this;
    }

    /**
     * @return array|QrCodeDTO
     */
    public function getQrCode(): array|QrCodeDTO
    {
        return $this->qrCode;
    }

    /**
     * @param $value
     * @return $this
     */
    public function setQrCode($value): static
    {
        $this->qrCode = $value;

        return $this;
    }

    /**
     * @return bool
     */
    public function hasQrCode(): bool
    {
        return !empty($this->qrCode);
    }

    // === Getters para dados temporais ===
    public function getDateTime(): ?DateTime
    {
        return $this->dateTime;
    }

    public function getRequestDateTime(): ?DateTime
    {
        return $this->requestDateTime;
    }

    public function getRefundDateTime(): ?DateTime
    {
        return $this->refundDateTime;
    }

    // === Getters para configurações adicionais ===
    public function getUrls(): array
    {
        return $this->urls;
    }


    /**
     * @return ArrayIterator
     */
    public function getUrlsIterator(): ArrayIterator
    {
        return new ArrayIterator($this->urls);
    }

    /**
     * @return array
     */
    public function getSecurityAuthentication(): array
    {
        return $this->securityAuthentication;
    }

    /**
     * @param int|null $sai
     * @return $this
     */
    public function setSecurityAuthentication(?int $sai): static
    {
        $this->securityAuthentication = ['sai' => $sai];
        return $this;
    }

    /**
     * @return array
     */
    public function getTransactionCredentials(): array
    {
        return $this->transactionCredentials;
    }

    /**
     * @param string|null $credentialId
     * @return $this
     */
    public function setTransactionCredentials(?string $credentialId): static
    {
        $this->transactionCredentials = array_filter(['credentialId' => $credentialId]);
        return $this;
    }

    /**
     * @return int|null
     */
    public function getDistributorAffiliation(): ?int
    {
        return $this->distributorAffiliation;
    }

    /**
     * @return int|null
     */
    public function getStorageCard(): ?int
    {
        return $this->storageCard;
    }

    /**
     * @return bool|null
     */
    public function getEmbeddedZeroDollar(): ?bool
    {
        return $this->embeddedZeroDollar;
    }

    /**
     * @param bool $embeddedZeroDollar
     * @return $this
     */
    public function setEmbeddedZeroDollar(bool $embeddedZeroDollar = true): PaymentRequestDTO
    {
        $this->embeddedZeroDollar = $embeddedZeroDollar;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return $this
     */
    public function setEmail(string $email): PaymentRequestDTO
    {
        $this->email = $email;

        return $this;
    }

    // === Getters para tokenização ===
    public function getTokenizationId(): ?string
    {
        return $this->tokenizationId;
    }

    /**
     * @return string|null
     */
    public function getTokenizationStatus(): ?string
    {
        return $this->tokenizationStatus;
    }

    /**
     * @param string|null $tokenizationStatus
     * @return $this
     */
    public function setTokenizationStatus(?string $tokenizationStatus): PaymentRequestDTO
    {
        $this->tokenizationStatus = $tokenizationStatus;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getReason(): ?int
    {
        return $this->reason;
    }

    /**
     * @param int|null $reason
     * @return $this
     */
    public function setReason(?int $reason): PaymentRequestDTO
    {
        $this->reason = $reason;
        return $this;
    }


    // Serialização otimizada
    public function toArray(): array
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
            'tokenizationStatus' => $this->tokenizationStatus,
            'reason' => $this->reason,
            'qrCode' => $this->qrCode,
            'embeddedZeroDollar' => $this->embeddedZeroDollar,
            'email' => $this->email,
            ...$this->creditCard
        ], fn($value) => !empty($value));
    }

    /**
     * @throws Exception
     */
    public function fromArray(array $properties): static
    {
        $this->creditCard = [];

        foreach ($properties as $property => $value) {
            if ($property === 'links') continue;

            match ($property) {
                'refunds' => $this->refunds = array_map(fn($refundValue) => RefundDTO::fromArray($refundValue), $value),
                'urls' => $this->urls = array_map(fn($refundValue) => RefundDTO::fromArray($refundValue), $value),
                'capture' => $this->capture = CaptureDTO::fromArray($value),
                'authorization' => $this->unserializeAuthorization($value),
                'requestDateTime', 'dateTime', 'refundDateTime' => $this->unserializeDateTime($property, $value),
                'brand' => $this->brand = BrandDTO::fromArray($value),
                'qrCodeResponse' => $this->qrCode = QrCodeDTO::fromArray($value),
                'threeDSecure' => $this->threeDSecure = ThreeDSecureDTO::fromArray($value),
                'cryptogramInfo' => $this->cryptogram = CryptogramDTO::fromArray($value),
                default => $this->{$property} = $value,
            };
        }

        return $this;
    }

    /**
     * @throws Exception
     */
    private function unserializeAuthorization(mixed $value): void
    {
        $this->authorization = AuthorizationDTO::fromArray($value);
        if (isset($value['brand'])) {
            $this->brand = BrandDTO::fromArray($value['brand']);
        }
    }

    /**
     * @throws Exception
     */
    private function unserializeDateTime(string $property, mixed $value): void
    {
        if (in_array($property, ['requestDateTime', 'dateTime', 'refundDateTime'])) $this->{$property} = new DateTime($value);
    }

    public function getCreditCard(): array
    {
        return $this->creditCard;
    }
}
