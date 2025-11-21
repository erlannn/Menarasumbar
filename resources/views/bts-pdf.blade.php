<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Data BTS</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #333; padding: 6px; text-align: left; }
        th { background-color: #f2f2f2; }
        h2 { text-align: center; margin-bottom: 10px; }
    </style>
</head>
<body>
    <h2>Data BTS</h2>
    <table>
        <thead>
            <tr>
                <th>Nama BTS</th>
                <th>Operator</th>
                <th>Perangkat</th>
                <th>Jenis Jaringan</th>
                <th>Jangkauan</th>
                <th>Longitude</th>
                <th>Latitude</th>
                <th>Tahun Registrasi</th>
                <th>Tahun Berakhir</th>
                <th>Sisa waktu Kadaluarsa</th>
                <th>Alamat</th>
                <th>Kecamatan</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($bts as $row)
            <tr>
                <td>{{ $row->nama_bts }}</td>
                <td>{{ $row->operator }}</td>
                <td>{{ $row->perangkat }}</td>
                <td>{{ $row->jenis_jaringan }}</td>
                <td>{{ $row->jangkauan_sinyal }} KM</td>
                <td>{{ $row->Longitude }}</td>
                <td>{{ $row->Latitude }}</td>
                <td class="px-1">{{ \Carbon\Carbon::parse($row->Tahun_registrasi)->format('d-m-Y') }}</td>
                <td class="px-1">{{ \Carbon\Carbon::parse($row->Tahun_berakhir)->format('d-m-Y') }}</td>
                <td>{{ $row->sisa_waktu }}
                <td>{{ $row->alamat }}</td>
                <td>{{ $row->kecamatan }}</td>
            </tr>
            @empty
            <p class=" flex justify-center text-center">Tidak ada Data BTS</p>
            @endforelse
        </tbody>
    </table>
</body>
</html>
