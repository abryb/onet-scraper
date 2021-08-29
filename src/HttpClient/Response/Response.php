<?php

namespace App\HttpClient\Response;

/**
 * @author Błażej Rybarkiewicz <b.rybarkiewicz@gmail.com>
 */
final class Response implements ResponseInterface
{
    public function __construct(
        private int $statusCode,
        private string $content
    ) {
    }

    /**
     * {@inheritdoc}
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * {@inheritdoc}
     */
    public function getContent(): string
    {
        return $this->content;
    }
}
