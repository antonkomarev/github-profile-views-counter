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

namespace Komarev\GitHubProfileViewsCounter;

final class Request
{
    private string $userAgent;

    private string $username;

    private ?string $badgeLabel;

    private ?string $badgeColor;

    private ?string $badgeStyle;

    public function __construct(
        string $userAgent,
        string $username,
        ?string $badgeLabel,
        ?string $badgeColor,
        ?string $badgeStyle
    ) {
        $this->userAgent = $userAgent;
        $this->username = $username;
        $this->badgeLabel = $badgeLabel;
        $this->badgeColor = $badgeColor;
        $this->badgeStyle = $badgeStyle;
    }

    public static function of(
        array $server,
        array $get
    ): self {
        array_walk_recursive($get, function (&$input) {
            $input = htmlspecialchars($input, ENT_NOQUOTES, 'UTF-8', false);
        });

        return new self(
            $server['HTTP_USER_AGENT'] ?? '',
            $get['username'] ?? '',
            $get['label'] ?? null,
            $get['color'] ?? null,
            $get['style'] ?? null,
        );
    }

    public function userAgent(): string
    {
        return $this->userAgent;
    }

    public function username(): string
    {
        return $this->username;
    }

    public function badgeLabel(): ?string
    {
        return $this->badgeLabel;
    }

    public function badgeColor(): ?string
    {
        return $this->badgeColor;
    }

    public function badgeStyle(): ?string
    {
        return $this->badgeStyle;
    }
}
