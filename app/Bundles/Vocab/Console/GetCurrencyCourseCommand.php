<?php

namespace App\Bundles\Vocab\Console;

use App\Bundles\Vocab\Services\CurrencyService;
use ArtARTs36\CbrCourseFinder\Contracts\Finder;
use Illuminate\Console\Command;

class GetCurrencyCourseCommand extends Command
{
    protected $signature = 'currency-course:now';

    protected $description = 'Get currency courses for today';

    protected $finder;

    protected $service;

    public function __construct(Finder $finder, CurrencyService $service)
    {
        parent::__construct();

        $this->finder = $finder;
        $this->service = $service;
    }

    public function handle()
    {
        $this->service->createOfExternals($this->finder->getOnDate(new \DateTime()));
    }
}
