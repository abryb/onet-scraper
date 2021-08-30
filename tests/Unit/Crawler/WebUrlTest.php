<?php

namespace App\Tests\Unit\Crawler;

use App\Crawler\WebUrl;
use PHPUnit\Framework\TestCase;

/**
 * @author Błażej Rybarkiewicz <b.rybarkiewicz@gmail.com>
 */
class WebUrlTest extends TestCase
{
    public function toStringDataProvider(): array
    {
        return [
            ["http://onet.pl", new WebUrl("http", "onet.pl", null, "")],
            ["https://onet.pl", new WebUrl("https", "onet.pl", null, "")],
            ["https://wp.pl", new WebUrl("https", "wp.pl", null, "")],
            ["https://onet.pl", new WebUrl("https", "onet.pl", null, "")],
            ["https://onet.pl:100", new WebUrl("https", "onet.pl", 100, "")],
            ["https://onet.pl:100", new WebUrl("https", "onet.pl", 100, null)],
            ["https://onet.pl:100/foo", new WebUrl("https", "onet.pl", 100, "/foo")],
            ["https://onet.pl/foo", new WebUrl("https", "onet.pl", null, "/foo")],
            ["https://onet.pl/foo", new WebUrl("https", "onet.pl", null, "/foo")],
            ["onet.pl/foo", new WebUrl(null, "onet.pl", null, "/foo")],
            ["onet.pl", new WebUrl(null, "onet.pl", null, null)],
            ["onet.pl/", new WebUrl(null, "onet.pl", null, "/")],
            ["/foo", new WebUrl(null, null, null, "/foo")],
            ["/foo", new WebUrl(null, null, null, "//foo")],
            ["/foo?bar=bar", new WebUrl(null, null, null, "/foo", "bar=bar")],
            ["/foo?bar=bar#foobar", new WebUrl(null, null, null, "/foo", "bar=bar", "foobar")],
            ["https://onet.pl/foo?query=query#bar", new WebUrl("https", "onet.pl", null, "/foo", "query=query", "bar")],
        ];
    }

    /**
     * @dataProvider toStringDataProvider
     */
    public function testToString(string $expected, WebUrl $webUrl): void
    {
        $this->assertEquals($expected, $webUrl->toString());
    }

    /**
     * @dataProvider toStringDataProvider
     */
    public function testCreateFromString(string $url, WebUrl $expected): void
    {
        $webUrl = WebUrl::fromString($url);

        $this->assertEquals($expected->getScheme(), $webUrl->getScheme());
        $this->assertEquals($expected->getHost(), $webUrl->getHost());
        $this->assertEquals($expected->getPort(), $webUrl->getPort());
        $this->assertEquals($expected->getPath(), $webUrl->getPath());
        $this->assertEquals($expected->getQuery(), $webUrl->getQuery());
        $this->assertEquals($expected->getFragment(), $webUrl->getFragment());
    }


    public function testCanCreateFromRelativePath(): void
    {
        $webUrl = WebUrl::fromString("//foo");
        $this->assertEquals("/foo", $webUrl->getPath());

        $webUrl = WebUrl::fromString("/foo");
        $this->assertEquals("/foo", $webUrl->getPath());
    }


    public function getHttpRelativePathRootDataProvider(): array
    {
        return [
            ["https://onet.pl", new WebUrl("https", "onet.pl", null, "/")],
            ["https://onet.pl", new WebUrl("https", "onet.pl", null, "")],
            ["https://onet.pl", new WebUrl("https", "onet.pl", null, "/foo")],
            ["https://onet.pl/foo/", new WebUrl("https", "onet.pl", null, "/foo/")],
            ["https://onet.pl:81", new WebUrl("https", "onet.pl", "81", "")],
        ];
    }

    /**
     * @dataProvider getHttpRelativePathRootDataProvider
     */
    public function testGetHttpRelativePathRoot(string $expected, WebUrl $webUrl): void
    {
        $this->assertEquals($expected, $webUrl->getHttpRelativePathRoot());
    }
}
