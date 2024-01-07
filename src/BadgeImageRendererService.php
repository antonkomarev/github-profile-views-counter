<?php

/*
 * This file is part of GitHub Profile Views Counter.
 *
 * (c) Anton Komarev <anton@komarev.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Komarev\GitHubProfileViewsCounter;

use PUGX\Poser\Badge;
use PUGX\Poser\Calculator\SvgTextSizeCalculator;
use PUGX\Poser\Poser;
use PUGX\Poser\Render\SvgFlatRender;
use PUGX\Poser\Render\SvgFlatSquareRender;
use PUGX\Poser\Render\SvgForTheBadgeRenderer;
use PUGX\Poser\Render\SvgPlasticRender;

final class BadgeImageRendererService
{
    private Poser $poser;

    private const ABBREVIATIONS = ['', 'K', 'M', 'B', 'T', 'Qa', 'Qi'];

    public function __construct()
    {
        $this->poser = new Poser([
            new SvgPlasticRender(
                textSizeCalculator: new SvgTextSizeCalculator(),
            ),
            new SvgFlatRender(
                textSizeCalculator: new SvgTextSizeCalculator(),
            ),
            new SvgFlatSquareRender(
                textSizeCalculator: new SvgTextSizeCalculator(),
            ),
            new SvgForTheBadgeRenderer(
                textSizeCalculator: new SvgTextSizeCalculator(),
            ),
        ]);
    }

    public function renderBadgeWithCount(
        string $label,
        Count $count,
        string $messageBackgroundFill,
        string $badgeStyle,
        bool $isCountAbbreviated,
    ): string {
        $message = $this->formatNumber($count->toInt(), $isCountAbbreviated);

        return $this->renderBadge(
            $label,
            $message,
            $messageBackgroundFill,
            $badgeStyle,
        );
    }

    public function renderBadgeWithError(
        string $label,
        string $message,
        string $badgeStyle,
    ): string {
        $messageBackgroundFill = 'red';

        return $this->renderBadge(
            $label,
            $message,
            $messageBackgroundFill,
            $badgeStyle,
        );
    }

    public function renderPixel(): string
    {
        return '<svg xmlns="http://www.w3.org/2000/svg" width="1" height="1"/>';
    }

    private function renderBadge(
        string $label,
        string $message,
        string $messageBackgroundFill,
        string $badgeStyle,
    ): string {
        return (string)$this->poser->generate(
            $label,
            $message,
            $messageBackgroundFill,
            $badgeStyle,
            Badge::DEFAULT_FORMAT,
        );
    }

    /**
     * This method required because of native `number_format`
     * method has big integer format limitation.
     */
    private function formatNumber(
        int $number,
        bool $isCountAbbreviated,
    ): string {
        if ($isCountAbbreviated) {
            return $this->formatAbbreviatedNumber($number);
        }

        $reversedString = strrev(strval($number));
        $formattedNumber = implode(',', str_split($reversedString, 3));

        return strrev($formattedNumber);
    }

    public function formatAbbreviatedNumber(
        int $number,
    ): string {
        $abbreviationIndex = 0;

        while ($number >= 1000) {
            $number /= 1000;
            $abbreviationIndex++;
        }

        return round($number, 1) . self::ABBREVIATIONS[$abbreviationIndex];
    }
}
