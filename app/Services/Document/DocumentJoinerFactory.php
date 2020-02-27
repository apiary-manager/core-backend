<?php

namespace App\Services\Document;

use App\Models\Document\Document;
use App\Services\Document\DocumentJoiner\AbstractDocumentJoiner;
use App\Services\Document\DocumentJoiner\PDFJoiner;

class DocumentJoinerFactory
{
    const JOINERS = [
        PDFJoiner::OUTPUT_FILE_EXTENSION => PDFJoiner::class,
    ];

    /**
     * @param Document[] $documents
     * @param null $savePath
     * @return bool
     */
    public static function join(array $documents, $savePath = null)
    {
        $ext = $documents[0]->getExtensionName();

        if (($count = count ($documents)) && $count > 1) {
            for ($i = 1; $i < $count; $i++) {
                if ($ext !== $documents[$i]->getExtensionName()) {
                    return false;
                }
            }
        }

        if (!isset(self::JOINERS[$ext])) {
            return false;
        }

        $joinerClass = self::JOINERS[$ext];

        /** @var AbstractDocumentJoiner $joiner */
        $joiner = new $joinerClass($documents, $savePath);

        return $joiner->join();
    }
}