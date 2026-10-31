<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReportForwarding extends Model
{
    protected $fillable = [
        'report_id',
        'forwarded_by',
        'pihak_terkait',
        'catatan',
    ];

    public function report(): BelongsTo
    {
        return $this->belongsTo(Report::class);
    }

    // koordinator yang mencatat penerusan
    public function koordinator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'forwarded_by');
    }
}