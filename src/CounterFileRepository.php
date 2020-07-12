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

use Contracts\Komarev\GitHubProfileViewsCounter\CounterRepositoryInterface;

final class CounterFileRepository implements CounterRepositoryInterface
{
    private string $storagePath;

    public function __construct(string $storagePath)
    {
        $this->storagePath = $storagePath;
    }

    public function getCountByUsername(string $username): int
    {
        $counterFilePath = $this->getCounterFilePath($username);

        return file_exists($counterFilePath) ? (int) file_get_contents($counterFilePath) : 0;
    }

    public function incrementCountByUsername(string $username): void
    {
        $counterFilePath = $this->getCounterFilePath($username);

        file_put_contents($counterFilePath, $this->getCountByUsername($username) + 1);
    }

    private function getCounterFilePath(string $username): string
    {
        if ($username === '') {
            $counterFileName = 'views-count';
        } else {
            $counterFileName = $username . '-views-count';
        }

        return $this->storagePath . '/' . $counterFileName;
    }
}
