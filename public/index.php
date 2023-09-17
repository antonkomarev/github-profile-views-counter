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
use Komarev\GitHubProfileViewsCounter\Request;
use Komarev\GitHubProfileViewsCounter\Username;
use Komarev\GitHubProfileViewsCounter\Count;

$appBasePath = realpath(__DIR__ . '/..');

require $appBasePath . '/vendor/autoload.php';

$request = Request::of($_SERVER, $_GET);

$username = trim($request->username());

if ($username === '') {
    header('Location: https://github.com/antonkomarev/github-profile-views-counter');
    exit;
}

$isGitHubUserAgent = strpos($request->userAgent(), 'github-camo') === 0;

$badgeLabel = $request->badgeLabel() ?? 'Profile views';
$badgeMessageBackgroundFill = $request->badgeColor() ?? 'blue';
$baseCount = $request->baseCount() ?? '0';
$badgeStyle = $request->badgeStyle() ?? 'flat';
if (!in_array($badgeStyle, ['flat', 'flat-square', 'plastic', 'for-the-badge', 'pixel'], true)) {
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
        $count = new Count(
            $counterRepository->getViewsCountByUsername($username)
        );
        if ($baseCount !== '0') {
            $count = $count->plus(
                Count::ofString($baseCount),
            );
        }

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
