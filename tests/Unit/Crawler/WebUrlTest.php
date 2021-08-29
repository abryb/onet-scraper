<?php

namespace App\Tests\Unit\Crawler;

use App\Crawler\WebUrl;
use PHPUnit\Framework\TestCase;

/**
 * @author Błażej Rybarkiewicz <b.rybarkiewicz@gmail.com>
 */
class WebUrlTest extends TestCase
{
    public function testCreatesValidWebUrl()
    {
        $webUrl = WebUrl::fromString("https://www.onet.pl/informacje/onetkrakow/krakow-spor-o-ziemie-na-klinach-w-tle-znajomosc-prezydenta-z-deweloperem/fblvhwy,79cfc278");

        $this->assertEquals("https", $webUrl->getScheme());
        $this->assertEquals("www.onet.pl", $webUrl->getHost());
        $this->assertEquals(null, $webUrl->getPort());
        $this->assertEquals("/informacje/onetkrakow/krakow-spor-o-ziemie-na-klinach-w-tle-znajomosc-prezydenta-z-deweloperem/fblvhwy,79cfc278", $webUrl->getPath());
        $this->assertEquals(null, $webUrl->getQuery());
        $this->assertEquals(null, $webUrl->getFragment());
    }

    public function testToString()
    {
        $url = "https://onet.pl/foo?query=query#bar";
        $webUrl = WebUrl::fromString("https://onet.pl/foo?query=query#bar");

        $this->assertEquals($url, $webUrl->toString());
    }

    public function testCanCreateFromRelativePath()
    {
        $webUrl = WebUrl::fromString("//foo");
        $this->assertEquals("/foo", $webUrl->getPath());

        $webUrl = WebUrl::fromString("/foo");
        $this->assertEquals("/foo", $webUrl->getPath());
    }
}
