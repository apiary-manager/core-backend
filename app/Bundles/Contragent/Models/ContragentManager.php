<?php

namespace App\Bundles\Contragent\Models;

use App\Bundles\Contragent\Models\Contragent;
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
    public const FIELD_NAME = 'name';
    public const FIELD_PATRONYMIC = 'patronymic';
    public const FIELD_FAMILY = 'family';
    public const FIELD_CONTRAGENT_ID = 'contragent_id';
    public const FIELD_POST = 'post';

    /**
     * @codeCoverageIgnore
     */
    public function contragent(): BelongsTo
    {
        return $this->belongsTo(Contragent::class);
    }
}