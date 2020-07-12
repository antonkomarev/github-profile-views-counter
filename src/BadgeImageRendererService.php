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

use Contracts\Komarev\GitHubProfileViewsCounter\InvalidPathException;

final class BadgeImageRendererService
{
    public function renderBadgeWithCount(string $imagePath, int $count): string
    {
        $message = (string) $count;

        $messageBackgroundFill = '#007ec6';

        return $this->renderBadge($imagePath, $message, $messageBackgroundFill);
    }

    public function renderBadgeWithError(string $imagePath, string $message): string
    {
        $messageBackgroundFill = '#e05d44';

        return $this->renderBadge($imagePath, $message, $messageBackgroundFill);
    }

    private function renderBadge(string $imagePath, string $message, string $messageBackgroundFill): string
    {
        if (!file_exists($imagePath)) {
            throw new InvalidPathException('Badge image not found');
        }

        $image = file_get_contents($imagePath);


        return $this->replaceImagePlaceholders($image, $message, $messageBackgroundFill);
    }

    private function replaceImagePlaceholders(string $image, string $message, string $messageBackgroundFill): string
    {
        $messageLength = strlen($message);

        $minImageWidth = 98;
        $minMessageBackgroundWidth = 17;
        $minMessageTextLength = 70;
        $minMessageTextMarginLeft = 885;

        $label = 'Profile views';
        $imageWidth = $minImageWidth + (8 * $messageLength);
        $messageBackgroundWidth = $minMessageBackgroundWidth + (8 * $messageLength);
        $messageTextLength = $minMessageTextLength + (80 * $messageLength);
        $messageTextMarginLeft = $minMessageTextMarginLeft + (40 * $messageLength);

        $image = str_replace('%IMAGE_WIDTH%', $imageWidth, $image);
        $image = str_replace('%LABEL%', $label, $image);
        $image = str_replace('%MESSAGE%', $message, $image);
        $image = str_replace('%MESSAGE_BACKGROUND_WIDTH%', $messageBackgroundWidth, $image);
        $image = str_replace('%MESSAGE_BACKGROUND_FILL%', $messageBackgroundFill, $image);
        $image = str_replace('%MESSAGE_TEXT_LENGTH%', $messageTextLength, $image);
        $image = str_replace('%MESSAGE_TEXT_MARGIN_LEFT%', $messageTextMarginLeft, $image);

        return $image;
    }
}
