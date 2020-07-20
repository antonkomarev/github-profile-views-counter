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
use Komarev\GitHubProfileViewsCounter\BadgeImageRendererService;
use Komarev\GitHubProfileViewsCounter\CounterFileRepository;
use Komarev\GitHubProfileViewsCounter\Username;

$basePath = realpath(__DIR__ . '/..');

// Register The Auto Loader
require $basePath . '/vendor/autoload.php';

header('Content-Type: image/svg+xml');
header('Cache-Control: max-age=0, no-cache, no-store, must-revalidate');

$badgeImageRenderer = new BadgeImageRendererService();

try {
    $dotEnv = Dotenv::createImmutable($basePath);
    $dotEnv->safeLoad();

    $httpUserAgent = $_SERVER['HTTP_USER_AGENT'];

    if (!isset($_ENV['FILE_STORAGE_PATH']) || $_ENV['FILE_STORAGE_PATH'] === null) {
        $storagePath = $basePath . '/storage';
    } else {
        $storagePath = $_ENV['FILE_STORAGE_PATH'];
    }

    $badgeStyle = $_GET['style'] ?? 'flat';
    if (!in_array($badgeStyle, ['flat', 'flat-square', 'plastic'])) {
        $badgeStyle = 'flat';
    }
    $username = $_GET['username'] ?? '';
    $username = trim($username);

    if ($username === '') {
        echo $badgeImageRenderer->renderBadgeWithError($badgeStyle, 'Invalid query parameter: username');
        exit;
    }

    $username = new Username($username);

    $counterRepository = new CounterFileRepository($storagePath);

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
} catch (Exception $exception) {
    echo $badgeImageRenderer->renderBadgeWithError($badgeStyle, $exception->getMessage());
    exit;
}
