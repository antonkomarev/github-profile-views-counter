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

final class CounterFileRepository
{
    private string $counterPath;

    public function __construct(
        string $storagePath,
        string $counterFileName
    )
    {
        $this->counterPath = $storagePath . '/' . $counterFileName;
    }

    public function getCount(): int
    {
        return file_exists($this->counterPath) ? (int) file_get_contents($this->counterPath) : 0;
    }

    public function incrementCount(): void
    {
        file_put_contents($this->counterPath, $this->getCount() + 1);
    }
}
