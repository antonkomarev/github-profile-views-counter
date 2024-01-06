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

    private ?string $baseCount;

    private ?string $withAbbreviation;

    public function __construct(
        string $userAgent,
        string $username,
        ?string $badgeLabel,
        ?string $badgeColor,
        ?string $badgeStyle,
        ?string $baseCount,
        ?string $withAbbreviation
    ) {
        $this->userAgent = $userAgent;
        $this->username = $username;
        $this->badgeLabel = $badgeLabel;
        $this->badgeColor = $badgeColor;
        $this->badgeStyle = $badgeStyle;
        $this->baseCount = $baseCount;
        $this->withAbbreviation = $withAbbreviation;
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
            $get['base'] ?? null,
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

    public function baseCount(): ?string
    {
        return $this->baseCount;
    }

    public function withAbbreviation(): ?string
    {
        return $this->withAbbreviation;
    }
}
