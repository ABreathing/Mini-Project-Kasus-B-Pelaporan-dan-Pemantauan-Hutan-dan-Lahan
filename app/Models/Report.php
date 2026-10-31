<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Report extends Model
{
    public const STATUSES = ['dikirim', 'ditinjau', 'perlu_diteruskan', 'diteruskan', 'selesai'];

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

    public function scopeFilter(Builder $query, array $filters): Builder
    {
        return $query
            ->when($filters['q'] ?? null, function ($q, $kata) {
                $q->where(function ($q) use ($kata) {
                    $q->where('judul', 'like', '%' . $kata . '%')
                        ->orWhere('kondisi', 'like', '%' . $kata . '%');
                });
            })
            ->when($filters['area_id'] ?? null, fn ($q, $nilai) => $q->where('area_id', $nilai))
            ->when($filters['status'] ?? null, fn ($q, $nilai) => $q->where('status', $nilai))
            ->when($filters['tingkat'] ?? null, fn ($q, $nilai) => $q->where('tingkat', $nilai));
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

    public function forwarding(): HasOne
    {
        return $this->hasOne(ReportForwarding::class);
    }
    
    public function statusLogs(): HasMany
    {
        return $this->hasMany(ReportStatusLog::class);
    }
}