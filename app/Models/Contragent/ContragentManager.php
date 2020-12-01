<?php

namespace App\Models\Contragent;

use App\Models\Contragent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Query\Builder;

/**
 * @property int $id
 * @property string $name
 * @property string|null $patronymic
 * @property string|null $family
 * @property string $post
 * @property int $contragent_id
 * @mixin Builder
 */
final class ContragentManager extends Model
{
    const PSEUDO = 'managers';

    /**
     * @return BelongsTo
     */
    public function contragent(): BelongsTo
    {
        return $this->belongsTo(Contragent::class);
    }
}