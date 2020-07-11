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
        $counterImage = str_replace('%COUNT%', $count, $counterImage);

        return $counterImage;
    }
}
