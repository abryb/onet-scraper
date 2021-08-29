<?php

namespace App\Crawler;

use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;

/**
 * @author Błażej Rybarkiewicz <b.rybarkiewicz@gmail.com>
 *
 * Helper Value object class for representing Web Url
 */
final class WebUrl
{
    public function __construct(
        private ?string $scheme,
        private ?string $host,
        private ?int $port,
        private ?string $path,
        private ?string $query,
        private ?string $fragment
    ) {
        // replace "//" with "/", it doesn't matter in our context
        $this->path = preg_replace("#//#", "/", $path);
    }


    public static function fromString(string $url): WebUrl
    {
        // if url begins with "//" replace it with "/", parse_url can't handle it
        $url = preg_replace("#^//?#", "/", $url);
        $parsed = parse_url($url);
        if (false === $parsed) {
            throw new \InvalidArgumentException("Url '$url' is not valid url!");
        }

        return new WebUrl(
            $parsed['scheme'] ?? null,
            $parsed['host'] ?? null,
            $parsed['port'] ?? null,
            $parsed['path'] ?? null,
            $parsed['query'] ?? null,
            $parsed['fragment'] ?? null,
        );
    }

    public function toString(): string
    {
        $url = "";
        if ($this->scheme) {
            $url .= $this->scheme . "://";
        }
        $url .= $this->host;
        if ($this->port) {
            $url .= ":".$this->port;
        }
        $url .= $this->path;
        if ($this->query) {
            $url .= '?'. $this->query;
        }
        if ($this->fragment) {
            $url .= "#".$this->fragment;
        }
        return $url;
    }

    #[Pure]
    public function __toString(): string
    {
        return $this->toString();
    }

    #[ArrayShape([
        'scheme' => "null|string",
        'host' => "null|string",
        'port' => "int|null",
        'path' => "null|string",
        'query' => "null|string",
        'fragment' => "null|string"
    ])]
    public function parts(): array
    {
        return [
            'scheme' => $this->scheme,
            'host' => $this->host,
            'port' => $this->port,
            'path' => $this->path,
            'query' => $this->query,
            'fragment' => $this->fragment,
        ];
    }

    public function getHttpRelativePathRoot(): string
    {
        $relativePathRoot = $this->scheme."://".$this->host;

        if ($this->port) {
            $relativePathRoot .= ":".$this->port;
        }

        // relative path /bar from /foo is /bar but from /foo/ it's /foo/bar
        if (str_ends_with($this->path, "/")) {
            $relativePathRoot .= $this->path;
        }

        return $relativePathRoot;
    }

    // getters

    public function getScheme(): ?string
    {
        return $this->scheme;
    }

    public function getHost(): ?string
    {
        return $this->host;
    }

    public function getPort(): ?int
    {
        return $this->port;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function getQuery(): ?string
    {
        return $this->query;
    }

    public function getFragment(): ?string
    {
        return $this->fragment;
    }
}
