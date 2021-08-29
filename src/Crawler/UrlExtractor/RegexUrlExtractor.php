<?php

namespace App\Crawler\UrlExtractor;

use App\Crawler\PageInterface;
use App\Crawler\UrlExtractorInterface;
use App\Crawler\WebUrl;

/**
 * @author Błażej Rybarkiewicz <b.rybarkiewicz@gmail.com>
 */
class RegexUrlExtractor implements UrlExtractorInterface
{
    /**
     * {@inheritdoc}
     */
    public function extractUrls(PageInterface $page): array
    {
        // array unique compares WebUrls by their string value
        return array_unique(array_merge(
            $this->extractFullUrls($page),
            $this->extractUrlsFromHtmlAttributes($page)
        ));
    }

    /**
     * Find absolute web urls like https://onet.pl/news/3
     * @return WebUrl[]
     */
    private function extractFullUrls(PageInterface $page): array
    {
        $html = $page->getContent();

        preg_match_all("/https?:\/\/(?:www\.)?[-a-zA-Z0-9@:%._\+~#=]{2,256}\.[a-z]{2,4}\b(?:[-a-zA-Z0-9@:%_\+.~#?&\/\/=,]*)/", $html, $matches);

        return array_map([WebUrl::class, 'fromString'], $matches[0]);
    }

    /**
     * Finds urls in html attributes and converts them to absolute urls if needed
     * @return WebUrl[]
     */
    private function extractUrlsFromHtmlAttributes(PageInterface $page) : array
    {
        $urls = [];

        $attributes = implode("|", [
            "href","src"
        ]);

        // attributes with double quotes
        preg_match_all("/(?:$attributes)=\"([^\"]*)\"/", $page->getContent(), $matches);
        $urls = array_merge($urls, $matches[1]);

        // attributes with single quotes
        preg_match_all("/(?:$attributes)='([^']*)'/", $page->getContent(), $matches);
        $urls = array_merge($urls, $matches[1]);


        return array_map(function (string $url) use ($page) {
            return $this->convertAttributeUrlToWebUrl($page, $url);
        }, $urls);
    }

    private function convertAttributeUrlToWebUrl(PageInterface $page, string $url): WebUrl
    {
        // if url is already absolute
        if (!preg_match("#^//?#", $url)) {
            return WebUrl::fromString($url);
        }

        return WebUrl::fromString($page->getWebUrl()->getHttpRelativePathRoot() . preg_replace("#^//?#", "/", $url));
    }
}
