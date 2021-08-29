<?php

namespace App\Crawler;

/**
 * @author Błażej Rybarkiewicz <b.rybarkiewicz@gmail.com>
 */
interface PageInterface
{
    public function getWebUrl(): WebUrl;

    public function getContent(): string;
}
