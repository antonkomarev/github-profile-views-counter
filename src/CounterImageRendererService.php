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

final class CounterImageRendererService
{
    private string $sourceImagePath;

    private CounterFileRepository $counterRepository;

    public function __construct(
        string $resourcesPath,
        string $sourceImageFileName,
        CounterFileRepository $counterRepository
    )
    {
        $this->sourceImagePath = $resourcesPath . '/' . $sourceImageFileName;
        $this->counterRepository = $counterRepository;
    }

    public function getImage(): string
    {
        $this->counterRepository->incrementCount();
        $count = $this->counterRepository->getCount();

        $counterImage = file_get_contents($this->sourceImagePath);

        return $this->replaceImagePlaceholders($counterImage, $count);
    }

    private function replaceImagePlaceholders(string $counterImage, int $count): string
    {
        $countLength = $this->countIntegerLength($count);

        $minImageWidth = 98;
        $minCounterBackgroundWidth = 17;
        $minCounterTextLength = 70;
        $minCounterTextMarginLeft = 885;

        $imageWidth = $minImageWidth + (8 * $countLength);
        $counterBackgroundWidth = $minCounterBackgroundWidth + (8 * $countLength);
        $counterTextLength = $minCounterTextLength + (80 * $countLength);
        $counterTextMarginLeft = $minCounterTextMarginLeft + (40 * $countLength);

        $counterImage = str_replace('%COUNT%', $count, $counterImage);
        $counterImage = str_replace('%IMAGE_WIDTH%', $imageWidth, $counterImage);
        $counterImage = str_replace('%COUNTER_BACKGROUND_WIDTH%', $counterBackgroundWidth, $counterImage);
        $counterImage = str_replace('%COUNTER_TEXT_LENGTH%', $counterTextLength, $counterImage);
        $counterImage = str_replace('%COUNTER_TEXT_MARGIN_LEFT%', $counterTextMarginLeft, $counterImage);

        return $counterImage;
    }

    private function countIntegerLength(int $integer): int
    {
        return strlen((string) $integer);
    }
}
