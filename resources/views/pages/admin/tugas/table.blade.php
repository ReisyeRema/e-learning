<h3 style="text-align: center;">{{ $tugas->judul }}</h3>
<br>
<p><strong>Mata Pelajaran:</strong> {{ $pembelajaran->nama_mapel }}</p>
<p><strong>Kelas:</strong> {{ $pembelajaran->kelas->nama_kelas ?? '-' }} TA. {{ $pembelajaran->tahunAjaran->nama_tahun }} ( {{ $pembelajaran->semester }} )</p>
<p><strong>Pertemuan:</strong> {{ $pertemuan->judul ?? '-' }}</p>
<br>

<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Nama Siswa</th>
            <th>Status</th>
            <th>Skor</th>
        </tr>
    </thead>
    <tbody>
        @foreach($siswaList as $index => $siswa)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $siswa['nama'] }}</td>
                <td>{{ $siswa['status'] }}</td>
                <td>{{ $siswa['skor'] }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
