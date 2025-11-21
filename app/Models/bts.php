<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class bts extends Model
{
    protected $fillable = [
        'nama_BTS',
        'Longitude',
        'Latitude',
        'Tahun_registrasi',
        'Tahun_berakhir',
        'alamat',
        'Kode_operator',
        'Kode_perangkat_jaringan',
        'Kode_kecamatan',
    ];
    protected $table = 'bts';

    public function getSisaWaktuAttribute()
    {
        if (!$this->Tahun_registrasi || !$this->Tahun_berakhir) {
            return null;
        }

        // Parse format MySQL (Y-m-d)
        $start = Carbon::parse($this->Tahun_registrasi);
        $end   = Carbon::parse($this->Tahun_berakhir);

        // Jika tanggal berakhir lebih kecil dari tanggal registrasi → salah input
        if ($end->lessThan($start)) {
            return "Tanggal berakhir tidak valid";
        }

        // Hitung total umur simpan
        $diff = $start->diff($end);

        return $diff->format('%y Tahun %m Bulan %d Hari');
    }

}