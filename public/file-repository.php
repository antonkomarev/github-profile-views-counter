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

$basePath = realpath(__DIR__ . '/..');

// Register The Auto Loader
require $basePath . '/vendor/autoload.php';

header('Content-Type: image/svg+xml');
header('Cache-Control: max-age=0, no-cache, no-store, must-revalidate');

$badgeImageRenderer = new BadgeImageRendererService();

try {
    $dotEnv = Dotenv::createImmutable($basePath);
    $dotEnv->safeLoad();

    $badgeImagePath = $basePath . '/resources/badge.svg';

    if (!isset($_ENV['FILE_STORAGE_PATH']) || $_ENV['FILE_STORAGE_PATH'] === null) {
        $storagePath = $basePath . '/storage';
    } else {
        $storagePath = $_ENV['FILE_STORAGE_PATH'];
    }

    $style = $_GET['style'] ?? null;
    $username = $_GET['username'] ?? '';
    $username = trim($username);

    if ($username === '') {
        echo $badgeImageRenderer->renderBadgeWithError($badgeImagePath, 'Invalid query parameter: username');
        exit;
    }

    $counterRepository = new CounterFileRepository($storagePath);
    $counterRepository->addViewByUsername($username);

    if ($style === 'pixel') {
        echo '<svg xmlns="http://www.w3.org/2000/svg" width="1" height="1"/>';
        exit;
    }

    $count = $counterRepository->getViewsCountByUsername($username);

    echo $badgeImageRenderer->renderBadgeWithCount($badgeImagePath, $count);
    exit;
} catch (Exception $exception) {
    echo $badgeImageRenderer->renderBadgeWithError($badgeImagePath, $exception->getMessage());
    exit;
}
