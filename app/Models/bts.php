<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class bts extends Model
{
    use HasFactory;
    protected $table = 'bts';
    
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

    public function perangkatJaringan()
    {
        return $this->belongsTo(PerangkatJaringan::class, 'Kode_perangkat_jaringan', 'Kode_perangkat_jaringan');
    }

    public function getSisaWaktuAttribute()
    {
        // Cek ketersediaan data tanggal
        if (!$this->Tahun_registrasi || !$this->Tahun_berakhir) {
            return null; // Atau pesan error lainnya
        }

        // Parse format MySQL (Y-m-d)
        $start = Carbon::parse($this->Tahun_registrasi);
        $end   = Carbon::parse($this->Tahun_berakhir);
        $now   = Carbon::now(); // Dapatkan tanggal dan waktu hari ini

        // 1. Cek validitas input tanggal
        if ($end->lessThan($start)) {
            return "Tanggal berakhir tidak valid"; // Tanggal berakhir lebih kecil dari tanggal registrasi
        }

        // 2. Cek status Kadaluarsa (Apakah tanggal berakhir SUDAH berlalu dari hari ini)
        if ($end->isPast()) {
            return "Kadaluarsa";
        }

        // 3. Hitung Sisa Waktu (Durasi dari hari ini sampai tanggal berakhir)
        // $diff akan berupa CarbonInterval
        $diff = $now->diff($end); 

        // Format output sisa waktu yang tersisa
        // Gunakan diffForHumans() jika ingin output lebih ringkas (misal: "3 bulan dari sekarang")
        // Jika ingin format spesifik:
        $years  = $diff->y;
        $months = $diff->m;
        $days   = $diff->d;

        $parts = [];
        if ($years > 0) {
            $parts[] = "$years Tahun";
        }
        if ($months > 0) {
            $parts[] = "$months Bulan";
        }
        if ($days > 0) {
            $parts[] = "$days Hari";
        }
        
        // Jika sisa waktu kurang dari 1 hari, tampilkan jam/menit jika perlu,
        // atau cukup tampilkan "Hari Ini" atau "Akan Kadaluarsa"
        if (empty($parts)) {
            // Jika selisih kurang dari 1 hari, tetapi belum expired
            return $end->isToday() ? "Berakhir Hari Ini" : "Segera Kadaluarsa";
        }

        return implode(' ', $parts);
    }
}