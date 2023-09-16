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
    /**
     * Wrong behavior around extremely large values if set
     * to an extremely large value (near PHP_INT_MAX)
     * Shouldn't exceed PHP_INT_MAX to avoid casting to a float
     */
    private const MAX_COUNT = 1E15;

    private int $count;

    public function __construct(
        int $count, int $baseCount
    ) {
        $countSum = $count + $baseCount;
        Assert::lessThanEq(
          $countSum,
          self::MAX_COUNT,
          'The maximum number of views has been reached'
        );
        /**
         * No need to case countSum to int because
         * if it is a float, it will always be greater than MAX_COUNT
         * provided that the note above MAX_COUNT is followed
         */
        $this->count = $countSum;
    }

    public function toInt(): int
    {
        return $this->count;
    }
}
