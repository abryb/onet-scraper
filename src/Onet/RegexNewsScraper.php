<?php

namespace App\Onet;

use App\Crawler\Page;
use App\Crawler\PageInterface;

/**
 * @author Błażej Rybarkiewicz <b.rybarkiewicz@gmail.com>
 */
final class RegexNewsScraper implements NewsScraperInterface
{
    /**
     * {@inheritdoc}
     */
    public function getNews(PageInterface $page): ?News
    {
        if (!$this->isNewsPage($page)) {
            return null;
        }
        return new News(
            imageUrl: $this->extractImageUrl($page),
            title: $this->extractTitle($page),
            url: $page->getWebUrl()->toString()
        );
    }

    private function isNewsPage(PageInterface $page): bool
    {
        return preg_match('#class="MainTitle_#', $page->getContent());
    }

    private function extractImageUrl(Page $page): ?string
    {
        // 1. select html for  <img> with class "Image_lead_xxxx"
        preg_match('#<img class="(?:.*?)Image_lead(?>[^>]*)>#', $page->getContent(), $matches);
        $imgHtml = $matches[0] ?? null;

        if (!$imgHtml) {
            // this may be video
            return null;
        }

        // 2. select image src
        preg_match('#src="(?<imageSrc>.*?)"#', $imgHtml, $matches);
        return $matches['imageSrc'];
    }

    private function extractTitle(Page $page): string
    {
        // <h1 class="MainTitle_desktopTextElementNoType__2ALui">Ferrari, Edyta Górniak i wielki krach. Szybka droga ze szczytu w przepaść</h1>
        preg_match('#<h1 class="MainTitle_([^"]*)">(?<title>.*?)<\/h1>#', $page->getContent(), $matches);

        return $matches['title'];
    }
}
