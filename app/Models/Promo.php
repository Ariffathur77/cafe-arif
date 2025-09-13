<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promo extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'description',
        'type',
        'value',
        'start_date',
        'end_date',
        'is_active',
    ];

    public function menus()
    {
        return $this->belongsToMany(Menu::class, 'menu_promo');
    }

    public function getIsCurrentlyActiveAttribute(): bool
    {
        if (!$this->is_active) {
            return false;
        }

        $now = now();
        // Gunakan Carbon untuk memastikan formatnya benar
        $startDate = \Carbon\Carbon::parse($this->start_date)->startOfDay();
        $endDate = \Carbon\Carbon::parse($this->end_date)->endOfDay();

        // Cek berdasarkan tanggal
        if ($this->start_date && $this->end_date) {
            // Logika between sudah benar
            return $now->between($startDate, $endDate);
        } elseif ($this->start_date && !$this->end_date) {
            return $now->greaterThanOrEqualTo($startDate);
        } elseif (!$this->start_date && $this->end_date) {
            return $now->lessThanOrEqualTo($endDate);
        }

        // Jika tidak ada tanggal, selalu aktif
        return true;
    }
}
