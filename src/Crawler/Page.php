<?php

namespace App\Crawler;

/**
 * @author Błażej Rybarkiewicz <b.rybarkiewicz@gmail.com>
 */
final class Page implements PageInterface
{
    public function __construct(
        private WebUrl $webUrl,
        private string $content
    ) {
    }

    public function getWebUrl(): WebUrl
    {
        return $this->webUrl;
    }

    public function getContent(): string
    {
        return $this->content;
    }
}
