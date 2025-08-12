<?php

namespace Lcs13761\EredeLaravel\Abstracts;

use Illuminate\Support\Facades\Http;
use InvalidArgumentException;
use Lcs13761\EredeLaravel\EredeService;
use Lcs13761\EredeLaravel\Exception\RedeException;
use Lcs13761\EredeLaravel\Store;
use Lcs13761\EredeLaravel\Transaction;
use RuntimeException;

abstract class AbstractService
{
    public const GET = 'GET';
    public const POST = 'POST';
    public const PUT = 'PUT';

    private ?string $platform = null;

    private ?string $platformVersion = null;

    /**
     * AbstractService constructor.
     *
     * @param Store $store
     */
    public function __construct(protected Store $store)
    {
    }

    /**
     * @param string|null $platform
     * @param string|null $platformVersion
     *
     * @return $this
     */
    public function platform(?string $platform, ?string $platformVersion): static
    {
        $this->platform = $platform;
        $this->platformVersion = $platformVersion;

        return $this;
    }

    /**
     * @return Transaction
     * @throws InvalidArgumentException
     * @throws RuntimeException
     * @throws RedeException
     */
    abstract public function execute(): Transaction;

    /**
     * @param string $body
     * @param string $method
     *
     * @return Transaction
     * @throws RuntimeException
     */
    protected function sendRequest(string $body = '', string $method = 'GET'): Transaction
    {
        $response = $this->makeHttpRequest($method, $body);

        if ($response->failed()) {
            throw new RuntimeException(__('Error obtaining a response from the API'));
        }

        return $this->parseResponse($response->body(), $response->status());
    }

    private function makeHttpRequest(string $method, string $body)
    {
        $url = $this->store->getEnvironment()->getEndpoint($this->getService());

        $headers = $this->prepareHeaders($body);

        return Http::withBasicAuth($this->store->getFiliation(), $this->store->getToken())
            ->withHeaders($headers)
            ->withOptions([
                'curl' => [
                    CURLOPT_SSLVERSION => CURL_SSLVERSION_TLSv1_2,
                    CURLOPT_SSL_VERIFYPEER => true,
                ],
            ])
            ->$method($url, $body ? json_decode($body, true) : []);
    }

    private function prepareHeaders(string $body): array
    {
        $headers = [
            'User-Agent' => $this->getUserAgent(),
            'Accept' => 'application/json',
            'Transaction-Response' => 'brand-return-opened',
        ];

        if ($body !== '') {
            $headers['Content-Type'] = 'application/json; charset=utf8';
        }

        return $headers;
    }

    /**
     * Gets the User-Agent string.
     *
     * @return string
     */
    private function getUserAgent(): string
    {
        $userAgentParts = [
            sprintf(
                EredeService::USER_AGENT,
                phpversion(),
                $this->store->getFiliation(),
                php_uname('s'),
                php_uname('r'),
                php_uname('m')
            ),
        ];

        if (!empty($this->platform) && !empty($this->platformVersion)) {
            $userAgentParts[] = sprintf('%s/%s', $this->platform, $this->platformVersion);
        }

        $curlVersion = curl_version();

        if (is_array($curlVersion)) {
            $userAgentParts[] = sprintf('curl/%s %s', $curlVersion['version'] ?? '', $curlVersion['ssl_version'] ?? '');
        }

        return 'User-Agent: ' . implode(' ', $userAgentParts);
    }

    /**
     * @return string Gets the service that will be used on the request
     */
    abstract protected function getService(): string;

    /**
     * @param string $response Parses the HTTP response from Rede
     * @param int $statusCode The HTTP status code
     *
     * @return Transaction
     */
    abstract protected function parseResponse(string $response, int $statusCode): Transaction;
}
