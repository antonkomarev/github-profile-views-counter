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
use Contracts\Komarev\GitHubProfileViewsCounter\Exceptions\InvalidPathException;
use DateTimeImmutable;
use DateTimeZone;

final class CounterFileRepository implements
    CounterRepositoryInterface
{
    private string $storagePath;

    public function __construct(string $storagePath)
    {
        if (!is_dir($storagePath)) {
            throw new InvalidPathException('Counter storage is not a directory');
        }

        if (!is_writable($storagePath)) {
            throw new InvalidPathException('Counter storage is not writable');
        }

        $this->storagePath = $storagePath;
    }

    public function getViewsCountByUsername(Username $username): int
    {
        $counterFilePath = $this->getCounterFilePath($username);

        return file_exists($counterFilePath) ? (int) file_get_contents($counterFilePath) : 0;
    }

    public function addViewByUsername(Username $username): void
    {
        file_put_contents(
            $this->getViewsFilePath($username),
            $this->getCurrentFormattedDateTime() . PHP_EOL,
            FILE_APPEND
        );

        $this->incrementViewsCount($username);
    }

    private function incrementViewsCount(Username $username): void
    {
        $counterFilePath = $this->getCounterFilePath($username);

        file_put_contents($counterFilePath, $this->getViewsCountByUsername($username) + 1);
    }

    private function getViewsFilePath(Username $username): string
    {
        return $this->storagePath . '/' . $username . '-views';
    }

    private function getCounterFilePath(Username $username): string
    {
        return $this->storagePath . '/' . $username . '-views-count';
    }

    private function getCurrentFormattedDateTime(): string
    {
        return (new DateTimeImmutable('now', new DateTimeZone('UTC')))->format(DATE_RFC3339_EXTENDED);
    }
}
