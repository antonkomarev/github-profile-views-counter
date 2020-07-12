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

$basePath = realpath(__DIR__ . '/..');

// Register The Auto Loader
require $basePath . '/vendor/autoload.php';

try {
    $dotEnv = Dotenv::createImmutable($basePath);
    $dotEnv->safeLoad();

    $counterSourceImagePath = $basePath . '/resources/views-count.svg';

    if ($_ENV['FILE_STORAGE_PATH'] === null) {
        $storagePath = $basePath . '/storage';
    } else {
        $storagePath = $_ENV['FILE_STORAGE_PATH'];
    }

    $username = $_GET['username'] ?? '';
    $username = trim($username);

    $counterRepository = new CounterFileRepository($storagePath);
    $counterRepository->incrementCountByUsername($username);
    $count = $counterRepository->getCountByUsername($username);

    $counterImageRenderer = new CounterImageRendererService($counterSourceImagePath);
    $counterImage = $counterImageRenderer->getImageWithCount($count);

    header('Content-Type: image/svg+xml');
    header('Cache-Control: max-age=0, no-cache, no-store, must-revalidate');

    echo $counterImage;
} catch (Exception $exception) {
    echo $exception->getMessage();
}
