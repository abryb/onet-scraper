<?php

namespace App\HttpClient;

use App\HttpClient\Response\ResponseInterface;

/**
 * @author Błażej Rybarkiewicz <b.rybarkiewicz@gmail.com>
 */
interface HttpClientInterface
{
    /**
     * @param string $url
     * @param array<string,string> $headers = [
     *     // associative array of http headers, where key is header name and value is header value e.g
     *     'Content-Type' => 'application/json',
     * ]
     * @return ResponseInterface
     *
     * Any implementation should follow redirects
     */
    public function get(string $url, array $headers = []): ResponseInterface;
}
