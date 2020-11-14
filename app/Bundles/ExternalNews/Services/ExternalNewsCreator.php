<?php

namespace App\Bundles\ExternalNews\Services;

use App\Bundles\ExternalNews\Models\ExternalNews;
use App\Bundles\ExternalNews\Models\ExternalNewsSource;
use App\Bundles\ExternalNews\Repositories\ExternalNewsRepository;
use App\Bundles\ExternalNews\Support\Rss;

final class ExternalNewsCreator
{
    private static $existsNews = null;

    /**
     * @param ExternalNewsSource|array|null $sources
     * @return array
     */
    public static function create($sources = null): array
    {
        $sources = ($sources instanceof ExternalNewsSource) ? [$sources] : ExternalNewsSource::all();

        $news = [];
        foreach ($sources as $source) {
            $items = (new Rss())->parse($source->link);
            $news = array_merge($news, self::createNews($items, $source));
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

        static::$existsNews = ExternalNewsRepository::findByLinks($links)
            ->pluck('id', ExternalNews::FIELD_LINK);

        $news = [];
        foreach ($items as $item) {
            $news[] = static::prepareNews($item, $source);
        }

        return $news;
    }

    private static function prepareNews(array $item, ExternalNewsSource $source)
    {
        if (!isset($item['title']) ||
            !isset($item['pubDate']) ||
            !isset($item['link']) ||
            self::isExistsNews($item['link'])) {
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

    /**
     * @param $link
     * @return bool
     */
    private static function isExistsNews($link): bool
    {
        return isset(self::$existsNews[$link]);
    }
}
