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

require_once $basePath . '/src/CounterImageRendererService.php';
require_once $basePath . '/src/CounterFileRepository.php';

$counterRepository = new CounterFileRepository($storagePath, 'views-count');
$counterImageRenderer = new CounterImageRendererService($resourcesPath, 'views-count.svg', $counterRepository);

header('Content-Type: image/svg+xml');
header('Cache-Control: max-age=0, no-cache, no-store, must-revalidate');

echo $counterImageRenderer->getImage();
