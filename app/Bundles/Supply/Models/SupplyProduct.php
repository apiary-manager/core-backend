<?php

namespace App\Bundles\Supply\Models;

use App\Bundles\Product\Models\Product;
use App\Bundles\Vocab\Models\VocabQuantityUnit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Query\Builder;

/**
 * Class SupplyProduct
 *
 * @property integer $id
 * @property double $price
 * @property integer $quantity
 * @property VocabQuantityUnit $quantityUnit
 * @property integer $quantity_unit_id
 * @property integer $product_id
 * @property integer $supply_id
 * @property Product $parent
 *
 * @mixin Builder
 */
class SupplyProduct extends Model
{
    public const RELATION_QUANTITY_UNIT = 'quantityUnit';
    public const RELATION_PARENT = 'parent';

    public const FIELD_PRICE = 'price';
    public const FIELD_QUANTITY = 'quantity';
    public const FIELD_PARENT_ID = 'product_id';
    public const FIELD_SUPPLY_ID = 'supply_id';
    public const FIELD_QUANTITY_UNIT_ID = 'quantity_unit_id';

    protected $fillable = [
        self::FIELD_PRICE,
        self::FIELD_QUANTITY,
        self::FIELD_PARENT_ID,
        self::FIELD_SUPPLY_ID,
        self::FIELD_QUANTITY_UNIT_ID,
    ];

    /**
     * @codeCoverageIgnore
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Product::class, static::FIELD_PARENT_ID);
    }

    /**
     * @codeCoverageIgnore
     */
    public function quantityUnit(): BelongsTo
    {
        return $this->belongsTo(VocabQuantityUnit::class);
    }

    /**
     * @return float
     */
    public function getTotalPrice(): float
    {
        return round((float) $this->price * $this->quantity);
    }
}
