<?php

namespace App\HttpClient\Response;

/**
 * @author Błażej Rybarkiewicz <b.rybarkiewicz@gmail.com>
 */
interface ResponseInterface
{
    /**
     * @return int response status code
     */
    public function getStatusCode(): int;

    /**
     * @return string response raw content
     */
    public function getContent(): string;
}
