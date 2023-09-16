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

final class BaseCount
{
    private int $baseCount;

    public function __construct(string $baseCount) {
        Assert::digits(
          $baseCount,
          'The base count must be a positive integer'
        );
        $this->baseCount = intval($baseCount);
    }

    public function toInt(): int
    {
        return $this->baseCount;
    }
}
