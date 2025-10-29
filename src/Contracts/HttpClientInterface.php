<?php

declare(strict_types=1);

namespace Lcsssilva\EredeLaravel\Contracts;

use Lcsssilva\EredeLaravel\Enums\EndpointType;
use Lcsssilva\EredeLaravel\Enums\HttpMethod;
use Lcsssilva\EredeLaravel\DTOs\ResponseDTO;


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