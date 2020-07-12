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
use InvalidArgumentException;

final class CounterFileRepository implements CounterRepositoryInterface
{
    private string $storagePath;

    public function __construct(string $storagePath)
    {
        if (!is_dir($storagePath)) {
            throw new InvalidArgumentException('Counter storage is not a directory');
        }

        if (!is_writable($storagePath)) {
            throw new InvalidArgumentException('Counter storage is not writable');
        }

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
        return $this->storagePath . '/' . $username . '-views-count';
    }
}
