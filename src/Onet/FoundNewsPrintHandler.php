<?php

namespace App\Onet;

/**
 * @author Błażej Rybarkiewicz <b.rybarkiewicz@gmail.com>
 */
class FoundNewsPrintHandler implements FoundNewsHandlerInterface
{
    /**
     * @throws \JsonException
     */
    public function handleFoundNews(News $news): void
    {
        echo json_encode([
                'imageUrl' => $news->getImageUrl(),
                'title' => $news->getTitle(),
                'url' => $news->getUrl()
            ], JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR) . "\n";
    }
}
