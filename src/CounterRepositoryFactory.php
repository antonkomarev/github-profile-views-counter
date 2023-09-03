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
use Dotenv\Dotenv;
use PDO;

final class CounterRepositoryFactory
{
    public function create(
        string $appBasePath
    ): CounterRepositoryInterface {
        $dotEnv = Dotenv::createImmutable($appBasePath);
        $env = $dotEnv->load();

        $dotEnv->required([
            'REPOSITORY',
        ]);

        $repositoryType = $env['REPOSITORY'];

        switch ($repositoryType) {
            case 'pdo':
                $dotEnv->required([
                    'DB_DRIVER',
                    'DB_HOST',
                    'DB_PORT',
                    'DB_USER',
                    'DB_PASSWORD',
                    'DB_NAME',
                ]);

                $dsn = sprintf(
                    '%s:host=%s;port=%d;dbname=%s',
                    $env['DB_DRIVER'],
                    $env['DB_HOST'],
                    $env['DB_PORT'],
                    $env['DB_NAME'],
                );
                $dbConnection = new PDO(
                    $dsn,
                    $env['DB_USER'],
                    $env['DB_PASSWORD'],
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    ],
                );

                return new CounterPdoRepository($dbConnection);
            case 'file':
                $dotEnv->required([
                    'FILE_STORAGE_PATH',
                ]);

                $storagePath = $env['FILE_STORAGE_PATH'] !== ''
                    ? $env['FILE_STORAGE_PATH']
                    : $appBasePath . '/storage';

                return new CounterFileRepository($storagePath);
            default:
                throw new \Exception(
                    "Unsupported repository `$repositoryType`",
                );
        }
    }
}
