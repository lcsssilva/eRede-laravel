<?php

namespace Lcs13761\EredeLaravel;

use Lcs13761\EredeLaravel\Interfaces\RedeSerializable;
use Lcs13761\EredeLaravel\Interfaces\RedeUnserializable;
use ArrayIterator;
use DateTime;
use Exception;
use InvalidArgumentException;

/**
 * Classe Transaction para processamento de transações de pagamento.
 *
 * Esta classe representa uma transação financeira e fornece métodos para configurar
 * diferentes tipos de pagamento (cartão de crédito e PIX), gerenciar dados da transação
 * e realizar serialização/deserialização dos dados.
 *
 * @throws InvalidArgumentException Quando propriedade inválida é acessada
 * @throws Exception Quando erro na deserialização JSON
 *
 * @property-read ?Authorization $authorization           Dados de autorização da transação
 * @property-read ?string $authorizationCode       Código de autorização
 * @property-read ?Brand $brand                   Marca do cartão utilizado
 * @property-read ?string $cancelId                ID do cancelamento
 * @property-read bool|Capture|null $capture                 Status/dados da captura
 * @property-read ?string $cardBin                 BIN do cartão
 * @property-read ?string $cardHolderName          Nome do portador do cartão
 * @property-read ?string $cardNumber              Número do cartão (mascarado)
 * @property-read ?DateTime $dateTime                Data/hora da transação
 * @property-read ?int $distributorAffiliation  Afiliação do distribuidor
 * @property-read int|string|null $expirationMonth         Mês de expiração do cartão
 * @property-read int|string|null $expirationYear          Ano de expiração do cartão
 * @property-read ?int $installments            Número de parcelas
 * @property-read ?string $kind                    Tipo de transação (credit/pix)
 * @property      ?string $last4                   Últimos 4 dígitos do cartão
 * @property-read ?string $nsu                     Número sequencial único
 * @property-read ?DateTime $refundDateTime          Data/hora do estorno
 * @property-read ?string $refundId                ID do estorno
 * @property-read array $refunds                 Lista de estornos
 * @property-read ?DateTime $requestDateTime         Data/hora da requisição
 * @property-read array|QrCode $qrCode                  Dados do QR Code PIX
 * @property-read ?string $returnCode              Código de retorno
 * @property-read ?string $returnMessage           Mensagem de retorno
 * @property-read ?string $securityCode            Código de segurança do cartão
 * @property-read ?string $softDescriptor          Descrição na fatura
 * @property-read ?int $storageCard             ID do cartão armazenado
 * @property-read ?string $tid                     Transaction ID
 * @property-read array $urls                    URLs de callback
 * @property-read array $securityAuthentication  Dados de autenticação de segurança
 * @property-read array $transactionCredentials  Credenciais da transação
 * @property-read ?int $amount                  Valor em centavos
 * @property-read ?string $reference               Referência da transação
 ***
 * @see Authorization Para detalhes da autorização
 * @see QrCode Para detalhes do QR Code PIX
 * @see Brand Para informações da marca do cartão
 * @see Capture Para dados de captura*
 */
class Transaction implements RedeSerializable, RedeUnserializable
{
    public const CREDIT = 'credit';
    public const PIX = 'pix';

    public function __construct(
        private int|float|null    $amount = null,
        private ?string           $reference = null,
        private ?Authorization    $authorization = null,
        private ?string           $authorizationCode = null,
        private ?Brand            $brand = null,
        private ?string           $cancelId = null,
        private bool|Capture|null $capture = null,
        private ?string           $cardBin = null,
        private ?string           $cardHolderName = null,
        private ?string           $cardNumber = null,
        private ?DateTime         $dateTime = null,
        private ?int              $distributorAffiliation = null,
        private int|string|null   $expirationMonth = null,
        private int|string|null   $expirationYear = null,
        private ?int              $installments = null,
        private ?string           $kind = null,
        public ?string            $last4 = null,
        private ?string           $nsu = null,
        private ?DateTime         $refundDateTime = null,
        private ?string           $refundId = null,
        private array             $refunds = [],
        private ?DateTime         $requestDateTime = null,
        private array|QrCode      $qrCode = [],
        private ?string           $returnCode = null,
        private ?string           $returnMessage = null,
        private ?string           $securityCode = null,
        private ?string           $softDescriptor = null,
        private ?int              $storageCard = null,
        private ?string           $tid = null,
        private array             $urls = [],
        private array             $securityAuthentication = [],
        private array             $transactionCredentials = [],
        private ?ThreeDSecure     $threeDSecure = null,
        private ?bool             $subscription = null,
        private ?string           $tokenCryptogram = null,
    )
    {
        if ($amount !== null) {
            $this->setAmount($amount);
        }
    }

    // Métodos fluent para configuração
    public function addUrl(string $url, string $kind = Url::CALLBACK): static
    {
        $this->urls[] = new Url($url, $kind);
        return $this;
    }

    public function creditCard(string $cardNumber, string $cardCvv, int|string $expirationMonth, int|string $expirationYear, string $holderName): static
    {
        return $this->setCard($cardNumber, $cardCvv, $expirationMonth, $expirationYear, $holderName, self::CREDIT);
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

    /**
     * @return ThreeDSecure
     */
    public function getThreeDSecure(): ThreeDSecure
    {
        return $this->threeDSecure ?? new ThreeDSecure();
    }

    public function capture(bool $capture = true): static
    {
        $this->capture = $capture;
        return $this;
    }

    public function setCard(string $cardNumber, string $securityCode, int|string $expirationMonth, int|string $expirationYear, string $cardHolderName, string $kind): static
    {
        $this->cardNumber = $cardNumber;
        $this->securityCode = $securityCode;
        $this->expirationMonth = $expirationMonth;
        $this->expirationYear = $expirationYear;
        $this->cardHolderName = $cardHolderName;
        $this->kind = $kind;
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

    public function setQrCode(): static
    {
        $this->qrCode = [
            'dateTimeExpiration' => (new DateTime())->modify('+1 day')->format('Y-m-d\TH:i:s')
        ];
        return $this;
    }

    public function setTransactionCredentials(?string $credentialId): static
    {
        $this->transactionCredentials = array_filter(['credentialId' => $credentialId]);
        return $this;
    }

    /**
     * @param Device $device
     * @param string $onFailure
     * @param string $mpi
     * @param string $directoryServerTransactionId
     * @param string|null $userAgent
     * @param int $threeDIndicator
     *
     * @return $this
     */
    public function threeDSecure(
        Device  $device,
        string  $onFailure = ThreeDSecure::DECLINE_ON_FAILURE,
        string  $mpi = ThreeDSecure::MPI_REDE,
        string  $directoryServerTransactionId = '',
        ?string $userAgent = null,
        int     $threeDIndicator = 2
    ): static
    {
        $threeDSecure = new ThreeDSecure($device, $onFailure, $mpi, $userAgent);
        $threeDSecure->setThreeDIndicator($threeDIndicator);
        $threeDSecure->setDirectoryServerTransactionId($directoryServerTransactionId);

        $this->threeDSecure = $threeDSecure;

        return $this;
    }


    public function setSecurityAuthentication(?int $sai): static
    {
        $this->securityAuthentication = ['sai' => $sai];
        return $this;
    }

    // Getters usando __get para reduzir código
    public function __get(string $property): mixed
    {
        if (property_exists($this, $property)) {
            return $this->{$property};
        }
        throw new InvalidArgumentException("Property {$property} does not exist");
    }

    // Setters usando __set para propriedades simples
    public function __set(string $property, mixed $value): void
    {
        $allowedProperties = [
            'cardHolderName', 'cardNumber', 'distributorAffiliation', 'expirationMonth',
            'expirationYear', 'installments', 'kind', 'securityCode', 'softDescriptor',
            'storageCard', 'tid'
        ];

        if (in_array($property, $allowedProperties)) {
            $this->{$property} = $value;
        }
    }

    // Métodos de conveniência
    public function getUrlsIterator(): ArrayIterator
    {
        return new ArrayIterator($this->urls);
    }

    public function getQrCode(): QrCode|array|null
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

    public function setInstallments(?int $installments): Transaction
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
        $this->cardHolderName = null;
        $this->cardNumber = null;
        $this->securityCode = null;
        $this->expirationYear = null;
        $this->expirationMonth = null;
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
        ], fn($value) => !empty($value));
    }

    /**
     * @throws Exception
     */
    public function jsonUnserialize(string $serialized): static
    {
        $properties = json_decode($serialized);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new InvalidArgumentException('JSON: ' . json_last_error_msg());
        }

        $this->resetCreditCardInformation();

        foreach (get_object_vars($properties) as $property => $value) {
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
            default => $this->{$property} = $value,
        };
    }

    private function unserializeRefunds(mixed $value): void
    {
        if (is_array($value)) {
            $this->refunds = array_map(/**
             * @throws Exception
             */ fn($refundValue) => Refund::create($refundValue), $value);
        }
    }

    private function unserializeUrls(mixed $value): void
    {
        if (is_array($value)) {
            $this->urls = array_map(fn($urlValue) => new Url($urlValue->url, $urlValue->kind), $value);
        }
    }

    /**
     * @throws Exception
     */
    private function unserializeCapture(mixed $value): void
    {
        if (is_object($value)) {
            $this->capture = Capture::create($value);
        }
    }

    /**
     * @throws Exception
     */
    private function unserializeAuthorization(mixed $value): void
    {
        if (is_object($value)) {
            $this->authorization = Authorization::create($value);
            if ($value->brand) {
                $this->brand = Brand::create($value->brand);
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
        $this->brand = Brand::create($value);
    }

    /**
     * @throws Exception
     */
    private function unserializeQrCodeResponse(mixed $value): void
    {
        if (is_object($value)) {
            $this->qrCode = QrCode::create($value);
        }
    }
}
