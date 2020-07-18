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

namespace Contracts\Komarev\GitHubProfileViewsCounter;

use Komarev\GitHubProfileViewsCounter\Username;

interface CounterRepositoryInterface
{
    public function getViewsCountByUsername(Username $username): int;

    public function addViewByUsername(Username $username): void;
}
