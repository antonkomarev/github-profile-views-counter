<?php

declare(strict_types=1);

namespace Komarev\GitHubProfileViewsCounter;

use XMLReader;

final class SvgFont
{
    private const UNICODE_CODE_POINT_M_LOWERCASE = 109;

    private array $glyphs;

    public int $unitsPerEm;

    private int $glyphSpacingAdvX;

    private int $missingGlyphAdvX;

    public function __construct(
        array $glyphs = [],
        int $unitsPerEm = 0,
        int $glyphSpacingAdvX = 0,
        int $missingGlyphAdvX = 0
    ) {
        $this->glyphs = $glyphs;
        $this->unitsPerEm = $unitsPerEm;
        $this->glyphSpacingAdvX = $glyphSpacingAdvX;
        $this->missingGlyphAdvX = $missingGlyphAdvX;
    }

    /**
     * Takes path to SVG font (local path) and processes its XML
     * to get path representation of every character and additional
     * font parameters.
     */
    public static function fromFile(
        string $filePath
    ): self {
        $xml = new XMLReader();
        $xml->open($filePath);

        $glyphs = [];
        $defaultHorizAdvX = 0;
        $unitsPerEm = 0;
        $glyphSpacingHorizAdvX = 0;
        $missingGlyphHorizAdvX = 0;

        while ($xml->read()) {
            if ($xml->nodeType !== XMLReader::ELEMENT) {
                continue;
            }

            if ($xml->name === 'font') {
                $defaultHorizAdvX = intval($xml->getAttribute('horiz-adv-x'));
            }

            if ($xml->name === 'font-face') {
                $unitsPerEm = intval($xml->getAttribute('units-per-em'));
            }

            if ($xml->name === 'missing-glyph') {
                $missingGlyphHorizAdvX = intval($xml->getAttribute('horiz-adv-x'));
            }

            if ($xml->name === 'glyph') {
                $unicode = $xml->getAttribute('unicode');

                if (isset($unicode)) {
                    $codePoints = TextUnicodeConverter::convertTextToCodePoints($unicode);

                    if (isset($codePoints[0])) {
                        $codePoint = $codePoints[0];

                        $glyphs[$codePoint] = new \stdClass();
                        $glyphHorizAdvX = $xml->getAttribute('horiz-adv-x');

                        if (empty($glyphHorizAdvX)) {
                            $glyphs[$codePoint]->horizAdvX = $defaultHorizAdvX;
                        } else {
                            $glyphs[$codePoint]->horizAdvX = intval($glyphHorizAdvX);
                        }

                        $glyphs[$codePoint]->d = $xml->getAttribute('d');

                        if ($codePoint === self::UNICODE_CODE_POINT_M_LOWERCASE) {
                            $glyphSpacingHorizAdvX = $glyphs[$codePoint]->horizAdvX;
                        }
                    }
                }
            }
        }

        return new self(
            $glyphs,
            $unitsPerEm,
            $glyphSpacingHorizAdvX,
            $missingGlyphHorizAdvX,
        );
    }

    public function computeWidth(
        int $codePoint,
        int $size,
        float $glyphSpacing = 0.0
    ): float {
        $size = $size / $this->unitsPerEm;

        $glyphAdvX = $this->getGlyphAdvX($codePoint);

        $glyphWidth = $glyphAdvX * $size;
        $glyphSpacingWidth = $this->glyphSpacingAdvX * $glyphSpacing * $size;

        return $glyphWidth + $glyphSpacingWidth;
    }

    private function getGlyphAdvX(
        int $codePoint
    ): int {
        return isset($this->glyphs[$codePoint])
            ? $this->glyphs[$codePoint]->horizAdvX
            : $this->missingGlyphAdvX;
    }
}
