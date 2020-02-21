<?php

namespace App\Services\Document;

use App\Models\Document\Document;
use App\Service\Document\DocumentService;
use App\Services\Shell\ShellCommand;

/**
 * Class DocumentPsFileMaker
 *
 * Инструмент для создания файла печати .ps
 */
class DocumentPsFileMaker
{
    /** @var Document[] */
    private $documents = null;

    /** @var string */
    private $baseFileName = null;

    /**
     * @return static
     */
    public static function getInstance(): self
    {
        return new self();
    }

    /**
     * @param Document $documents
     * @return static
     */
    public static function getInstanceByDocs($documents): self
    {
        $instance = new self();
        foreach ($documents as $document) {
            $instance->addDocument($document);
        }

        return $instance;
    }

    /**
     * @return string
     */
    public function join(): string
    {
        $this->createTmpFolders($inputDir, $outDir);
        $this->copyFilesToInputDir($inputDir);

        $command = ShellCommand::getInstance('soffice', false)
            ->addOption('headless')
            ->addOption('print-to-file')
            ->addOption('outdir')
            ->addParameter($outDir)
            ->addParameter($inputDir . '*');

        $result = $command->getShellResult();
        if (null === $result) {
            $this->handleException();
        }

        $outputFile = FileHelper::changeExtension(
            $outDir . DIRECTORY_SEPARATOR . $this->baseFileName,
            'ps'
        );

        if (!file_exists($outputFile)) {
            $this->handleException();
        }

        return $outputFile;
    }

    /**
     * @param string $inputDir
     */
    private function copyFilesToInputDir(string $inputDir)
    {
        foreach ($this->documents as $key => $document) {
            $path = DocumentService::getDownloadLink($document, true);
            $newFileName = 'file-'. $key . '-'. $document->getFileName();
            $newPath = $inputDir . $newFileName;

            if ($key == 0) {
                $this->baseFileName = $newFileName;
            }

            copy($path, $newPath);
        }
    }

    /**
     * @param $inputDir
     * @param $outputDir
     */
    private function createTmpFolders(&$inputDir, &$outputDir)
    {
        $root = storage_path('documents_ps');
        if (!file_exists($root)) {
            mkdir($root, 0775);
        }

        $docFolder = $root . DIRECTORY_SEPARATOR . time();
        if (!file_exists($docFolder)) {
            mkdir($docFolder, 0775);
        }

        $inputDir = $docFolder . DIRECTORY_SEPARATOR . 'input/';
        $outputDir = $docFolder . DIRECTORY_SEPARATOR . 'output/';

        mkdir($inputDir, 0775);
        mkdir($outputDir, 0775);
    }

    /**
     * @param Document $document
     * @return $this
     */
    public function addDocument(Document $document): self
    {
        $this->documents[] = $document;

        return $this;
    }


    private function handleException()
    {
        throw new \LogicException("Не удалось объединить файлы: \n" . implode("\n",
            array_map(function (Document $document) {
                return $document->getFileName();
            }, $this->documents)
        ));
    }
}