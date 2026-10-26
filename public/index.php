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

/*
 * Security guard: CVE-2026-109618 and CVE-2026-109619 (php2shell) are
 * unauthenticated RCE flaws in PHP itself. Fixed in 8.2.35, 8.3.36,
 * 8.4.27 and 8.5.12. Refuse to serve requests on vulnerable builds.
 */
$patchedPhpVersions = [
    '8.2' => '8.2.35',
    '8.3' => '8.3.36',
    '8.4' => '8.4.27',
    '8.5' => '8.5.12',
];
$phpBranch = PHP_MAJOR_VERSION . '.' . PHP_MINOR_VERSION;
$isPhpPatched = isset($patchedPhpVersions[$phpBranch])
    ? version_compare(PHP_VERSION, $patchedPhpVersions[$phpBranch], '>=')
    : version_compare(PHP_VERSION, '8.6', '>=');

if (!$isPhpPatched) {
    http_response_code(503);
    header('Content-Type: text/plain');
    echo 'Service unavailable: PHP runtime must be upgraded (CVE-2026-109618, CVE-2026-109619).';
    exit;
}

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
$isCountAbbreviated = $request->isCountAbbreviated();
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
            $isCountAbbreviated,
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
