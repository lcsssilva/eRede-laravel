<?php

declare(strict_types=1);

namespace Lcs13761\EredeLaravel\DTOs;

use DateTime;
use DateTimeInterface;
use Exception;
use JsonSerializable;
use ReflectionClass;

readonly class TransactionDTO implements JsonSerializable
{
    public const CREDIT = 'credit';
    public const PIX = 'pix';

    public function __construct(
        public ?string              $tid = null,
        public int|float|null       $amount = null,
        public ?string              $reference = null,
        public ?AuthorizationDTO    $authorization = null,
        public ?string              $authorizationCode = null,
        public ?BrandDTO            $brand = null,
        public ?string              $cancelId = null,
        public bool|CaptureDTO|null $capture = null,
        public ?string              $cardBin = null,
        public ?string              $cardHolderName = null,
        public ?string              $cardNumber = null,
        public ?DateTimeInterface   $dateTime = null,
        public ?int                 $distributorAffiliation = null,
        public int|string|null      $expirationMonth = null,
        public int|string|null      $expirationYear = null,
        public ?int                 $installments = null,
        public ?string              $kind = null,
        public ?string              $last4 = null,
        public ?string              $nsu = null,
        public ?DateTimeInterface   $refundDateTime = null,
        public ?string              $refundId = null,
        public ?array               $refunds = [],
        public ?DateTimeInterface   $requestDateTime = null,
        public array|QrCodeDTO|null $qrCode = [],
        public ?string              $returnCode = null,
        public ?string              $returnMessage = null,
        public ?string              $securityCode = null,
        public ?string              $softDescriptor = null,
        public ?int                 $storageCard = null,
        public ?array               $urls = [],
        public ?array               $securityAuthentication = [],
        public ?array               $transactionCredentials = [],
        public ?ThreeDSecureDTO     $threeDSecure = null,
        public ?bool                $subscription = null,
        public ?string              $tokenCryptogram = null,
        //  Campos extras encontrados na deserialização
        public ?string              $status = null,
        public ?QrCodeDTO           $qrCodeResponse = null,
    )
    {
    }

    /**
     * Cria uma nova instância do TransactionDTO a partir de um objeto ou array
     *
     * @param object|array $data
     * @return self
     * @throws Exception
     */
    public static function create(object|array $data): self
    {
        // Converte object para array se necessário
        $dataArray = is_object($data) ? get_object_vars($data) : $data;

        // Obtém os parâmetros do construtor
        $constructor = new ReflectionClass(self::class);
        $constructorParams = $constructor->getConstructor()?->getParameters() ?? [];

        $args = [];
        $brandFromAuth = null;

        // Para cada parâmetro do construtor, busca e processa o valor
        foreach ($constructorParams as $param) {
            $paramName = $param->getName();
            $value = $dataArray[$paramName] ?? null;

            if ($value === null) {
                $args[$paramName] = null;
                continue;
            }

            // Aplica as transformações específicas baseadas na Transaction original
            $processedValue = match ($paramName) {
                'refund', 'refunds' => self::unserializeRefunds($value),
                'capture' => self::unserializeCapture($value),
                'authorization' => self::unserializeAuthorizationAndBrand($value, $brandFromAuth),
                'qrCodeResponse' => self::unserializeQrCodeResponse($value),
                'qrCode' => self::unserializeQrCode($value),
                'urls' => self::unserializeUrls($value),
                'threeDSecure' => self::unserializeThreeDSecure($value),
                'dateTime', 'requestDateTime', 'refundDateTime' => self::unserializeDateTime($value),
                'brand' => self::unserializeBrand($value),
                default => $value,
            };

            $args[$paramName] = $processedValue;
        }

        if ($brandFromAuth) {
            $args['brand'] = $brandFromAuth;
        }

        return new self(...$args);
    }

    /**
     *  Deserializa refunds (array de RefundDTO)
     * @throws Exception
     */
    private static function unserializeRefunds(mixed $value): array
    {
        if (!is_array($value)) {
            return [];
        }

        return array_map(function ($refundValue) {
            $refundArray = is_object($refundValue) ? get_object_vars($refundValue) : $refundValue;
            return RefundDTO::fromArray($refundArray);
        }, $value);
    }

    /**
     *  Deserializa URLs
     */
    private static function unserializeUrls(mixed $value): array
    {
        if (!is_array($value)) {
            return [];
        }

        return array_map(function ($urlValue) {
            if (is_object($urlValue)) {
                return [
                    'url' => $urlValue->url ?? null,
                    'kind' => $urlValue->kind ?? null
                ];
            }
            return $urlValue;
        }, $value);
    }

    /**
     *  Deserializa ThreeDSecure
     * @throws Exception
     */
    private static function unserializeThreeDSecure(mixed $value): ?ThreeDSecureDTO
    {
        if (empty($value)) {
            return null;
        }

        $dataArray = is_object($value) ? get_object_vars($value) : $value;

        return ThreeDSecureDTO::fromArray($dataArray);
    }

    /**
     * ✅ Deserializa QrCode (campo qrCode)
     */
    private static function unserializeQrCode(mixed $value): array|QrCodeDTO|null
    {
        return $value;
    }

    /**
     * ✅ Deserializa QrCodeResponse
     */
    private static function unserializeQrCodeResponse(mixed $value): ?QrCodeDTO
    {
        if (empty($value)) {
            return null;
        }

        $value  = is_object($value) ? get_object_vars($value) : $value;

        return QrCodeDTO::fromArray($value);
    }


    /**
     * Deserializa dados de authorization E verifica se tem brand
     * @throws Exception
     */
    private static function unserializeAuthorizationAndBrand(mixed $value, ?BrandDTO &$brandFromAuth): ?AuthorizationDTO
    {
        if (empty($value)) {
            return null;
        }


        if (is_array($value)) {
            // Verifica se tem brand no array
            if (isset($value['brand'])) {
                $brandFromAuth = self::unserializeBrand($value['brand']);
            }

            return AuthorizationDTO::fromArray($value);
        }

        return null;
    }

    /**
     * Deserializa dados de brand
     * @throws Exception
     */
    private static function unserializeBrand(mixed $value): ?BrandDTO
    {
        if (empty($value)) {
            return null;
        }

        if (is_array($value)) {
            return BrandDTO::fromArray($value);
        }

        if (is_object($value)) {
            return BrandDTO::fromArray(get_object_vars($value));
        }

        return null;
    }

    /**
     * Deserializa dados de capture
     * @throws Exception
     */
    private static function unserializeCapture(mixed $value): bool|CaptureDTO|null
    {
        if (empty($value)) {
            return null;
        }

        // Se for boolean, mantém como boolean
        if (is_bool($value)) {
            return $value;
        }

        // Se for string "true"/"false", converte para boolean
        if (is_string($value)) {
            return match (strtolower($value)) {
                'true' => true,
                'false' => false,
                default => null
            };
        }

        $value = is_object($value) ? get_object_vars($value) : $value;

        return CaptureDTO::fromArray($value);
    }

    /**
     * Deserializa dados de DateTime
     */
    private static function unserializeDateTime(mixed $value): ?DateTimeInterface
    {
        if (empty($value)) {
            return null;
        }

        if (is_string($value)) {
            try {
                return new DateTime($value);
            } catch (Exception) {
                return null;
            }
        }

        return null;
    }

    /**
     *  Implementa JsonSerializable seguindo o padrão da Transaction original
     */
    public function jsonSerialize(): array
    {
        $capture = match (true) {
            is_bool($this->capture) => $this->capture ? 'true' : 'false',
            $this->capture instanceof CaptureDTO => $this->capture->jsonSerialize(),
            default => null
        };

        $data = [
            'tid' => $this->tid,
            'capture' => $capture,
            'kind' => $this->kind,
            'reference' => $this->reference,
            'threeDSecure' => $this->threeDSecure?->jsonSerialize(),
            'amount' => $this->amount,
            'installments' => $this->installments,
            'cardholderName' => $this->cardHolderName,
            'cardNumber' => $this->cardNumber,
            'expirationMonth' => $this->expirationMonth,
            'subscription' => $this->subscription,
            'expirationYear' => $this->expirationYear,
            'securityCode' => $this->securityCode,
            'softDescriptor' => $this->softDescriptor,
            'distributorAffiliation' => $this->distributorAffiliation,
            'securityAuthentication' => $this->securityAuthentication,
            'storageCard' => $this->storageCard,
            'transactionCredentials' => $this->transactionCredentials,
            'tokenCryptogram' => $this->tokenCryptogram,
            'qrCode' => $this->getQrCode(),
        ];

        //  Remove valores vazios como na Transaction original
        return array_filter($data, fn($value) => !empty($value));
    }

    /**
     *  toArray usa jsonSerialize para compatibilidade
     */
    public function toArray(): array
    {
        return $this->jsonSerialize();
    }

    /**
     *  Métodos de conveniência da Transaction original
     */
    public function getQrCode(): array|QrCodeDTO|null
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
        return $this->authorizationCode !== null || ($this->authorization?->isApproved() ?? false);
    }

    public function hasQrCode(): bool
    {
        return !empty($this->qrCode);
    }

    public function isSubscription(): bool
    {
        return $this->subscription ?? false;
    }

    /**
     * @throws Exception
     */
    public static function fromArray(array $data): self
    {
        return self::create($data);
    }
}