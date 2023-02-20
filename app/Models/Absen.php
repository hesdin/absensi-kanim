<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absen extends Model
{
    use HasFactory;


    /**
     * Get the user that owns the mobile.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected $appends = ['bulan', 'tahun'];

    public function getBulanAttribute()
    {
      return Carbon::parse($this->created_at)->isoFormat('MMMM');
    }

    public function getTahunAttribute()
    {
      return Carbon::parse($this->created_at)->isoFormat('YYYY');
    }
}
