<?php

declare(strict_types=1);

namespace Lcs13761\EredeLaravel\Contracts;

use Lcs13761\EredeLaravel\Enums\EndpointType;
use Lcs13761\EredeLaravel\Enums\HttpMethod;
use Lcs13761\EredeLaravel\DTOs\ResponseDTO;


interface HttpClientInterface
{
    public function request(
        HttpMethod $method,
        string $endpoint,
        array $data = [],
        array $headers = [],
        EndpointType $endpointType = EndpointType::AUTHORIZATION
    ): ResponseDTO;

}