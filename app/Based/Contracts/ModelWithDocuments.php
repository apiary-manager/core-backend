<?php

namespace App\Based\Contracts;

use App\Bundles\Document\Models\Document;
use App\Bundles\Document\Repositories\DocumentRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Interface ModelWithDocuments
 * @property int $id
 * @property Document[]|Collection $documents
 * @property int $supply_id
 */
interface ModelWithDocuments
{
    /**
     * @return Document|null
     */
    public function getDocument(): ?Document;

    /**
     * @return BelongsToMany
     */
    public function documents(): BelongsToMany;

    /**
     * @return bool
     */
    public function isExistsDocument(): bool;

    /**
     * @return bool
     */
    public function isNotExistsDocument(): bool;

    /**
     * @return DocumentRepository
     */
    public static function getDocRepo(): DocumentRepository;

    /**
     * @param array $options
     * @return mixed
     */
    public function save(array $options = []);
}
