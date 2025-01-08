<?php

namespace Firebase\JWT;

use ArrayAccess;
use DateTime;
use DomainException;
use Exception;
use InvalidArgumentException;
use OpenSSLAsymmetricKey;
use OpenSSLCertificate;
use stdClass;
use UnexpectedValueException;

/**
 * JSON Web Token implementation, based on this spec:
 * https://tools.ietf.org/html/rfc7519
 *
 * PHP version 5
 *
 * @category Authentication
 * @package  Authentication_JWT
 * @author   Neuman Vong <neuman@twilio.com>
 * @author   Anant Narayanan <anant@php.net>
 * @license  http://opensource.org/licenses/BSD-3-Clause 3-clause BSD
 * @link     https://github.com/firebase/php-jwt
 */
class JWT {
    private $key;

    public function __construct(string $key) {
        $this->key = $key;
    }

    public function encode(array $payload): string {
        // Создаем заголовок
        $header = json_encode([
            "alg" => "HS256",
            "typ" => "JWT"
        ]);

        // Кодируем заголовок в формате Base64 URL
        $header = $this->base64URLEncode($header);

        // Кодируем полезную нагрузку в формате JSON и затем в Base64 URL
        $payload = json_encode($payload);
        $payload = $this->base64URLEncode($payload);

        // Создаем подпись с помощью HMAC SHA-256
        $signature = hash_hmac("sha256", $header . "." . $payload, $this->key, true);

        // Кодируем подпись в формате Base64 URL
        $signature = $this->base64URLEncode($signature);

        // Возвращаем JWT токен
        return $header . "." . $payload . "." . $signature;
    }

    public function decode(string $token) {
        // Разделяем токен на части
        list($header, $payload, $signature) = explode('.', $token);

        // Проверяем подпись
        $expectedSignature = hash_hmac("sha256", "$header.$payload", $this->key, true);
        if ($this->base64URLEncode($expectedSignature) !== $signature) {
            throw new Exception('Invalid token signature.');
        }

        // Декодируем полезную нагрузку
        $payload = json_decode($this->base64URLDecode($payload), true);

        // Проверка на истечение токена
        if (isset($payload['exp']) && $payload['exp'] < time()) {
            throw new Exception('Token has expired.');
        }

        return $payload;
    }

    private function base64URLEncode(string $text): string {
        return str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($text));
    }

    private function base64URLDecode(string $text): string {
        return base64_decode(str_replace(['-', '_'], ['+', '/'], $text) . str_repeat('=', (4 - strlen($text) % 4) % 4));
    }
}

