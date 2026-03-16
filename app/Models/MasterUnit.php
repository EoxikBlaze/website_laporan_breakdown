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
