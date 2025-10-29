<?php

namespace Lcsssilva\EredeLaravel\Services;

use Exception;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Lcsssilva\EredeLaravel\Contracts\StoreConfigInterface;

readonly class ERedeOAuthService
{
    private int $token_buffer_minutes;
    private string $cacheKey;
    private string $expiresAtKey;

    public function __construct(private StoreConfigInterface $storeConfig)
    {
        $envSuffix = $storeConfig->getEnvironment()->value;

        $this->token_buffer_minutes = $storeConfig->getBufferMinuter();

        $this->cacheKey = $storeConfig->getCachePrefixKey() . $envSuffix;

        $this->expiresAtKey = $storeConfig->getCachePrefixExpiration() . $envSuffix;
    }

    /**
     * Obtém o token de acesso (do cache ou renovando)
     *
     * @return string
     * @throws Exception
     */
    public function getAccessToken(): string
    {
        $token = Cache::get($this->cacheKey);

        return $token && $this->isTokenValid() ? Crypt::decryptString($token) : $this->refreshToken();
    }

    /**
     * Verifica se o token em cache ainda é válido
     *
     * @return bool
     */
    private function isTokenValid(): bool
    {
        $expiresAt = Cache::get($this->expiresAtKey);

        return $expiresAt && now()->lessThan($expiresAt);
    }

    /**
     * Renova o token OAuth2
     *
     * @return string
     * @throws Exception
     */
    public function refreshToken(): string
    {
        $response = Http::withBasicAuth(
            $this->storeConfig->getClientId(),
            $this->storeConfig->getClientSecret()
        )
            ->asForm()
            ->timeout($this->storeConfig->getTimeout())
            ->post($this->storeConfig->getOAuthUrl(), ['grant_type' => 'client_credentials']);

        if (!$response->successful()) {
            $this->handleOAuthError($response);
        }

        $data = $response->json();
        $accessToken = $data['access_token'];
        $expiresIn = $data['expires_in'];

        $this->cacheToken($accessToken, $expiresIn);

        return $accessToken;
    }

    /**
     * Trata erros da requisição OAuth2
     *
     * @param Response $response
     * @throws Exception
     */
    private function handleOAuthError(Response $response): void
    {
        $errorBody = $response->json() ?? [];

        $errorMessage = $errorBody['error_description'] ?? $errorBody['error'] ?? $response->body();

        Log::error('eRede OAuth2 Error', [
            'status' => $response->status(),
            'error' => $errorMessage,
            'body' => $errorBody,
            'environment' => $this->storeConfig->getEnvironment()->value,
        ]);

        throw new Exception(
            "OAuth2 authentication failed [{$response->status()}]: $errorMessage"
        );
    }

    /**
     * Armazena o token no cache
     *
     * @param string $accessToken
     * @param int $expiresIn Tempo em segundos
     * @return void
     */
    private function cacheToken(string $accessToken, int $expiresIn): void
    {
        $encryptedToken = Crypt::encrypt($accessToken);

        // Calcula o tempo de expiração com buffer de segurança
        $expirationMinutes = floor($expiresIn / 60) - $this->token_buffer_minutes;

        $expiresAt = now()->addMinutes($expirationMinutes);

        // access token
        Cache::put($this->cacheKey, $encryptedToken, $expiresAt);
        //expired at
        Cache::put($this->expiresAtKey, $expiresAt, $expiresAt);
    }

    /**
     * Limpa o token do cache (útil para forçar renovação)
     *
     * @return void
     */
    public function clearToken(): void
    {
        Cache::forget($this->cacheKey);
        Cache::forget($this->expiresAtKey);
    }

    /**
     * Verifica se o token está válido
     *
     * @return bool
     */
    public function hasValidToken(): bool
    {
        return Cache::has($this->cacheKey) && $this->isTokenValid();
    }
}
