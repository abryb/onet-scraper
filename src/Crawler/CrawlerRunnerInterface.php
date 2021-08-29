<?php

namespace App\Crawler;

/**
 * @author Błażej Rybarkiewicz <b.rybarkiewicz@gmail.com>
 */
interface CrawlerRunnerInterface
{
    public function runCrawler(CrawlerInterface $crawler, string $startingUrl);
}
