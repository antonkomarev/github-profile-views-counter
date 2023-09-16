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
        float $count
    ) {
        Assert::lessThan(
          $count,
          self::MAX_COUNT,
          'The maximum number of views has been reached'
        );
        $this->count = intval($count);
        Assert::greaterThanEq(
          $count,
          0,
          "Received a negative number of views"
        );
    }

    public static function ofString(string $countStr): self
    {
      Assert::digits(
        $countStr,
        'The base count must only contain digits'
      );
      $count = floatval($countStr);
      return new self($count);
    }

    public function toInt(): int
    {
        return $this->count;
    }

    public function plus(self $count): self
    {
      return new self($this->toInt() + $count->toInt());
    }
}
