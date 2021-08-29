<?php

namespace App\Crawler;

/**
 * @author Błażej Rybarkiewicz <b.rybarkiewicz@gmail.com>
 */
interface CrawlerInterface
{
    /**
     * This method receives two parameters. The first parameter is the page
     * in which we have discovered this new url and the second parameter is
     * the new url. You should implement this function to specify whether
     * the given url should be crawled or not (based on your crawling logic).
     */
    public function shouldVisit(PageInterface $referringPage, WebUrl $url);

    /**
     * This function is called when a page is fetched and ready to be processed.
     */
    public function visit(PageInterface $page);
}
