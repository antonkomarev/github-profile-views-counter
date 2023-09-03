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

use Dotenv\Exception\InvalidPathException;
use Komarev\GitHubProfileViewsCounter\BadgeImageRendererService;
use Komarev\GitHubProfileViewsCounter\CounterRepositoryFactory;
use Komarev\GitHubProfileViewsCounter\Username;

$appBasePath = realpath(__DIR__ . '/..');

require $appBasePath . '/vendor/autoload.php';

array_walk_recursive($_GET, function (&$input) {
    $input = htmlspecialchars($input, ENT_NOQUOTES, 'UTF-8', false);
});

$username = $_GET['username'] ?? '';
$username = trim($username);

if ($username === '') {
    header('Location: https://github.com/antonkomarev/github-profile-views-counter');
    exit;
}

$httpUserAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
$isGitHubUserAgent = strpos($httpUserAgent, 'github-camo') === 0;

$badgeLabel = $_GET['label'] ?? 'Profile views';
$badgeMessageBackgroundFill = $_GET['color'] ?? 'blue';
$badgeStyle = $_GET['style'] ?? 'flat';
if (!in_array($badgeStyle, ['flat', 'flat-square', 'plastic', 'for-the-badge', 'pixel'])) {
    $badgeStyle = 'flat';
}

header('Content-Type: image/svg+xml');
header('Cache-Control: max-age=0, no-cache, no-store, must-revalidate');

$badgeImageRenderer = new BadgeImageRendererService();

try {
    $counterRepository = (new CounterRepositoryFactory())->create($appBasePath);

    $username = new Username($username);

    if ($isGitHubUserAgent) {
        $counterRepository->addViewByUsername($username);
    }

    if ($badgeStyle === 'pixel') {
        echo $badgeImageRenderer->renderPixel();
    } else {
        $count = $counterRepository->getViewsCountByUsername($username);

        echo $badgeImageRenderer->renderBadgeWithCount(
            $badgeLabel,
            $count,
            $badgeMessageBackgroundFill,
            $badgeStyle,
        );
    }
} catch (InvalidPathException $exception) {
    echo $badgeImageRenderer->renderBadgeWithError(
        $badgeLabel,
        'Application environment file is missing',
        $badgeStyle,
    );
} catch (Exception $exception) {
    echo $badgeImageRenderer->renderBadgeWithError(
        $badgeLabel,
        $exception->getMessage(),
        $badgeStyle,
    );
}
