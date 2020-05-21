<?php

namespace App\Events;

use App\Models\TechSupport\TechSupportReport;

class TechSupportReportCreated extends BaseEvent
{
    public $report;

    public function __construct(TechSupportReport $report)
    {
        $this->report = $report;
    }
}
