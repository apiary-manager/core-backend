<?php

namespace App\Services\Document\DocTemplateLoader;

use App\Models\Document\Document;
use DocumentBundle\Exception\DocumentFailedToSaveException;
use DocumentBundle\Helper\TextMinificator;

abstract class AbstractDocTemplateLoader
{
    const NAME = '';

    const TEMPLATE_DIR = '@Document/DocumentTemplates/';

    const TEMPLATE_DIR_FULL_PATH = __DIR__ . '/../../Resources/views/DocumentTemplates/';

    abstract protected function make(Document $document, $save = false);

    abstract protected function makeMany($documents, $save = false);

    public function load(Document $document, $save = false)
    {
        return $this->make($document, $save);
    }

    public function loadMany($documents, $save = false)
    {
        return $this->makeMany($documents, $save);
    }

    /**
     * Получил файл шаблона
     *
     * @param Document $document
     * @return false|string
     */
    protected function getTemplateFileByDocument(Document $document)
    {
        $path = self::TEMPLATE_DIR_FULL_PATH. $document->type->template;

        return file_get_contents($path);
    }

    /**
     * Получить путь для сохранения документа
     *
     * @param Document $document
     * @return string
     * @throws \Exception
     */
    public function getSavePath(Document $document)
    {
        if (empty($document->folder)) {
            $document->folder = trim(file_get_contents(base_path(env('DOCUMENT_SAVE_MAP'))));
            $document->save();
        }

        $dir = implode(DIRECTORY_SEPARATOR, [
            base_path(env('DOCUMENT_SAVE_DIR')),
            $document->folder,
            $document->uuid
        ]);

        if (!file_exists($dir)) {
            mkdir($dir, 0777, true);
        }

        return $dir . DIRECTORY_SEPARATOR . $document->getFileName();
    }

    /**
     * Сохранить документ
     *
     * @param Document $document
     * @param $output
     * @param string $path
     * @return string
     * @throws \Exception
     */
    protected function saveDocument(Document $document, $output, $path = null)
    {
        if ($path === null) {
            $path = $this->getSavePath($document);
        }

        if (file_put_contents($path, $output) === false) {
            throw new DocumentFailedToSaveException($document);
        }

        return $path;
    }
}
