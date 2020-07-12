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
use Dotenv\Exception\InvalidPathException;
use Komarev\GitHubProfileViewsCounter\CounterImageRendererService;
use Komarev\GitHubProfileViewsCounter\CounterDatabaseRepository;
use Komarev\GitHubProfileViewsCounter\ErrorImageRendererService;

$basePath = realpath(__DIR__ . '/..');

// Register The Auto Loader
require $basePath . '/vendor/autoload.php';

header('Content-Type: image/svg+xml');
header('Cache-Control: max-age=0, no-cache, no-store, must-revalidate');

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

    $counterBadgePath = $basePath . '/resources/views-count-badge.svg';
    $errorBadgePath = $basePath . '/resources/error-badge.svg';

    $style = $_GET['style'];
    $username = $_GET['username'] ?? '';
    $username = trim($username);

    if ($username === '') {
        $errorImageRenderer = new ErrorImageRendererService($errorBadgePath);

        echo $errorImageRenderer->getImageWithMessage('Invalid query parameter: username');
        exit;
    }

    $dsn = sprintf(
        '%s:host=%s;port=%d;dbname=%s',
        $_ENV['DB_DRIVER'], $_ENV['DB_HOST'], $_ENV['DB_PORT'], $_ENV['DB_NAME']
    );
    $dbConnectionOptions = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ];
    $dbConnection = new PDO($dsn, $_ENV['DB_USER'], $_ENV['DB_PASSWORD'], $dbConnectionOptions);

    $counterRepository = new CounterDatabaseRepository($dbConnection);
    $counterRepository->addViewByUsername($username);

    if ($style === 'pixel') {
        echo '<svg xmlns="http://www.w3.org/2000/svg" width="1" height="1"/>';
        exit;
    }

    $count = $counterRepository->getViewsCountByUsername($username);

    $counterImageRenderer = new CounterImageRendererService($counterBadgePath);
    $counterImage = $counterImageRenderer->getImageWithCount($count);

    echo $counterImage;
    exit;
} catch (InvalidPathException $exception) {
    $errorImageRenderer = new ErrorImageRendererService($errorBadgePath);

    echo $errorImageRenderer->getImageWithMessage('Application environment file is missing');
    exit;
} catch (Exception $exception) {
    $errorImageRenderer = new ErrorImageRendererService($errorBadgePath);

    echo $errorImageRenderer->getImageWithMessage($exception->getMessage());
    exit;
}
