<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jurnal extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }
    public function mata_pelajaran()
    {
        return $this->belongsTo(MataPelajaran::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function materi()
    {
        return $this->belongsTo(Materi::class);
    }
    public function presensis()
    {
        return $this->hasMany(Presensi::class);
    }
    public function semester()
    {
        return $this->belongsTo(Semester::class);
    }
    public function verifikasi()
    {
        return $this->hasOne(VerifikasiJurnal::class);
    }
}
