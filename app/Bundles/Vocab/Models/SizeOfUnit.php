<?php

namespace App\Bundles\Vocab\Models;

use App\Based\ModelSupport\WithFillOfRequest;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $name
 * @property string $short_name
 * @property string $name_en
 * @property string $short_name_en
 * @property int $okei
 */
final class SizeOfUnit extends Model
{
    use WithFillOfRequest;

    public const FIELD_ID = 'id';
    public const FIELD_NAME = 'name';
    public const FIELD_SHORT_NAME = 'short_name';
    public const FIELD_NAME_EN = 'name_en';
    public const FIELD_SHORT_NAME_EN = 'short_name_en';
    public const FIELD_OKEI = 'okei';

    protected $fillable = [
        self::FIELD_NAME,
        self::FIELD_SHORT_NAME,
        self::FIELD_NAME_EN,
        self::FIELD_SHORT_NAME_EN,
        self::FIELD_OKEI,
    ];
}
