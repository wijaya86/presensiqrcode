<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AutoAlphaLog extends Model
{
    protected $fillable = ['nisn', 'status', 'tanggal', 'pesan'];


     public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'nisn', 'NISN');
    }

}

