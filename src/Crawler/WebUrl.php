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
        private ?string $query = null,
        private ?string $fragment = null,
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

        /*
         * Handle path only parse_url result, eg
         * parse_url("www.onet.pl") // ['path' => 'wwww.onet.pl']
         */
        if (!array_key_exists('host', $parsed) && isset($parsed['path']) && !str_starts_with($parsed['path'], "/")) {
            $pathParts = explode("/", $parsed['path']);
            if (str_contains($pathParts[0], ".")) {
                // if first part of path is like domain e.g
                $parsed['host'] = array_shift($pathParts);
            }
            $parsed['path'] = !empty($pathParts) ? "/".implode("/", $pathParts) : null;
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

    public function getHttpRelativePathRoot(): string
    {
        $relativePathRoot = $this->scheme."://".$this->host;

        if ($this->port) {
            $relativePathRoot .= ":".$this->port;
        }

        // relative path /bar from /foo is /bar but from /foo/ it's /foo/bar
        if ("/" !== $this->path && str_ends_with($this->path, "/")) {
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
