<?php

namespace App\Models\Document;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class DocumentType
 *
 * @property int id
 * @property string name
 * @property string title
 * @property string template
 * @property int loader_id
 * @property DocumentLoader loader
 *
 * @mixin Builder
 */
class DocumentType extends Model
{
    const SCORE_FOR_PAYMENT_ID = 1;
    const SCORES_FOR_PAYMENTS_ID = 2;
    const TORG_12_ID = 3;
    const QUALITY_CERTIFICATE_ID = 4;
    const MANY_TORG_12_ID = 5;

    /**
     * @return BelongsTo|DocumentLoader
     */
    public function loader()
    {
        return $this->belongsTo(DocumentLoader::class);
    }
}
