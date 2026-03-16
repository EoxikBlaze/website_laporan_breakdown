<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vendor extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_vendor',
        'kontak',
        'keterangan',
    ];

    /**
     * Get the units associated with this vendor.
     */
    public function units(): HasMany
    {
        return $this->hasMany(MasterUnit::class, 'vendor_id');
    }

    /**
     * Get the breakdown logs associated with this vendor.
     */
    public function breakdownLogs(): HasMany
    {
        return $this->hasMany(BreakdownLog::class, 'vendor_id');
    }
}
