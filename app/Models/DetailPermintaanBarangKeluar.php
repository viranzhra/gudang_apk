<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPermintaanBarangKeluar extends Model
{
    use HasFactory;

    protected $table = "detail_permintaan_bk";

    protected $fillable = [
        'permintaan_barang_keluar_id',
        'serial_number_id',
        'keterangan'
    ];
}
