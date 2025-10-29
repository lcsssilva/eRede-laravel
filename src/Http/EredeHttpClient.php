<?php

declare(strict_types=1);

namespace Lcsssilva\EredeLaravel\Http;

use Exception;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Lcsssilva\EredeLaravel\Contracts\HttpClientInterface;
use Lcsssilva\EredeLaravel\DTOs\ResponseDTO;
use Lcsssilva\EredeLaravel\DTOs\StoreConfigDTO;
use Lcsssilva\EredeLaravel\Enums\EndpointType;
use Lcsssilva\EredeLaravel\Enums\HttpMethod;
use Lcsssilva\EredeLaravel\Services\ERedeOAuthService;

final readonly class EredeHttpClient implements HttpClientInterface
{
    private ERedeOAuthService $oauthService;

    public function __construct(private StoreConfigDTO $storeConfig)
    {
        $this->oauthService = new ERedeOAuthService($storeConfig);
    }

    /**
     * @throws ConnectionException
     */
    public function request(HttpMethod $method, string $endpoint, array $data = [], array $headers = [], EndpointType $endpointType = EndpointType::AUTHORIZATION): ResponseDTO
    {
        $response = $this->makeRequest($method, $endpoint, $data, $headers, $endpointType);

        return new ResponseDTO(
            statusCode: $response->status(),
            data: $response->json() ?? [],
            success: $response->successful(),
            error: $response->failed() ? $response->body() : null
        );
    }

    /**
     * @throws ConnectionException
     */
    private function makeRequest(HttpMethod $method, string $endpoint, array $data = [], array $headers = [], EndpointType $endpointType = EndpointType::AUTHORIZATION): Response
    {
        $url = $this->buildUrl($endpointType, $endpoint);

        $client = $this->clientInit();

        $client->withHeaders($this->buildHeaders($headers))
            ->withOptions([
                'curl' => [
                    CURLOPT_SSLVERSION => CURL_SSLVERSION_TLSv1_2,
                    CURLOPT_SSL_VERIFYPEER => true,
                ],
            ]);

        return match ($method) {
            HttpMethod::GET => $client->get($url, $data),
            HttpMethod::POST => $client->post($url, $data),
            HttpMethod::PUT => $client->put($url, $data),
            HttpMethod::DELETE => $client->delete($url, $data),
        };
    }

    /**
     * Constrói a URL completa
     *
     * @param EndpointType $endpointType
     * @param string $endpoint
     * @return string
     */
    private function buildUrl(EndpointType $endpointType, string $endpoint): string
    {
        return $this->storeConfig->getApiBaseUrl($endpointType) . '/' . ltrim($endpoint, '/');
    }

    /**
     * @return PendingRequest
     * @throws Exception
     */
    private function clientInit(): PendingRequest
    {
        return $this->storeConfig->getOAuthType() ?
            Http::withToken($this->oauthService->getAccessToken())->timeout($this->storeConfig->getTimeout()) :
            Http::withBasicAuth($this->storeConfig->getClientId(), $this->storeConfig->getClientSecret());
    }

    /**
     * @param array $customHeaders
     * @return array
     */
    private function buildHeaders(array $customHeaders = []): array
    {
        return array_merge([
            'User-Agent' => $this->getUserAgent(),
            'Accept' => 'application/json',
            'Transaction-Response' => 'brand-return-opened',
            'Content-Type' => 'application/json; charset=utf8',
        ], $customHeaders);
    }

    /**
     * @return string
     */
    private function getUserAgent(): string
    {
        return sprintf(
            'eRede-Laravel/%s PHP/%s (%s %s %s)',
            '2.0.0', // versão do pacote
            phpversion(),
            php_uname('s'),
            php_uname('r'),
            php_uname('m')
        );
    }
}