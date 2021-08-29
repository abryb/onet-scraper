<?php

namespace App\Onet;

/**
 * @author Błażej Rybarkiewicz <b.rybarkiewicz@gmail.com>
 */
class News
{
    public function __construct(
        private ?string $imageUrl,
        private string $title,
        private string $url
    ) {
    }

    public function getImageUrl(): ?string
    {
        return $this->imageUrl;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getUrl(): string
    {
        return $this->url;
    }
}
