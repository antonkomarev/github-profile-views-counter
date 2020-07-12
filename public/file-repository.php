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
    $dotEnv->load();

    $counterSourceImagePath = $basePath . '/resources/views-count.svg';
    // Define path for counters storage
    $storagePath = $basePath . '/storage';

    $username = $_GET['username'] ?? '';
    $username = trim($username);

    $counterRepository = new CounterFileRepository($storagePath);
    $counterImageRenderer = new CounterImageRendererService($counterSourceImagePath);

    $counterRepository->incrementCountByUsername($username);
    $count = $counterRepository->getCountByUsername($username);

    header('Content-Type: image/svg+xml');
    header('Cache-Control: max-age=0, no-cache, no-store, must-revalidate');

    echo $counterImageRenderer->getImageWithCount($count);
} catch (Exception $exception) {
    echo $exception->getMessage();
}
