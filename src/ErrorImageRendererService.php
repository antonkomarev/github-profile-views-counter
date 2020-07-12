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

final class ErrorImageRendererService
{
    private string $errorBadgePath;

    public function __construct(string $errorBadgePath)
    {
        if (!file_exists($errorBadgePath)) {
            throw new InvalidPathException('Error badge image not found');
        }

        $this->errorBadgePath = $errorBadgePath;
    }

    public function getImageWithMessage(string $message): string
    {
        $errorImage = file_get_contents($this->errorBadgePath);

        return $this->replaceImagePlaceholders($errorImage, $message);
    }

    private function replaceImagePlaceholders(string $errorImage, string $message): string
    {
        $messageLength = strlen($message);

        $minImageWidth = 98;
        $minCounterBackgroundWidth = 17;
        $minCounterTextLength = 70;
        $minCounterTextMarginLeft = 480;

        $imageWidth = $minImageWidth + (8 * $messageLength);
        $counterBackgroundWidth = $minCounterBackgroundWidth + (8 * $messageLength);
        $counterTextLength = $minCounterTextLength + (80 * $messageLength);
        $counterTextMarginLeft = $minCounterTextMarginLeft + (40 * $messageLength);

        $errorImage = str_replace('%IMAGE_WIDTH%', $imageWidth, $errorImage);
        $errorImage = str_replace('%MESSAGE%', $message, $errorImage);
        $errorImage = str_replace('%MESSAGE_BACKGROUND_WIDTH%', $counterBackgroundWidth, $errorImage);
        $errorImage = str_replace('%MESSAGE_TEXT_LENGTH%', $counterTextLength, $errorImage);
        $errorImage = str_replace('%MESSAGE_TEXT_MARGIN_LEFT%', $counterTextMarginLeft, $errorImage);

        return $errorImage;
    }
}
