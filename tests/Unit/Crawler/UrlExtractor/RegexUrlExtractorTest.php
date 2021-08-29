<?php

namespace App\Tests\Unit\Crawler\UrlExtractor;

use App\Crawler\Page;
use App\Crawler\PageInterface;
use App\Crawler\UrlExtractor\RegexUrlExtractor;
use App\Crawler\WebUrl;
use PHPUnit\Framework\TestCase;

/**
 * @author Błażej Rybarkiewicz <b.rybarkiewicz@gmail.com>
 */
class RegexUrlExtractorTest extends TestCase
{
    public function canExtractUrlsDataProvider(): array
    {
        $url = WebUrl::fromString("http://onet.pl");
        return [
            'extracts from content' => [new Page($url, "http://onet.pl"), ["http://onet.pl"]],
            'extracts from href attribute' => [new Page($url, '<a href="http://onet.pl">Bar<a/>'), ["http://onet.pl"]],
            'converts relative to absolute' => [new Page($url, '<a href="/foo">Foo<a/><a href="//bar">Bar<a/>'), ["http://onet.pl/foo", "http://onet.pl/bar"]],
            'handles root home leading slash' => [new Page($url, '<a href="/">Foo<a/>'), ["http://onet.pl/"]],
            'handles real onet href' => [new Page($url, '<a href="https://www.onet.pl/informacje/onetkrakow/krakow-spor-o-ziemie-na-klinach-w-tle-znajomosc-prezydenta-z-deweloperem/fblvhwy,79cfc278">Foo<a/>'), ["https://www.onet.pl/informacje/onetkrakow/krakow-spor-o-ziemie-na-klinach-w-tle-znajomosc-prezydenta-z-deweloperem/fblvhwy,79cfc278"]],
        ];
    }

    /**
     * @dataProvider canExtractUrlsDataProvider
     */
    public function testCanExtractUrls(PageInterface $page, array $expectedUrls): void
    {
        $extractor = new RegexUrlExtractor();

        $urls = $extractor->extractUrls($page);
        $urls = array_map('strval', $urls);
        // assert arrays contains same elements but order does not matter
        $this->assertEqualsCanonicalizing($expectedUrls, $urls);
    }

    public function testCanExtractFromOnetMainPage()
    {
        $page = file_get_contents(__DIR__."/../../../resources/main_page.html");

        $extractor = new RegexUrlExtractor();

        $foundUrls = $extractor->extractUrls(new Page(WebUrl::fromString("https://onet.pl"), $page));

        $foundUrls = array_map('strval', $foundUrls);

        $this->assertContains("https://www.onet.pl/sport/onetsport/reprezentacja-polski-klopoty-kadrowe-w-druzynie-paulo-sousy/r8c4490,d87b6cc4", $foundUrls);
    }
}
