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
use Komarev\GitHubProfileViewsCounter\CounterFileRepository;
use Komarev\GitHubProfileViewsCounter\ErrorImageRendererService;

$basePath = realpath(__DIR__ . '/..');

// Register The Auto Loader
require $basePath . '/vendor/autoload.php';

header('Content-Type: image/svg+xml');
header('Cache-Control: max-age=0, no-cache, no-store, must-revalidate');

try {
    $dotEnv = Dotenv::createImmutable($basePath);
    $dotEnv->safeLoad();

    $counterBadgePath = $basePath . '/resources/views-count-badge.svg';
    $errorBadgePath = $basePath . '/resources/error-badge.svg';

    if ($_ENV['FILE_STORAGE_PATH'] === null) {
        $storagePath = $basePath . '/storage';
    } else {
        $storagePath = $_ENV['FILE_STORAGE_PATH'];
    }

    $username = $_GET['username'] ?? '';
    $username = trim($username);

    if ($username === '') {
        $errorImageRenderer = new ErrorImageRendererService($errorBadgePath);

        echo $errorImageRenderer->getImageWithMessage('Invalid query parameter: username');
        exit;
    }

    $counterRepository = new CounterFileRepository($storagePath);
    $counterRepository->incrementCountByUsername($username);
    $count = $counterRepository->getCountByUsername($username);

    $counterImageRenderer = new CounterImageRendererService($counterBadgePath);
    $counterImage = $counterImageRenderer->getImageWithCount($count);

    echo $counterImage;
} catch (Exception $exception) {
    $errorImageRenderer = new ErrorImageRendererService($errorBadgePath);

    echo $errorImageRenderer->getImageWithMessage($exception->getMessage());
}
