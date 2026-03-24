<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class BreakdownLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'unit_id',
        'spare_unit_id',
        'user_id',
        'vendor_id',
        'waktu_awal_bd',
        'waktu_akhir_bd',
        'waktu_spare_datang',
        'status',
        'keterangan',
    ];

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::addGlobalScope('vendor', function (\Illuminate\Database\Eloquent\Builder $builder) {
            if (auth()->check() && !auth()->user()->isSuperAdmin() && auth()->user()->vendor_id) {
                $builder->where('vendor_id', auth()->user()->vendor_id);
            }
        });

        static::saving(function (BreakdownLog $log) {
            // 1. Auto-assign vendor from unit if not manually set or if unit changed
            if ($log->unit_id) {
                $unit = MasterUnit::find($log->unit_id);
                if ($unit && $unit->vendor_id) {
                    $log->vendor_id = $unit->vendor_id;
                }
            }

            // 2. Clear spare arrival time if no spare unit is assigned
            if (!$log->spare_unit_id) {
                $log->waktu_spare_datang = null;
            }

            // 3. Auto-status based on waktu_akhir_bd
            if (!$log->exists) {
                // When creating, always auto-calculate since there's no manual status field
                $log->status = $log->waktu_akhir_bd ? 'Closed' : 'Open';
            } else {
                // When updating:
                // Admins have manual status radio buttons on the edit form. We trust their input.
                // Operators don't have those buttons, so we force auto-calculation for them.
                if (!auth()->check() || (!auth()->user()->isSuperAdmin() && !auth()->user()->isVendorAdmin())) {
                    $log->status = $log->waktu_akhir_bd ? 'Closed' : 'Open';
                } elseif (!$log->status) {
                    $log->status = $log->waktu_akhir_bd ? 'Closed' : 'Open';
                }
            }
        });
    }

    /**
     * Get the vendor associated with this breakdown.
     */
    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }

    /**
     * Get the unit that is broken down.
     */
    public function unit(): BelongsTo
    {
        return $this->belongsTo(MasterUnit::class, 'unit_id');
    }

    /**
     * Get the spare unit assigned to this breakdown.
     */
    public function spareUnit(): BelongsTo
    {
        return $this->belongsTo(MasterUnit::class, 'spare_unit_id');
    }

    /**
     * Get the user who reported the breakdown.
     */
    public function reporter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Accessor to calculate loss_time (diff between waktu_awal_bd and waktu_akhir_bd).
     */
    protected function lossTime(): Attribute
    {
        return Attribute::make(
            get: function () {
                if (!$this->waktu_awal_bd) {
                    return null;
                }

                $awal = Carbon::parse($this->waktu_awal_bd);
                
                if ($this->spare_unit_id && $this->waktu_spare_datang) {
                    $akhir = Carbon::parse($this->waktu_spare_datang);
                } else {
                    if (!$this->waktu_akhir_bd) return null;
                    $akhir = Carbon::parse($this->waktu_akhir_bd);
                }

                if ($akhir->lessThan($awal)) return '0 Menit';

                $diff = $awal->diff($akhir);
                $parts = [];
                if ($diff->d > 0) $parts[] = $diff->d . ' Hari';
                if ($diff->h > 0) $parts[] = $diff->h . ' Jam';
                if ($diff->i > 0) $parts[] = $diff->i . ' Menit';

                return count($parts) > 0 ? implode(' ', $parts) : '0 Menit';
            }
        );
    }

    /**
     * Accessor to calculate lama_unit_breakdown (diff between waktu_awal_bd and waktu_akhir_bd).
     */
    protected function lamaUnitBreakdown(): Attribute
    {
        return Attribute::make(
            get: function () {
                if (!$this->waktu_awal_bd || !$this->waktu_akhir_bd) {
                    return null;
                }

                $awal = Carbon::parse($this->waktu_awal_bd);
                $akhir = Carbon::parse($this->waktu_akhir_bd);

                if ($akhir->lessThan($awal)) return '0 Menit';

                $diff = $awal->diff($akhir);
                $parts = [];
                if ($diff->d > 0) $parts[] = $diff->d . ' Hari';
                if ($diff->h > 0) $parts[] = $diff->h . ' Jam';
                if ($diff->i > 0) $parts[] = $diff->i . ' Menit';

                return count($parts) > 0 ? implode(' ', $parts) : '0 Menit';
            }
        );
    }

    /**
     * Accessor to calculate loss_time as a percentage of 24 hours (1 day).
     */
    protected function lossTimePercentage(): Attribute
    {
        return Attribute::make(
            get: function () {
                if (!$this->waktu_awal_bd) {
                    return null;
                }

                $awal = Carbon::parse($this->waktu_awal_bd);
                
                if ($this->spare_unit_id && $this->waktu_spare_datang) {
                    $akhir = Carbon::parse($this->waktu_spare_datang);
                } else {
                    if (!$this->waktu_akhir_bd) return null;
                    $akhir = Carbon::parse($this->waktu_akhir_bd);
                }

                if ($akhir->lessThan($awal)) return '0.00%';

                $totalSeconds = $awal->diffInSeconds($akhir);
                $secondsInDay = 86400;

                $percentage = ($totalSeconds / $secondsInDay) * 100;

                return number_format($percentage, 2) . '%';
            }
        );
    }
}
