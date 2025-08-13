<?php

declare(strict_types=1);

namespace Lcs13761\EredeLaravel\Http;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Lcs13761\EredeLaravel\Contracts\HttpClientInterface;
use Lcs13761\EredeLaravel\DTOs\ResponseDTO;
use Lcs13761\EredeLaravel\DTOs\StoreConfigDTO;
use Lcs13761\EredeLaravel\Enums\HttpMethod;

final readonly class EredeHttpClient implements HttpClientInterface
{
    public function __construct(private StoreConfigDTO $storeConfig)
    {
    }

    /**
     * @throws ConnectionException
     */
    public function request(HttpMethod $method, string $endpoint, array $data = [], array $headers = []): ResponseDTO
    {
        $response = $this->makeRequest($method, $endpoint, $data, $headers);

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
    private function makeRequest(HttpMethod $method, string $endpoint, array $data = [], array $headers = []): Response
    {
        $url = $this->storeConfig->environment->getBaseUrl() . '/' . ltrim($endpoint, '/');

        $client = Http::withBasicAuth(
            $this->storeConfig->filiation,
            $this->storeConfig->token
        )
            ->withHeaders($this->buildHeaders($headers))
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