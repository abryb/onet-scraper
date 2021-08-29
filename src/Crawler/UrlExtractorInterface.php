<?php

namespace App\Crawler;

/**
 * @author Błażej Rybarkiewicz <b.rybarkiewicz@gmail.com>
 *
 * Interface for extracting urls from Page visited by Crawler
 */
interface UrlExtractorInterface
{
    /**
     * @return WebUrl[] array of urls
     */
    public function extractUrls(PageInterface $page): array;
}
