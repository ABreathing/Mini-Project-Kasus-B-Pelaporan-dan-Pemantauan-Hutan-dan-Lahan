<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Report extends Model
{
    public const MAX_DIKIRIM = 3;

    public const TRANSITIONS = [
        'relawan' => [
            'dikirim' => ['ditinjau'],
            'ditinjau' => ['perlu_diteruskan', 'selesai'],
        ],
        'koordinator' => [
            'diteruskan' => ['selesai'],
        ],
    ];

    protected $fillable = [
        'user_id',
        'area_id',
        'judul',
        'kondisi',
        'tingkat',
        'latitude',
        'longitude',
        'waktu_kejadian',
        'evidence_path',
        'status',
        'catatan_relawan',
        'reviewed_by',
    ];

    protected function casts(): array
    {
        return [
            'waktu_kejadian' => 'datetime',
        ];
    }

    public function scopeVisibleTo(Builder $query, User $user): Builder
    {
        if ($user->isRelawan() || $user->isKoordinator()) {
            return $query;
        }

        return $query->where('user_id', $user->id);
    }

    public function nextStatusesFor(User $user): array
    {
        return self::TRANSITIONS[$user->role][$this->status] ?? [];
    }

    public function pelapor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    // catatan penerusan oleh koordinator (paling banyak satu)
    public function forwarding(): HasOne
    {
        return $this->hasOne(ReportForwarding::class);
    }
}