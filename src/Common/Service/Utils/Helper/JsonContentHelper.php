<?php

namespace App\Common\Service\Utils\Helper;

final readonly class JsonContentHelper
{
    /**
     * @param array|string $content
     * @param bool         $strict
     *
     * @return array|string|bool|null
     */
    public static function convertJsonContent(array|string $content, bool $strict): array|string|bool|null
    {
        if (\is_string($content)) {
            $content = trim($content);
            if (!$strict) {
                if ('' === $content || 0 === strcasecmp($content, 'null')) {
                    return null;
                } elseif (0 === strcasecmp($content, 'true')) {
                    return true;
                } elseif (0 === strcasecmp($content, 'false')) {
                    return false;
                } elseif (self::isJson($content)) {
                    $decoded = json_decode($content, true);
                    if (\is_array($decoded)) {
                        return self::convertJsonContent($decoded, $strict);
                    }
                }
            } elseif (self::isJson($content)) {
                $decoded = json_decode($content, true);
                if (\is_array($decoded)) {
                    return self::convertJsonContent($decoded, $strict);
                }
            }

            return $content;
        }
        $arrayContent = [];
        foreach ($content as $parameterName => $parameterValue) {
            $arrayContent[$parameterName] = $parameterValue;
            if (\is_string($parameterValue) || \is_array($parameterValue)) {
                $arrayContent[$parameterName] = self::convertJsonContent($parameterValue, $strict);
            }
        }

        return $arrayContent;
    }

    /**
     * Verifico se una stringa è un JSON valido.
     *
     * @param mixed $string
     * @return bool
     */
    public static function isJson(mixed $string): bool
    {
        if (!\is_string($string)) {
            return false;
        }
        json_decode($string);

        return \JSON_ERROR_NONE == json_last_error();
    }
}