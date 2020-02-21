<?php

namespace Tests\Feature;

use App\Models\Document\Document;
use App\Models\Document\DocumentType;
use App\Services\Document\DocumentBuilder;
use App\Services\Document\DocumentPsFileMaker;
use Tests\BaseTestCase;

class DocumentPsFileMakerTest extends BaseTestCase
{
    public function testJoin()
    {
        $documents = Document::where('type_id', DocumentType::TORG_12_ID)
            ->inRandomOrder()
            ->get()
            ->take(rand(2, 5));

        foreach ($documents as $document) {
            DocumentBuilder::build($document);
        }

        $joiner = DocumentPsFileMaker::getInstanceByDocs($documents);

        $file = $joiner->join();

        self::assertFileExists($file);
    }
}