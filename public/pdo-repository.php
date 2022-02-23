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

    $httpUserAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';

    array_walk_recursive($_GET, function (&$input) {
        $input = htmlspecialchars($input, ENT_NOQUOTES, 'UTF-8', false);
    });

    $badgeLabel = $_GET['label'] ?? 'Profile views';
    $badgeMessageBackgroundFill = $_GET['color'] ?? 'blue';
    $badgeStyle = $_GET['style'] ?? 'flat';
    if (!in_array($badgeStyle, ['flat', 'flat-square', 'plastic', 'for-the-badge'])) {
        $badgeStyle = 'flat';
    }
    $username = $_GET['username'] ?? '';
    $username = trim($username);

    if ($username === '') {
        header('Location: https://github.com/antonkomarev/github-profile-views-counter');
        exit;
//        echo $badgeImageRenderer->renderBadgeWithError($badgeLabel, 'Invalid query parameter: username', $badgeStyle);
//        exit;
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

    echo $badgeImageRenderer->renderBadgeWithCount($badgeLabel, $count, $badgeMessageBackgroundFill, $badgeStyle);
    exit;
} catch (InvalidPathException $exception) {
    echo $badgeImageRenderer->renderBadgeWithError($badgeLabel, 'Application environment file is missing', $badgeStyle);
    exit;
} catch (Exception $exception) {
    echo $badgeImageRenderer->renderBadgeWithError($badgeLabel, $exception->getMessage(), $badgeStyle);
    exit;
}
