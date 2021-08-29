<?php

namespace App;

use App\Crawler\CrawlerRunner;
use App\Crawler\UrlExtractor\RegexUrlExtractor;
use App\HttpClient\CurlHttpClient;
use App\Onet\FoundNewsPrintHandler;
use App\Onet\OnetCrawler;
use App\Onet\RegexNewsScraper;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Log\LoggerInterface;

/**
 * @author Błażej Rybarkiewicz <b.rybarkiewicz@gmail.com>
 */
final class App
{
    private LoggerInterface $logger;

    public function __construct(bool $debug = false)
    {
        $log = new Logger('app');
        $log->pushHandler(new StreamHandler('php://stdout', $debug ? Logger::DEBUG : Logger::WARNING));
        $this->logger = $log;
    }

    public function runOnetNewsScraper(): void
    {
        $crawlerRunner = new CrawlerRunner(
            httpClient: new CurlHttpClient(),
            urlExtractor: new RegexUrlExtractor(),
            logger: $this->logger
        );

        $crawler = new OnetCrawler(
            newsScraper: new RegexNewsScraper(),
            foundNewsHandler: new FoundNewsPrintHandler(),
        );

        $crawlerRunner->runCrawler(
            crawler: $crawler,
            startingUrl: "https://onet.pl"
        );
    }
}
