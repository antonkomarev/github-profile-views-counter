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
use PUGX\Poser\Poser;
use PUGX\Poser\Render\SvgFlatRender;
use PUGX\Poser\Render\SvgFlatSquareRender;
use PUGX\Poser\Render\SvgForTheBadgeRenderer;
use PUGX\Poser\Render\SvgPlasticRender;

final class BadgeImageRendererService
{
    private Poser $poser;

    private const SUFFIXES = ["", "k", "M", "B", "T"];

    public function __construct()
    {
        $this->poser = new Poser([
            new SvgPlasticRender(),
            new SvgFlatRender(),
            new SvgFlatSquareRender(),
            new SvgForTheBadgeRenderer(),
        ]);
    }

    public function renderBadgeWithCount(
        string $label,
        Count $count,
        string $messageBackgroundFill,
        string $badgeStyle
        boolean $withAbbreviation = false
    ): string {
        $message = $this->formatNumber($count->toInt(), $withAbbreviation);

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
        string $badgeStyle
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
        string $badgeStyle
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
        boolean $withAbbreviation = false
    ): string {
        $abbreviation = strval($number)
        if ($withAbbreviation) {
            $abbreviation = $this->formatAbbreviatedNumber($number);
        }else{
            $reversedString = strrev(strval($number));
            $formattedNumber = implode(',', str_split($reversedString, 3));
            $abbreviation= strrev($formattedNumber)
        }
        return $abbreviation;
    }

    public function toAbbreviated(): string
    {
        $count = $this->count;
        $suffixIndex = 0;

        while ($count >= 1000) {
            $count /= 1000;
            $suffixIndex++;
        }

        return round($count, 1) . self::SUFFIXES[$suffixIndex];
    }
}
