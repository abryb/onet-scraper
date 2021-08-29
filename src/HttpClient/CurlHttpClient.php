<?php

namespace App\HttpClient;

use App\HttpClient\Response\Response;
use App\HttpClient\Response\ResponseInterface;

/**
 * @author Błażej Rybarkiewicz <b.rybarkiewicz@gmail.com>
 */
final class CurlHttpClient implements HttpClientInterface
{
    /**
     * {@inheritdoc}
     */
    public function get(string $url, array $headers = []): ResponseInterface
    {
        // create curl resource
        $ch = curl_init();

        // set url
        curl_setopt($ch, CURLOPT_URL, $url);

        //return the transfer as a string
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        // follow redirects
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

        // $output contains the output string
        $content = curl_exec($ch);

        $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        // close curl resource to free up system resources
        curl_close($ch);

        return new Response(statusCode: $statusCode, content: $content);
    }
}
