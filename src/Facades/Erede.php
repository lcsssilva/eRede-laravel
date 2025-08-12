<?php


namespace Lcs13761\EredeLaravel\Facades;

use Illuminate\Support\Facades\Facade;
use Lcs13761\EredeLaravel\EredeService;
use Lcs13761\EredeLaravel\Transaction;

/**
 * Facade para o serviço eRede
 *
 * @method static Transaction authorize(Transaction $transaction) Autoriza uma transação (alias para create)
 * @method static Transaction create(Transaction $transaction) Cria uma nova transação
 * @method static EredeService platform(string $platform, string $platformVersion) Define informações da plataforma
 * @method static Transaction cancel(Transaction $transaction) Cancela uma transação
 * @method static Transaction getById(string $tid) Busca transação por TID (alias para get)
 * @method static Transaction get(string $tid) Busca transação por TID
 * @method static Transaction getByReference(string $reference) Busca transação por referência
 * @method static Transaction getRefunds(string $tid) Busca estornos de uma transação
 * @method static Transaction zero(Transaction $transaction) Processa transação de valor zero
 * @method static Transaction capture(Transaction $transaction) Captura uma transação autorizada
 *
 * @see EredeService
 * @package Lcs13761\EredeLaravel\Facades
 */
class Erede extends Facade
{
    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor(): string
    {
        return EredeService::class;
    }
}
