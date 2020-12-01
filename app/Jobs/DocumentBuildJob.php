<?php

namespace App\Jobs;

use App\Based\Interfaces\JobInterface;
use App\Events\DocumentOfQueueGenerated;
use App\Models\Document\Document;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

/**
 * Class DocumentBuildJob
 * @package App\Jobs
 */
class DocumentBuildJob implements ShouldQueue, JobInterface
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Document
     */
    public $document;

    /**
     * DocumentBuildJob constructor.
     * @param Document $document
     */
    public function __construct(Document $document)
    {
        $this->document = $document;
    }

    public function handle(): void
    {
        $this->document->build();

        event(new DocumentOfQueueGenerated($this->document));
    }
}