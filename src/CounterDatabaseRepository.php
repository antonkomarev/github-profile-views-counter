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
use PDO;

final class CounterDatabaseRepository implements CounterRepositoryInterface
{
    private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function getViewsCountByUsername(string $username): int
    {
        $statement = $this->connection->prepare(
            'SELECT COUNT(*)
               FROM github_profile_views
              WHERE username = :username;'
        );
        $statement->bindParam('username', $username);
        $statement->execute();

        return (int) $statement->fetchColumn(0);
    }

    public function addViewByUsername(string $username): void
    {
        $statement = $this->connection->prepare(
            'INSERT INTO github_profile_views
                         (username, created_at)
                  VALUES (:username, NOW());'
        );
        $statement->bindParam('username', $username);
        $statement->execute();
    }
}
