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

use PUGX\Poser\Poser;
use PUGX\Poser\Render\SvgFlatRender;
use PUGX\Poser\Render\SvgFlatSquareRender;
use PUGX\Poser\Render\SvgRender;

final class BadgeImageRendererService
{
    private Poser $poser;

    public function __construct()
    {
        $this->poser = new Poser([new SvgRender(), new SvgFlatRender(), new SvgFlatSquareRender()]);
    }

    public function renderBadgeWithCount($badgeStyle, int $count): string
    {
        $message = (string) $count;

        $messageBackgroundFill = '007ec6';

        return $this->renderBadge($badgeStyle, $message, $messageBackgroundFill);
    }

    public function renderBadgeWithError($badgeStyle, string $message): string
    {
        $messageBackgroundFill = 'e05d44';

        return $this->renderBadge($badgeStyle, $message, $messageBackgroundFill);
    }

    private function renderBadge($badgeStyle, string $message, string $messageBackgroundFill): string
    {
        return (string) $this->poser->generate('Page views', $message, $messageBackgroundFill, $badgeStyle);
    }

    public function renderPixel(): string
    {
        return '<svg xmlns="http://www.w3.org/2000/svg" width="1" height="1"/>';
    }
}
