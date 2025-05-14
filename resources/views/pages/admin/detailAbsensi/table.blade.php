<h3 style="text-align: center;">ABSENSI</h3>
<br>
<p><strong>Mata Pelajaran:</strong> {{ $pembelajaran->nama_mapel }}</p>
<p><strong>Kelas:</strong> {{ $pembelajaran->kelas->nama_kelas ?? '-' }}</p>
<br>

<table border="1" cellspacing="0" cellpadding="5">
    <thead>
        <tr>
            <th rowspan="3">No</th>
            <th rowspan="3">Nama</th>
            <th colspan="{{ $pertemuanList->count() }}" style="text-align: center;">Pertemuan</th>
            <th colspan="4" style="text-align: center;">Rekap</th>
        </tr>
        <tr>
            @foreach ($pertemuanList as $index => $pertemuan)
                <th>{{ $index + 1 }}</th>
            @endforeach
            <th rowspan="2">Hadir</th>
            <th rowspan="2">Izin</th>
            <th rowspan="2">Sakit</th>
            <th rowspan="2">Alfa</th>
        </tr>
        <tr>
            @foreach ($pertemuanList as $pertemuan)
                <th style="font-size: 7px;">
                    {{ \Carbon\Carbon::parse($pertemuan->tanggal)->format('d/m/Y') }}
                </th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach ($siswaList as $index => $siswa)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $siswa['nama'] }}</td>
                @foreach ($siswa['pertemuan'] as $keterangan)
                    <td>
                        @if ($keterangan === 'Hadir')
                            &#10004; {{-- ✔ centang hitam --}}
                        @else
                            {{ substr($keterangan, 0, 1) }}
                        @endif
                    </td>
                @endforeach
                <td>{{ $siswa['rekap']['Hadir'] }}</td>
                <td>{{ $siswa['rekap']['Izin'] }}</td>
                <td>{{ $siswa['rekap']['Sakit'] }}</td>
                <td>{{ $siswa['rekap']['Alfa'] }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
