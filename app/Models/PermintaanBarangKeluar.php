<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermintaanBarangKeluar extends Model
{
    use HasFactory;

    protected $table = "permintaan_barang_keluar";

    protected $fillable = [
        'barangmasuk_id',
        'customer_id',
        'keperluan_id',
        'tanggal',
        'catatan',
        'status'
    ];
}
