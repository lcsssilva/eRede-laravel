# eRede Laravel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/lcsssilva/erede-laravel.svg?style=flat-square)](https://packagist.org/packages/lcsssilva/erede-laravel)
[![Total Downloads](https://img.shields.io/packagist/dt/lcsssilva/erede-laravel.svg?style=flat-square)](https://packagist.org/packages/lcsssilva/erede-laravel)
[![PHP Version Require](https://img.shields.io/packagist/php-v/lcsssilva/erede-laravel?style=flat-square)](https://packagist.org/packages/lcsssilva/erede-laravel)
[![License](https://img.shields.io/packagist/l/lcsssilva/erede-laravel.svg?style=flat-square)](https://packagist.org/packages/lcsssilva/erede-laravel)

Pacote Laravel para integração com a API de pagamentos da **eRede** (Rede S.A.).

## 🚀 Características

- ✅ **Integração** com a API eRede
- ✅ **Facade Laravel** para fácil utilização
- ✅ **Service Provider** com auto-discovery
- ✅ **DTOs tipados** para melhor experiência no IDE
- ✅ **Suporte a tokenização** de cartões
- ✅ **PIX e Cartão de Crédito** suportados
- ✅ **OAuth 2.0** com cache automático de tokens
- ✅ **Criptografia de credenciais** em cache
- ✅ **PSR-4** autoloading
- ✅ **PHP 8.2+** com recursos modernos
- ✅ **Readonly DTOs** para imutabilidade
- ✅ **Traits reutilizáveis** para serialização

## 📋 Requisitos

- **PHP**: 8.2 ou superior
- **Laravel**: 10.x, 11.x, 12.x
- **Extensões PHP**: 
  - cURL habilitada
  - JSON
  - OpenSSL

## 📦 Instalação

### 1. Instalar o pacote
```
composer require lcsssilva/erede-laravel
```
### 2. Publicar a configuração
```
php artisan vendor:publish --provider="Lcsssilva\EredeLaravel\Providers\EredeServiceProvider"
```
### 3. Configurar variáveis de ambiente

Adicione as seguintes variáveis ao seu arquivo `.env`:
```
env
# Credenciais eRede
EREDE_PV=seu_numero_pv
EREDE_TOKEN=seu_token_aqui

# Ambiente (sandbox(true) ou production(false))
EREDE_SANDBOX=true

# Configurações OAuth (opcional)
EREDE_OAUTH_ENABLED=true
EREDE_TIMEOUT=30
EREDE_BUFFER_MINUTES=2
```
## 🔧 Uso Básico

### Facade

```php
use Lcsssilva\EredeLaravel\Facades\Erede;
use Lcsssilva\EredeLaravel\DTOs\PaymentRequestDTO;

// Criar transação com cartão de crédito
$payment = (new PaymentRequestDTO())
    ->setAmount(100.00) // R$ 100,00
    ->setReference('pedido-123')
    ->creditCard('4242424242424242', '123', 12, 2024, 'João da Silva')
    ->capture(true);

$transaction = Erede::transaction()->createTransaction($payment);

// Criar transação PIX
$pixPayment = (new PaymentRequestDTO())
    ->setAmount(50.00)
    ->setReference('pedido-456')
    ->setQrCode(['dateTimeExpiration' => (new DateTime())->modify('+1 day')->format('Y-m-d\TH:i:s')])
    ->pix();

$pixTransaction = Erede::transaction()->createTransaction($pixPayment);
```

### Tokenização

```php
// Tokenizar um cartão
$tokenData = [
    'cardNumber' => '4242424242424242',
    'cardholderName' => 'João da Silva',
    'expirationMonth' => 12,
    'expirationYear' => 2024,
];

$token = Erede::tokenization()->createToken($tokenData);
```


### Captura e Cancelamento

```php
// Capturar transação (parcial ou total)
$capture = Erede::transaction()->captureTransaction('tid-123', 5000); // R$ 50,00

// Cancelar transação (parcial ou total)
$cancellation = Erede::transaction()->cancelTransaction('tid-123', 2000); // R$ 20,00

// Consultar transação por TID
$transaction = Erede::transaction()->getTransaction('tid-123');

// Consultar por referência
$transaction = Erede::transaction()->getTransactionByReference('pedido-123');
```

## 🤝 Contribuindo

Contribuições são sempre bem-vindas!

## 📄 Licença

Este pacote é open-source e licenciado sob a [MIT License](LICENSE).

## 🔗 Links Úteis

- [Documentação oficial eRede](https://developer.userede.com.br/)
---