<?php

namespace App\Crawler;

use App\HttpClient\HttpClientInterface;
use Psr\Log\LoggerInterface;

/**
 * @author Błażej Rybarkiewicz <b.rybarkiewicz@gmail.com>
 */
final class CrawlerRunner implements CrawlerRunnerInterface
{
    /** @var WebUrl[] */
    private array $webUrlsQueue = [];

    public function __construct(
        private HttpClientInterface $httpClient,
        private UrlExtractorInterface $urlExtractor,
        private LoggerInterface $logger,
    ) {
    }

    /**
     * @param CrawlerInterface $crawler
     * @param string $startingUrl
     * @param array $options = [
     *     'usleep' => 500_000, // milliseconds to sleep between requests
     * ];
     */
    public function runCrawler(CrawlerInterface $crawler, string $startingUrl, array $options = []): void
    {
        $options = array_replace([
            'usleep' => 300_000
        ], $options);

        $this->addToQueue(WebUrl::fromString($startingUrl));

        while ($webUrl = $this->nextUrl()) {
            usleep($options['usleep']);

            $this->logger->debug("Request {$webUrl}.");

            $response = $this->httpClient->get($webUrl->toString());

            if ($response->getStatusCode() === 200) {
                $page = new Page($webUrl, $response->getContent());

                $crawler->visit($page);

                $foundUrls = $this->urlExtractor->extractUrls($page);

                foreach ($foundUrls as $foundUrl) {
                    if ($crawler->shouldVisit($page, $foundUrl)) {
                        $this->addToQueue($foundUrl);
                    }
                }
            }
        }
    }

    private function addToQueue(WebUrl $url) : void
    {
        $this->webUrlsQueue[$url->toString()] = $url;
    }

    private function nextUrl(): ?WebUrl
    {
        return array_shift($this->webUrlsQueue);
    }
}
