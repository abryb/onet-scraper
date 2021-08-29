<?php

namespace App\Onet;

/**
 * @author Błażej Rybarkiewicz <b.rybarkiewicz@gmail.com>
 */
interface FoundNewsHandlerInterface
{
    public function handleFoundNews(News $news): void;
}
