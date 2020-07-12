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

use Komarev\GitHubProfileViewsCounter\CounterImageRendererService;
use Komarev\GitHubProfileViewsCounter\CounterFileRepository;

$basePath = realpath(__DIR__ . '/..');
$storagePath = $basePath . '/storage';
$counterSourceImagePath = $basePath . '/resources/views-count.svg';

require $basePath . '/vendor/autoload.php';

$username = $_GET['username'] ?? '';
$username = trim($username);

if ($username === '') {
    $counterFileName = 'views-count';
} else {
    $counterFileName = $username . '-views-count';
}

try {
    $counterRepository = new CounterFileRepository($storagePath, $counterFileName);
    $counterImageRenderer = new CounterImageRendererService($counterSourceImagePath);

    header('Content-Type: image/svg+xml');
    header('Cache-Control: max-age=0, no-cache, no-store, must-revalidate');

    $counterRepository->incrementCount();

    echo $counterImageRenderer->getImageWithCount($counterRepository->getCount());
} catch (Exception $exception) {
    echo $exception->getMessage();
}
