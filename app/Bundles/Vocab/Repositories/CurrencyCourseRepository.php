<?php

namespace App\Bundles\Vocab\Repositories;

use App\Bundles\Vocab\Models\CurrencyCourse;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class CurrencyCourseRepository
 * @package App\Repositories
 */
final class CurrencyCourseRepository
{
    /**
     * @return Collection|CurrencyCourse[]
     */
    public static function last(): Collection
    {
        return CurrencyCourse::query()
            ->with(CurrencyCourse::RELATION_CURRENCY)
            ->orderBy(CurrencyCourse::FIELD_ACTUAL_DATE, 'desc')
            ->take(100)
            ->get();
    }
}