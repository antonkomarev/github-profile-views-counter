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

use Webmozart\Assert\Assert;

final class Count
{
    private const MAX_COUNT = PHP_INT_MAX;

    private int $count;

    public function __construct(
        int $count
    ) {
        $this->count = $count;

        Assert::lessThan(
            $count,
            self::MAX_COUNT,
            'The maximum number of views has been reached',
        );
        Assert::greaterThanEq(
            $count,
            0,
            'Number of views cannot be negative',
        );
    }

    public static function ofString(
        string $countStr
    ): self {
        Assert::digits(
            $countStr,
            'The base count must only contain digits',
        );
        $count = intval($countStr);

        return new self($count);
    }

    public function toInt(): int
    {
        return $this->count;
    }

    public function plus(
        self $count
    ): self {
        $sum = $this->toInt() + $count->toInt();

        if (!is_int($sum)) {
            throw new \InvalidArgumentException(
                'The maximum number of views has been reached',
            );
        }

        return new self($sum);
    }
}
