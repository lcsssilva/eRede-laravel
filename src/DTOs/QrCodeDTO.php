<?php

declare(strict_types=1);

namespace Lcs13761\EredeLaravel\DTOs;

use DateTime;
use Exception;
use Lcs13761\EredeLaravel\Contracts\DTOFromArray;
use Lcs13761\EredeLaravel\Contracts\DTOToArray;
use Lcs13761\EredeLaravel\Traits\CreateObject;
use Lcs13761\EredeLaravel\Traits\SerializeTrait;

readonly class QrCodeDTO implements DTOToArray, DTOFromArray
{
    use CreateObject {
        fromArray as createFromTrait;
    }
    use SerializeTrait;


    public function __construct(
        public ?string $affiliation = null,
        public ?int    $amount = null,
        public ?string $reference = null,
        public ?string $status = null,
        public ?string $tid = null,
        public ?string $expirationQrCode = null,
        public ?string $qrCodeImage = null,
        public ?string $qrCodeData = null,
        public ?string $returnCode = null,
        public ?string $returnMessage = null,
    )
    {
    }

    /**
     *
     * @param object|array $data
     * @return self
     * @throws Exception
     */
    public static function fromArray(object|array $data): self
    {
        // Converte object para array se necessário
        $dataArray = is_object($data) ? get_object_vars($data) : $data;

        if (isset($dataArray['dateTimeExpiration']) && !isset($dataArray['expirationQrCode'])) {
            $dataArray['expirationQrCode'] = $dataArray['dateTimeExpiration'];
        }

        return self::createFromTrait($dataArray);
    }

    public static function getDateTimeExpirationForTransaction(): array
    {
        return [
            'dateTimeExpiration' => (new DateTime())->modify('+1 day')->format('Y-m-d\TH:i:s')
        ];
    }
}