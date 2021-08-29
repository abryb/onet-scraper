<?php

namespace App\Onet;

use App\Crawler\CrawlerInterface;
use App\Crawler\PageInterface;
use App\Crawler\WebUrl;

/**
 * @author Błażej Rybarkiewicz <b.rybarkiewicz@gmail.com>
 */
final class OnetCrawler implements CrawlerInterface
{
    private array $visitedUrls = [];

    public function __construct(
        private NewsScraperInterface $newsScraper,
        private FoundNewsHandlerInterface $foundNewsHandler
    ) {
    }

    /**
     * {@inheritdoc}
     */
    public function shouldVisit(PageInterface $referringPage, WebUrl $url)
    {
        if (!preg_match("#\.?onet\.pl$#", $url->getHost())) {
            // skip not *.onet.pl domains
            return false;
        }
        if (preg_match("#\.(js|css)#", $url->getPath())) {
            // skip js and css files
            return false;
        }
        if (preg_match("#/ocdn\.eu#", $url->getPath())) {
            // skip ocdn
            return false;
        }

        // do not visit twice
        if (array_key_exists($url->toString(), $this->visitedUrls)) {
            return false;
        }
        $this->visitedUrls[$url->toString()] = true;

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function visit(PageInterface $page)
    {
        $news = $this->newsScraper->getNews($page);

        if ($news) {
            $this->foundNewsHandler->handleFoundNews($news);
        }
    }
}
