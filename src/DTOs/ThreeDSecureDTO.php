<?php

declare(strict_types=1);

namespace Lcs13761\EredeLaravel\DTOs;

use Exception;
use Lcs13761\EredeLaravel\Contracts\DTOFromArray;
use Lcs13761\EredeLaravel\Contracts\DTOToArray;
use Lcs13761\EredeLaravel\Traits\CreateObject;
use Lcs13761\EredeLaravel\Traits\SerializeTrait;

readonly class ThreeDSecureDTO implements DTOToArray, DTOFromArray
{
    use CreateObject {
        fromArray as createFromTrait;
    }
    use SerializeTrait;

    public const DATA_ONLY = 'DATA_ONLY';
    public const CONTINUE_ON_FAILURE = 'continue';
    public const DECLINE_ON_FAILURE = 'decline';
    public const MPI_REDE = 'mpi_rede';
    public const MPI_THIRD_PARTY = 'mpi_third_party';

    public function __construct(
        public ?string $cavv = null,
        public ?string $eci = null,
        public ?string $url = null,
        public ?string $xid = null,
        public int $threeDIndicator = 2,
        public ?string $DirectoryServerTransactionId = null,
        public ?string $userAgent = null,
        public bool $embedded = true,
        public ?string $returnCode = null,
        public ?string $returnMessage = null,
        public ?string $challengePreference = null,
        public ?DeviceDTO $Device = null,
        public string $onFailure = self::DECLINE_ON_FAILURE
    ) {
    }

    /**
     *
     * @param object|array $data
     * @return self
     * @throws Exception
     */
    public static function fromArray(object|array $data): static
    {
        // Converte object para array se necessário
        $dataArray = is_object($data) ? get_object_vars($data) : $data;

        if (isset($dataArray['Device']))
            $dataArray['Device'] = DeviceDTO::fromArray($dataArray['Device']);

        return self::createFromTrait($dataArray);
    }
}