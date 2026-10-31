<?php

namespace App\Observers;

use App\Models\Report;
use App\Models\ReportStatusLog;

class ReportObserver
{
    public function updated(Report $report): void
    {
        if (! $report->wasChanged('status')) {
            return;
        }

        ReportStatusLog::create([
            'report_id' => $report->id,
            'status_lama' => $report->getOriginal('status'),
            'status_baru' => $report->status,
            'changed_by' => auth()->id(),
        ]);
    }
}