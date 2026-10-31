<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReportStatusLog extends Model
{
    protected $fillable = [
        'report_id',
        'status_lama',
        'status_baru',
        'changed_by',
    ];

    public function report(): BelongsTo
    {
        return $this->belongsTo(Report::class);
    }

    public function pengubah(): BelongsTo
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}