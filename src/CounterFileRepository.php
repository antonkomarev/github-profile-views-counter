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

final class CounterFileRepository implements
    CounterRepositoryInterface
{
    private const FILE_OPEN_MODE_CREATE = 'c';

    private string $storagePath;

    public function __construct(
        string $storagePath
    ) {
        if (!is_dir($storagePath)) {
            throw new InvalidPathException('Counter storage is not a directory');
        }

        if (!is_writable($storagePath)) {
            throw new InvalidPathException('Counter storage is not writable');
        }

        $this->storagePath = $storagePath;
    }

    public function getViewsCountByUsername(
        Username $username
    ): int {
        $counterFilePath = $this->getCounterFilePath($username);

        return file_exists($counterFilePath)
            ? (int)file_get_contents($counterFilePath)
            : 0;
    }

    public function addViewByUsername(
        Username $username
    ): void {
        file_put_contents(
            $this->getViewsFilePath($username),
            $this->getCurrentFormattedDateTime() . PHP_EOL,
            FILE_APPEND
        );

        try {
            $this->incrementViewsCount($username);
        } catch (\Exception $exception) {
            // Do not throw exception, because counter could be re-calculated
        }
    }

    private function incrementViewsCount(
        Username $username
    ): void {
        $counterFilePath = $this->getCounterFilePath($username);

        /**
         * Need to open the file in "c" mode to avoid truncating
         * before acquiring the lock and before reading.
         */
        $counterFile = fopen($counterFilePath, self::FILE_OPEN_MODE_CREATE);

        if ($counterFile === false) {
            throw new \RuntimeException(
                "Cannot open file `$counterFilePath` for write",
            );
        }

        try {
            /**
             * Acquire an exclusive lock to avoid two requests
             * causing write at the same time.
             */
            $isLockAcquired = flock($counterFile, LOCK_EX);

            if ($isLockAcquired === false) {
                throw new \RuntimeException(
                    "Cannot acquire lock file `$counterFilePath` for write",
                );
            }

            $fileContent = file_get_contents($counterFilePath);

            /**
             * Stop execution to avoid counter reset
             * because of "false" to "0" conversion.
             */
            if ($fileContent === false) {
                throw new \RuntimeException(
                    "Cannot read previous counter value from file `$counterFilePath`",
                );
            }

            $incrementedValue = intval($fileContent) + 1;

            fwrite($counterFile, strval($incrementedValue));
            fflush($counterFile);
        } finally {
            flock($counterFile, LOCK_UN);
            fclose($counterFile);
        }
    }

    private function getViewsFilePath(
        Username $username
    ): string {
        return $this->storagePath . '/' . $username . '-views';
    }

    private function getCounterFilePath(
        Username $username
    ): string {
        return $this->storagePath . '/' . $username . '-views-count';
    }

    private function getCurrentFormattedDateTime(): string
    {
        return (new \DateTimeImmutable('now', new \DateTimeZone('UTC')))
            ->format(DATE_RFC3339_EXTENDED);
    }
}
