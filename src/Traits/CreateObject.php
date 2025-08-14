<?php

namespace Lcs13761\EredeLaravel\Traits;

use DateTime;
use Exception;
use Lcs13761\EredeLaravel\DTOs\AuthorizationDTO;
use Lcs13761\EredeLaravel\DTOs\BrandDTO;
use Lcs13761\EredeLaravel\DTOs\CaptureDTO;
use Lcs13761\EredeLaravel\DTOs\DeviceDTO;
use Lcs13761\EredeLaravel\DTOs\QrCodeDTO;
use Lcs13761\EredeLaravel\DTOs\RefundDTO;
use Lcs13761\EredeLaravel\DTOs\ThreeDSecureDTO;
use ReflectionClass;

trait CreateObject
{
    /**
     * Cria uma nova instância a partir de um objeto ou array
     *
     * @param object|array $data
     * @return BrandDTO|AuthorizationDTO|CaptureDTO|DeviceDTO|QrCodeDTO|RefundDTO|ThreeDSecureDTO|CreateObject
     * @throws Exception
     */
    public static function fromArray(object|array $data): self
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

        return new self(...$args);
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