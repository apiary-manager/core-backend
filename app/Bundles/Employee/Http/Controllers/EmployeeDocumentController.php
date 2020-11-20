<?php

namespace App\Bundles\Employee\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resource\DocumentResource;
use App\Bundles\Employee\Models\Employee;
use App\Services\Document\DocumentCreator;

/**
 * Class EmployeeDocumentController
 * @package App\Bundles\Employee\Http\Controllers
 */
class EmployeeDocumentController extends Controller
{
    /**
     * @param Employee $employee
     * @param int $typeId
     * @return DocumentResource
     * @throws \Throwable
     */
    public function byType(Employee $employee, int $typeId): DocumentResource
    {
        $document = DocumentCreator::getInstance($typeId)->save();

        $document->employees()->attach($employee->id);

        $document->build();

        return new DocumentResource($document);
    }
}