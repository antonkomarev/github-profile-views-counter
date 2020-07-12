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
$resourcesPath = $basePath . '/resources';
$storagePath = $basePath . '/storage';

require $basePath . '/vendor/autoload.php';

$username = $_GET['username'] ?? '';
$username = trim($username);

if ($username === '') {
    $counterFileName = 'views-count';
} else {
    $counterFileName = $username . '-views-count';
}

$counterRepository = new CounterFileRepository($storagePath, $counterFileName);
$counterImageRenderer = new CounterImageRendererService($resourcesPath, 'views-count.svg', $counterRepository);

header('Content-Type: image/svg+xml');
header('Cache-Control: max-age=0, no-cache, no-store, must-revalidate');

echo $counterImageRenderer->getImage();
