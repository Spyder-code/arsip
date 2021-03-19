<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Buku extends Model
{
    protected $table = 'buku';
    protected $fillable = [
        'dari',
        'untuk',
        'perihal',
        'nomor_surat',
        'tahun',
        'no',
        'file',
    ];

}
