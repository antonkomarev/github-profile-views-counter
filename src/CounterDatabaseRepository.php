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

final class CounterDatabaseRepository implements
    CounterRepositoryInterface
{
    private PDO $connection;

    private string $tableName;

    public function __construct(PDO $connection, string $tableName = null)
    {
        $this->connection = $connection;
        $this->tableName = $tableName !== null ? $tableName : 'github_profile_views';
    }

    public function getViewsCountByUsername(Username $username): int
    {
        $statement = $this->connection->prepare(
            'SELECT COUNT(*)
               FROM :tableName
              WHERE username = :username;'
        );
        $statement->bindParam('tableName', $this->tableName);
        $statement->bindParam('username', $username);
        $statement->execute();

        return (int) $statement->fetchColumn(0);
    }

    public function addViewByUsername(Username $username): void
    {
        $statement = $this->connection->prepare(
            'INSERT INTO :tableName
                         (username, created_at)
                  VALUES (:username, NOW());'
        );
        $statement->bindParam('tableName', $this->tableName);
        $statement->bindParam('username', $username);
        $statement->execute();
    }
}
