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

use Dotenv\Dotenv;
use Komarev\GitHubProfileViewsCounter\CounterImageRendererService;
use Komarev\GitHubProfileViewsCounter\CounterPdoRepository;

$basePath = realpath(__DIR__ . '/..');

// Register The Auto Loader
require $basePath . '/vendor/autoload.php';

try {
    $dotEnv = Dotenv::createImmutable($basePath);
    $dotEnv->load();

    $dotEnv->required([
        'DB_DRIVER',
        'DB_HOST',
        'DB_PORT',
        'DB_USER',
        'DB_PASSWORD',
        'DB_NAME',
    ]);

    $counterSourceImagePath = $basePath . '/resources/views-count.svg';

    $username = $_GET['username'] ?? '';
    $username = trim($username);

    $dsn = sprintf(
        '%s:host=%s;port=%d;dbname=%s',
        $_ENV['DB_DRIVER'], $_ENV['DB_HOST'], $_ENV['DB_PORT'], $_ENV['DB_NAME']
    );
    $dbConnectionOptions = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ];
    $dbConnection = new PDO($dsn, $_ENV['DB_USER'], $_ENV['DB_PASSWORD'], $dbConnectionOptions);

    $counterRepository = new CounterPdoRepository($dbConnection);
    $counterImageRenderer = new CounterImageRendererService($counterSourceImagePath);

    $counterRepository->incrementCountByUsername($username);
    $count = $counterRepository->getCountByUsername($username);

    header('Content-Type: image/svg+xml');
    header('Cache-Control: max-age=0, no-cache, no-store, must-revalidate');

    echo $counterImageRenderer->getImageWithCount($count);
} catch (Exception $exception) {
    echo $exception->getMessage();
}
