<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MasterUnit extends Model
{
    use HasFactory;

    protected $fillable = [
        'nomor_lambung',
        'jenis_unit',
        'status_operasional',
        'vendor_id',
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

        static::saving(function ($unit) {
            if (is_null($unit->vendor_id)) {
                $prefix = strtoupper(substr($unit->nomor_lambung, 0, 4));
                if ($prefix === 'ARBI') {
                    $unit->vendor_id = 5; // CV BINA INTI PERSADA
                } elseif ($prefix === 'ARJH') {
                    $unit->vendor_id = 4; // PT Jejak Hasanah
                }
            }
        });
    }

    /**
     * Get the vendor associated with this unit.
     */
    public function vendor(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    /**
     * Get the breakdown logs where this unit is the one that broke down.
     */
    public function breakdowns(): HasMany
    {
        return $this->hasMany(BreakdownLog::class, 'unit_id');
    }

    /**
     * Get the breakdown logs where this unit is used as a spare.
     */
    public function replacements(): HasMany
    {
        return $this->hasMany(BreakdownLog::class, 'spare_unit_id');
    }
}
