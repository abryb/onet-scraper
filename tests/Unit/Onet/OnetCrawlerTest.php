<?php

namespace App\Tests\Unit\Onet;

use App\Crawler\Page;
use App\Crawler\PageInterface;
use App\Crawler\WebUrl;
use App\Onet\FoundNewsHandlerInterface;
use App\Onet\NewsScraperInterface;
use App\Onet\OnetCrawler;
use PHPUnit\Framework\TestCase;

/**
 * @author Błażej Rybarkiewicz <b.rybarkiewicz@gmail.com>
 */
class OnetCrawlerTest extends TestCase
{
    public function shouldVisitDataProvider(): array
    {
        return [
            [true, "https://www.onet.pl"],
            [true, "https://www.onet.pl/foo-bar"],
            [true, "https://onet.pl"],
            [false, "https://www.medonet.pl"],
            [false, "https://zapytaj.onet.pl"],
            [false, "https://kropka.onet.pl"],
            [false, "https://sympatia.onet.pl"],
            [false, "https://onet.pl/file.js"],
            [false, "https://onet.pl/file.css"],
            [false, "https://onet.pl/ocdn.eu"],
        ];
    }

    /**
     * @dataProvider shouldVisitDataProvider
     */
    public function testShouldVisitFiltersUrls(bool $expected, string $url): void
    {
        $onetCrawler = new OnetCrawler(
            $this->createMock(NewsScraperInterface::class),
            $this->createMock(FoundNewsHandlerInterface::class)
        );

        $page = new Page(WebUrl::fromString("https://www.onet.pl"), "");

        $this->assertEquals($expected, $onetCrawler->shouldVisit($page, WebUrl::fromString($url)));
    }

    public function testShouldVisitReturnsFalseOnReapetedUrl(): void
    {
        $onetCrawler = new OnetCrawler(
            $this->createMock(NewsScraperInterface::class),
            $this->createMock(FoundNewsHandlerInterface::class)
        );

        $page = new Page(WebUrl::fromString("https://www.onet.pl"), "");
        $url = WebUrl::fromString("https://www.onet.pl");

        $this->assertTrue($onetCrawler->shouldVisit($page, $url));
        $this->assertFalse($onetCrawler->shouldVisit($page, $url));
    }
}
