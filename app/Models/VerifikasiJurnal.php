<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VerifikasiJurnal extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function jurnal()
    {
        return $this->belongsTo(Jurnal::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
