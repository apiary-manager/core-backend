<?php

namespace App\Bundles\Contract\Models;

use App\Models\Contragent;
use App\Models\Supply\Supply;
use App\Models\Traits\WithSupplierAndCustomer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Query\Builder;

/**
 * @property int $id
 * @property string $title
 * @property string $planned_date
 * @property string $executed_date
 * @property int $supplier_id
 * @property int $customer_id
 * @property int $template_id
 * @property ContractTemplate $template
 */
class Contract extends Model
{
    use WithSupplierAndCustomer;

    public const FIELD_TITLE = 'title';
    public const FIELD_PLANNED_DATE = 'planned_date';
    public const FIELD_EXECUTED_DATE = 'executed_date';
    public const FIELD_SUPPLIER_ID = 'supplier_id';
    public const FIELD_TEMPLATE_ID = 'template_id';
    public const FIELD_CUSTOMER_ID = 'customer_id';

    public const RELATION_CUSTOMER = 'customer';
    public const RELATION_SUPPLIER = 'supplier';
    public const RELATION_TEMPLATE = 'template';
    public const RELATION_SUPPLIES = 'supplies';

    protected $fillable = [
        self::FIELD_TITLE,
        self::FIELD_PLANNED_DATE,
        self::FIELD_EXECUTED_DATE,
        self::FIELD_SUPPLIER_ID,
        self::FIELD_TEMPLATE_ID,
        self::FIELD_CUSTOMER_ID,
    ];

    public function template(): BelongsTo
    {
        return $this->belongsTo(ContractTemplate::class);
    }

    /**
     * Поставки по договору
     */
    public function supplies(): HasMany
    {
        return $this->hasMany(Supply::class);
    }
}