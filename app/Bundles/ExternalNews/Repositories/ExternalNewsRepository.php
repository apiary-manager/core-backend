<?php

namespace App\Bundles\ExternalNews\Repositories;

use App\Based\Contracts\Repository;
use App\Bundles\ExternalNews\Models\ExternalNews;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class ExternalNewsRepository extends Repository implements \App\Bundles\ExternalNews\Contracts\ExternalNewsRepository
{
    public function paginate(int $page = 1): LengthAwarePaginator
    {
        return $this->newQuery()
            ->with(ExternalNews::RELATION_SOURCE)
            ->latest(ExternalNews::FIELD_ID)
            ->paginate(10, ['*'], 'ExternalNewsList', $page);
    }

    public function getByLinks(array $links, array $columns = ['*']): Collection
    {
        return $this
            ->newQuery()
            ->select($columns)
            ->whereIn(ExternalNews::FIELD_LINK, $links)
            ->get();
    }

    public function chart(int $count): LengthAwarePaginator
    {
        return $this
            ->newQuery()
            ->with(ExternalNews::RELATION_SOURCE)
            ->latest()
            ->paginate($count);
    }
}
