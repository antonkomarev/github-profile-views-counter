<?php

declare(strict_types=1);

namespace Komarev\GitHubProfileViewsCounter;

final class TextUnicodeConverter
{
    /**
     * Converts UTF-8 encoded string and returns unicode number for every character.
     */
    public static function convertTextToCodePoints(
        string $string
    ): array {
        $unicode = [];
        $values = [];
        $lookingFor = 1;

        for ($i = 0, $iMax = strlen($string); $i < $iMax; $i++) {
            $thisValue = ord($string[$i]);
            if ($thisValue < 128) {
                $unicode[] = $thisValue;
            } else {
                if (count($values) === 0) {
                    $lookingFor = ($thisValue < 224) ? 2 : 3;
                }
                $values[] = $thisValue;
                if (count($values) === $lookingFor) {
                    $number = ($lookingFor === 3) ?
                        (($values[0] % 16) * 4096) + (($values[1] % 64) * 64) + ($values[2] % 64) :
                        (($values[0] % 32) * 64) + ($values[1] % 64);

                    $unicode[] = $number;
                    $values = [];
                    $lookingFor = 1;
                }
            }
        }

        return $unicode;
    }
}
