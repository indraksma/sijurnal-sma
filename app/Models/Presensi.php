<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Presensi extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function jurnal()
    {
        return $this->belongsTo(Jurnal::class);
    }
    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }
}
