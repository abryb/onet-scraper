<?php

namespace App\Tests\Unit\Onet;

use App\Crawler\Page;
use App\Crawler\WebUrl;
use App\Onet\News;
use App\Onet\RegexNewsScraper;
use PHPUnit\Framework\TestCase;

/**
 * @author Błażej Rybarkiewicz <b.rybarkiewicz@gmail.com>
 */
class NewsScraperTest extends TestCase
{
    public function testCanExtractNews()
    {
        $pageContent = file_get_contents(__DIR__."/../../resources/news_deatails.html");
        $url = WebUrl::fromString('https://www.onet.pl/sport/onetsport/widzew-lodz-szybka-droga-ze-szczytu-w-przepasc-ferrari-i-edyta-gorniak-w-tle/ytmqtct,d87b6cc4');

        $page = new Page($url, $pageContent);

        $newsScrapper = new RegexNewsScraper();

        $news = $newsScrapper->getNews($page);

        $this->assertInstanceOf(News::class, $news);

        $this->assertEquals($url, $news->getUrl());
        $this->assertEquals("Ferrari, Edyta Górniak i wielki krach. Szybka droga ze szczytu w przepaść", $news->getTitle());
        $this->assertEquals("https://ocdn.eu/pulscms-transforms/1/jr0k9kpTURBXy9iNDI3MmFkMTE2NzZlZWIyOWQ1MzU1ZmNjNGI4YzE0NC5qcGeSlQMAMs0He80ENZUCzQOdAMPDgqEwAaExAQ", $news->getImageUrl());
    }
}
