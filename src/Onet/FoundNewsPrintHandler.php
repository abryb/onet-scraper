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
        echo "---------------\n";
        echo "News image url : {$news->getImageUrl()} \n";
        echo "News Tile      : {$news->getTitle()} \n";
        echo "News Url       : {$news->getUrl()} \n";
    }
}
