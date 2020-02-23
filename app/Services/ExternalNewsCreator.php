<?php

namespace App\Services;

use App\Models\News\ExternalNews;
use App\Models\News\ExternalNewsSource;
use App\Parsers\RssParser;

class ExternalNewsCreator
{
    private static $existsNews = null;

    /**
     * @param null $sources
     * @return array
     */
    public static function create($sources = null): array
    {
        if ($sources === null) {
            $sources = ExternalNewsSource::all();
        } elseif ($sources instanceof ExternalNewsSource) {
            $sources = [$sources];
        }

        $news = [];
        foreach ($sources as $source) {
            $items = (new RssParser($source->link))->getArrayItems();
            array_merge($news, self::createNews($items, $source));
        }

        return $news;
    }

    private static function createNews(array $items, ExternalNewsSource $source)
    {
        $links = [];
        foreach ($items as $item) {
            if (!isset($item['link'])) {
                continue;
            }

            $links[] = $item['link'];
        }

        self::$existsNews = ExternalNews::whereIn('link', $links)
            ->get()
            ->pluck('id', 'link');

        $news = [];
        foreach ($items as $item) {
            $news[] = self::prepareNews($item, $source);
        }

        return $news;
    }

    private static function prepareNews(array $item, ExternalNewsSource $source)
    {
        if (
            !isset($item['title']) ||
            !isset($item['pubDate']) ||
            !isset($item['link']) ||
            self::isExistsNews($item['link'])
        ) {
            return false;
        }

        $news = new ExternalNews();
        $news->title = $item['title'];
        $news->pub_date = $item['pubDate'];
        $news->link = $item['link'];
        $news->description = $item['description'] ?? null;
        $news->source_id = $source->id;
        $news->save();

        return $news;
    }

    private static function isExistsNews($link)
    {
        return isset(self::$existsNews[$link]);
    }
}