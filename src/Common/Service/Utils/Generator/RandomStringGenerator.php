<?php

namespace App\Common\Service\Utils\Generator;

use Symfony\Component\Uid\Uuid;

readonly class RandomStringGenerator
{
    public static function generate(int $length = 255): string
    {
        $string = "";
        while (strlen($string) < $length) {
            $string .= str_replace("-", "", Uuid::v4());
        }

        return substr($string, 0, $length);
    }
}