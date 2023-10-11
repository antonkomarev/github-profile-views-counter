<?php

declare(strict_types=1);

namespace Komarev\GitHubProfileViewsCounter;

use PUGX\Poser\Calculator\TextSizeCalculatorInterface;

class SvgTextSizeCalculator implements TextSizeCalculatorInterface
{
    private const SHIELD_PADDING = 12;

    private const UNICODE_CODE_POINT_LINE_FEED = 10;

    /**
     * Calculate the width of the text box.
     */
    public function calculateWidth(string $text, int $size = self::TEXT_SIZE): float
    {
        $font = SvgFont::fromFile(__DIR__ . '/DejaVuSans.svg');

        $textUnicode = TextUnicodeConverter::convertTextToCodePoints($text);

        $width = 0;
        $lineWidth = 0;

        foreach ($textUnicode as $unicodeCodePoint) {
            if ($unicodeCodePoint === self::UNICODE_CODE_POINT_LINE_FEED) {
                $width = max($width, $lineWidth);
                $lineWidth = 0;
                continue;
            }

            $lineWidth += $font->computeWidth($unicodeCodePoint, $size);
        }

        $width = max($width, $lineWidth);

        return \round($width + self::SHIELD_PADDING, 1);
    }
}
