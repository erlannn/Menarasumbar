<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\bts;
use App\Models\operator;
use App\Models\perangkatjaringan;
use App\Models\kecamatan;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class BTSController extends Controller
{
    // =========================
    // HALAMAN USER (Data BTS)
    // =========================
    public function index()
    {
        $bts = bts::join('kecamatan', 'bts.Kode_kecamatan', '=', 'kecamatan.Kode_kecamatan')
            ->join('operator', 'bts.Kode_operator', '=', 'operator.Kode_operator')
            ->join('perangkatjaringan', 'bts.Kode_perangkat_jaringan', '=', 'perangkatjaringan.Kode_perangkat_jaringan')
            ->select(
                'bts.*',  
                'bts.nama_BTS as nama_bts',
                'operator.Operator as operator',
                'perangkatjaringan.Jangkauan_sinyal as jangkauan_sinyal',
                'kecamatan.Nama_kecamatan as kecamatan'
            )
            ->get();

        // Dropdown untuk filter data
        $kecamatan = $bts->pluck('kecamatan')->unique();
        $operator = $bts->pluck('operator')->unique();
        $tahun = $bts->pluck('Tahun_registrasi')->map(function($t){
            return Carbon::parse($t)->format('Y');
        })->unique();

        return view('databts', compact('bts', 'kecamatan', 'operator', 'tahun'));
    }

    // =========================
    // HALAMAN ADMIN (Kelola Data BTS)
    // =========================
    public function kelolaIndex(Request $request)
    {
        $query = bts::join('kecamatan', 'bts.Kode_kecamatan', '=', 'kecamatan.Kode_kecamatan')
            ->join('operator', 'bts.Kode_operator', '=', 'operator.Kode_operator')
            ->join('perangkatjaringan', 'bts.Kode_perangkat_jaringan', '=', 'perangkatjaringan.Kode_perangkat_jaringan')
            ->select(
                'bts.*',
                'bts.nama_BTS as nama_bts',
                'operator.Operator as operator',
                'perangkatjaringan.Perangkat as perangkat',
                'perangkatjaringan.Jenis_jaringan as jenis_jaringan',
                'perangkatjaringan.Jangkauan_sinyal as jangkauan_sinyal',
                'kecamatan.Nama_kecamatan as kecamatan'
            );

        // ---------------------------
        // FILTER
        // ---------------------------

        if ($request->filled('kecamatan')) {
            $query->where('kecamatan.Nama_kecamatan', $request->kecamatan);
        }

        if ($request->filled('operator')) {
            $query->where('operator.Operator', $request->operator);
        }

        if ($request->filled('tahun_registrasi')) {
            $query->whereYear('bts.Tahun_registrasi', $request->tahun_registrasi);
        }

        // Ambil hasil akhir
        $bts = $query->paginate(10);

        // ---------------------------
        // DATA DROPDOWN
        // ---------------------------

        $kecamatan = bts::join('kecamatan','bts.Kode_kecamatan','=','kecamatan.Kode_kecamatan')
            ->pluck('kecamatan.Nama_kecamatan')
            ->unique();

        $operator = bts::join('operator','bts.Kode_operator','=','operator.Kode_operator')
            ->pluck('operator.Operator')
            ->unique();

        // Tahun registrasi
        $tahun_registrasi = bts::pluck('Tahun_registrasi')
            ->map(function($t){
                return \Carbon\Carbon::parse($t)->format('Y');
            })
            ->unique()
            ->sort();

        return view('keloladatabts', compact(
            'bts',
            'kecamatan',
            'operator',
            'tahun_registrasi',
        ));
    }


    public function create()
    {
        $operator = operator::all();
        $perangkat = perangkatjaringan::all();
        $kecamatan = kecamatan::all();

        return view('tambahdatabts', compact('operator','perangkat','kecamatan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_BTS' => 'required|unique:bts,nama_BTS',
            'Longitude' => 'required|numeric',
            'Latitude' => 'required|numeric',
            'Tahun_registrasi' => 'required|date',
            'Tahun_berakhir' => 'required|date',
            'alamat' => 'required|string|max:500',
            'Kode_operator' => 'required',
            'Kode_perangkat_jaringan' => 'required',
            'Kode_kecamatan' => 'required',
        ],
        [
            'required' => ':attribute wajib diisi.',
            'numeric' => ':attribute harus berupa angka.',
            'date' => ':attribute harus berupa tanggal.',
            'exists' => ':attribute tidak valid.',
            'nama_BTS.unique' => 'Data BTS dengan nama ini sudah ada.',
        ], [
            'nama_BTS' => 'Nama BTS',
            'Longitude' => 'Longitude',
            'Latitude' => 'Latitude',
            'Tahun_registrasi' => 'Tahun Registrasi',
            'Tahun_berakhir' => 'Tahun Berakhir',
            'alamat' => 'Alamat',
            'Kode_operator' => 'Operator',
            'Kode_perangkat_jaringan' => 'Perangkat Jaringan',
            'Kode_kecamatan' => 'Kecamatan',
        ]);

        bts::create($request->all());

        return redirect()->route('kelola-bts.index')->with('success','Data BTS berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $bts = bts::findOrFail($id);
        $operator = operator::all();
        $perangkat = perangkatjaringan::all();
        $kecamatan = kecamatan::all();

        return view('editdatabts', compact('bts','operator','perangkat','kecamatan'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_BTS' => 'required|unique:bts,nama_BTS',
            'Longitude' => 'required',
            'Latitude' => 'required',
            'Tahun_registrasi' => 'required|date',
            'Tahun_berakhir' => 'required|date',
            'alamat' => 'required',
            'Kode_operator' => 'required',
            'Kode_perangkat_jaringan' => 'required',
            'Kode_kecamatan' => 'required',
        ],
        [
            'required' => ':attribute wajib diisi.',
            'numeric' => ':attribute harus berupa angka.',
            'date' => ':attribute harus berupa tanggal.',
            'exists' => ':attribute tidak valid.',
            'nama_BTS.unique' => 'Data BTS dengan nama ini sudah ada.',
        ], 
        [
            'nama_BTS' => 'Nama BTS',
            'Longitude' => 'Longitude',
            'Latitude' => 'Latitude',
            'Tahun_registrasi' => 'Tahun Registrasi',
            'Tahun_berakhir' => 'Tahun Berakhir',
            'alamat' => 'Alamat',
            'Kode_operator' => 'Operator',
            'Kode_perangkat_jaringan' => 'Perangkat Jaringan',
            'Kode_kecamatan' => 'Kecamatan',
        ]);

        $bts = bts::findOrFail($id);
        $bts->update($request->all());

        return redirect()->route('kelola-bts.index')->with('success','Data BTS berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $bts = bts::findOrFail($id);
        $bts->delete();

        return redirect()->route('kelola-bts.index')->with('success','Data BTS berhasil dihapus!');
    }

    public function exportPdf(Request $request)
    {
        $query = bts::join('kecamatan', 'bts.Kode_kecamatan', '=', 'kecamatan.Kode_kecamatan')
            ->join('operator', 'bts.Kode_operator', '=', 'operator.Kode_operator')
            ->join('perangkatjaringan', 'bts.Kode_perangkat_jaringan', '=', 'perangkatjaringan.Kode_perangkat_jaringan')
            ->select(
                'bts.id',
                'bts.nama_BTS as nama_bts',
                'operator.Operator as operator',
                'perangkatjaringan.Perangkat as perangkat',
                'perangkatjaringan.Jenis_jaringan as jenis_jaringan',
                'perangkatjaringan.Jangkauan_sinyal as jangkauan_sinyal',
                'bts.Longitude',
                'bts.Latitude',
                'bts.Tahun_registrasi',
                'bts.Tahun_berakhir',
                'bts.alamat',
                'kecamatan.Nama_kecamatan as kecamatan'
            );

        // Terapkan filter (sama kayak kelolaIndex)
        if ($request->filled('kecamatan')) {
            $query->where('kecamatan.Nama_kecamatan', $request->kecamatan);
        }
        if ($request->filled('operator')) {
            $query->where('operator.Operator', $request->operator);
        }
        if ($request->filled('tahun_registrasi')) {
            $query->whereYear('bts.Tahun_registrasi', $request->tahun_registrasi);
        }

        $bts = $query->get();

        $pdf = Pdf::loadView('bts-pdf', compact('bts'))->setPaper('a4', 'landscape');
        return $pdf->download('data-bts.pdf');
    }
}
