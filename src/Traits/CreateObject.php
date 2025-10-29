<?php

namespace Lcsssilva\EredeLaravel\Traits;

use DateTime;
use Exception;
use Lcsssilva\EredeLaravel\DTOs\AuthorizationDTO;
use Lcsssilva\EredeLaravel\DTOs\BrandDTO;
use Lcsssilva\EredeLaravel\DTOs\CaptureDTO;
use Lcsssilva\EredeLaravel\DTOs\CryptogramDTO;
use Lcsssilva\EredeLaravel\DTOs\DeviceDTO;
use Lcsssilva\EredeLaravel\DTOs\QrCodeDTO;
use Lcsssilva\EredeLaravel\DTOs\RefundDTO;
use Lcsssilva\EredeLaravel\DTOs\ThreeDSecureDTO;
use Lcsssilva\EredeLaravel\DTOs\UrlDTO;
use ReflectionClass;

trait CreateObject
{
    /**
     * Cria uma nova instância a partir de um objeto ou array
     *
     * @param object|array $data
     * @return BrandDTO|AuthorizationDTO|CaptureDTO|CryptogramDTO|DeviceDTO|QrCodeDTO|RefundDTO|ThreeDSecureDTO|UrlDTO|CreateObject
     * @throws Exception
     */
    public static function fromArray(object|array $data): static
    {
        // Converte object para array se necessário
        $dataArray = is_object($data) ? get_object_vars($data) : $data;

        // Obtém os parâmetros do construtor
        $constructor = new ReflectionClass(self::class);
        $constructorParams = $constructor->getConstructor()?->getParameters() ?? [];

        $args = [];

        // Para cada parâmetro do construtor, busca o valor correspondente nos dados
        foreach ($constructorParams as $param) {
            $paramName = $param->getName();
            $value = $dataArray[$paramName] ?? null;

            // Aplica as mesmas transformações do CreateTrait
            if ($value !== null && self::isDateTimeProperty($paramName)) {
                $value = new DateTime($value);
            }

            $args[$paramName] = $value;
        }

        return new static(...$args);
    }

    /**
     * Método auxiliar para identificar propriedades de data/hora
     */
    private static function isDateTimeProperty(string $propertyName): bool
    {
        return in_array($propertyName, [
            'requestDateTime',
            'dateTime',
            'refundDateTime'
        ]);
    }

}