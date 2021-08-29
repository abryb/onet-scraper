<?php

namespace App\Onet;

use App\Crawler\PageInterface;

/**
 * @author Błażej Rybarkiewicz <b.rybarkiewicz@gmail.com>
 */
interface NewsScraperInterface
{
    public function getNews(PageInterface $page): ?News;
}
