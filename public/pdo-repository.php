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
use Komarev\GitHubProfileViewsCounter\BadgeImageRendererService;
use Komarev\GitHubProfileViewsCounter\CounterPdoRepository;
use Komarev\GitHubProfileViewsCounter\Username;

$basePath = realpath(__DIR__ . '/..');

// Register The Auto Loader
require $basePath . '/vendor/autoload.php';

header('Content-Type: image/svg+xml');
header('Cache-Control: max-age=0, no-cache, no-store, must-revalidate');

$badgeImageRenderer = new BadgeImageRendererService();

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

    $httpUserAgent = $_SERVER['HTTP_USER_AGENT'];

    $badgeStyle = $_GET['style'] ?? 'flat-square';
    if (!in_array($badgeStyle, ['flat-square', 'flat', 'plastic'])) {
        $badgeStyle = 'flat-square';
    }
    $username = $_GET['username'] ?? '';
    $username = trim($username);

    if ($username === '') {
        echo $badgeImageRenderer->renderBadgeWithError($badgeStyle, 'Invalid query parameter: username');
        exit;
    }

    $username = new Username($username);

    $dsn = sprintf(
        '%s:host=%s;port=%d;dbname=%s',
        $_ENV['DB_DRIVER'], $_ENV['DB_HOST'], $_ENV['DB_PORT'], $_ENV['DB_NAME']
    );
    $dbConnectionOptions = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ];
    $dbConnection = new PDO($dsn, $_ENV['DB_USER'], $_ENV['DB_PASSWORD'], $dbConnectionOptions);

    $counterRepository = new CounterPdoRepository($dbConnection);

    if (strpos($httpUserAgent, 'github-camo') === 0) {
        $counterRepository->addViewByUsername($username);
    }

    if ($badgeStyle === 'pixel') {
        echo $badgeImageRenderer->renderPixel();
        exit;
    }

    $count = $counterRepository->getViewsCountByUsername($username);

    echo $badgeImageRenderer->renderBadgeWithCount($badgeStyle, $count);
    exit;
} catch (InvalidPathException $exception) {
    echo $badgeImageRenderer->renderBadgeWithError($badgeStyle, 'Application environment file is missing');
    exit;
} catch (Exception $exception) {
    echo $badgeImageRenderer->renderBadgeWithError($badgeStyle, $exception->getMessage());
    exit;
}
