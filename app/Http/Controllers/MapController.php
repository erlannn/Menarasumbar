<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kecamatan;
use App\Models\bts;

class MapController extends Controller
{
    public function index()
    {
        $kecamatans = Kecamatan::all();
        return view('peta', compact('kecamatans'));
    }

    public function lanjutan($kode_kecamatan)
    {
        // Ambil semua kecamatan untuk dropdown
        $kecamatans = Kecamatan::all();
        // Ambil data kecamatan aktif
        $kecamatan = Kecamatan::where('Kode_kecamatan', $kode_kecamatan)->firstOrFail();

        // Jika kecamatan bernama Padang → ambil semua BTS
        if (strtolower($kecamatan->Nama_kecamatan) === 'padang') {
            $btsList = BTS::join('perangkatjaringan', 'bts.Kode_perangkat_jaringan', '=', 'perangkatjaringan.Kode_perangkat_jaringan')
                ->select(
                    'bts.nama_BTS',
                    'bts.Longitude',
                    'bts.Latitude',
                    'bts.alamat',
                    'bts.Kode_operator',
                    'bts.Kode_perangkat_jaringan',
                    'bts.Kode_kecamatan',
                    'perangkatjaringan.jenis_jaringan'
                )
                ->get();
        } else {
            // Default: tampilkan BTS hanya di kecamatan terpilih
            $btsList = BTS::join('perangkatjaringan', 'bts.Kode_perangkat_jaringan', '=', 'perangkatjaringan.Kode_perangkat_jaringan')
                ->where('Kode_kecamatan', $kode_kecamatan)
                ->select(
                    'bts.nama_BTS',
                    'bts.Longitude',
                    'bts.Latitude',
                    'bts.alamat',
                    'bts.Kode_operator',
                    'bts.Kode_perangkat_jaringan',
                    'bts.Kode_kecamatan',
                    'perangkatjaringan.jenis_jaringan'
                )
                ->get();
        }

        // Path GeoJSON per kecamatan
        $geojsonPath = asset("geojson/{$kode_kecamatan}.json");

        // Mapping kode operator ke nama operator
        $operatorNames = [
            'OP1' => 'Telkomsel',
            'OP2' => 'Indosat',
        ];

        // Hitung jumlah BTS per operator
        $operatorCounts = $btsList->groupBy('Kode_operator')->map->count();

        // Konversi ke nama operator
        $operatorCountsReadable = $operatorCounts->mapWithKeys(function($count, $code) use ($operatorNames) {
            $name = $operatorNames[$code] ?? $code;
            return [$name => $count];
        });

        // Kirim semua variabel ke view
        return view('petalanjutan', compact(
            'kecamatan',
            'btsList',
            'geojsonPath',
            'operatorCountsReadable',
            'kecamatans'
        ));
    }
}
