<style>
    .thead-green th {
        background-color: #198754;
        color: white;
        border-color: white;
    }
</style>

<div>
    <h6 class="fw-bold">Absensi Siswa</h6>

    <!-- Tombol Absensi -->
    <div class="mb-3">
        @if ($absensiAktif && $waktuAbsensiBelumDimulai)
            <button class="btn btn-warning" disabled>
                Waktu Absensi Belum Dimulai
            </button>
        @elseif ($absensiAktif && $absensiMasihAktif)
            @if ($detailAbsensi)
                <button class="btn btn-light" disabled>
                    Sudah Absen Hari Ini ({{ $detailAbsensi->keterangan }})
                </button>
            @else
                <!-- Tombol untuk memicu modal -->
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalAbsensi">
                    Lakukan Absensi
                </button>
            @endif
        @elseif ($absensiAktif && !$absensiMasihAktif)
            <button class="btn btn-secondary" disabled>Waktu Absensi Telah Berakhir</button>
        @else
            <button class="btn btn-light" disabled>Belum Tersedia</button>
        @endif
    </div>



    <!-- Riwayat Absensi -->
    <h6 class="fw-bold mt-4">Riwayat Absensi</h6>
    <div class="table-responsive">
        <table class="table table-bordered align-middle text-center shadow-sm rounded" style="border-color: #198754;">
            <thead class="thead-green">
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Jam Absen</th>
                    <th>Status</th>
                    <th>Dokumen</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($riwayatAbsensi as $i => $absen)
                    @php
                        $tanggal = $absen->absensi->tanggal;
                        $jamAbsen = \Carbon\Carbon::parse($absen->created_at)->format('H:i'); 
                    @endphp
                    <tr style="background-color: {{ $absen->keterangan == 'Hadir' ? '#e9f7ef' : '#fff3f3' }};">
                        <td>{{ $i + 1 }}</td>
                        <td>{{ \Carbon\Carbon::parse($tanggal)->translatedFormat('d M Y') }}</td>
                        <td>{{ $jamAbsen }}</td>
                        <td>
                            @php
                                switch ($absen->keterangan) {
                                    case 'Hadir':
                                        $badgeClass = 'bg-success'; 
                                        break;
                                    case 'Izin':
                                        $badgeClass = 'bg-warning text-dark'; 
                                        break;
                                    case 'Sakit':
                                        $badgeClass = 'bg-info text-dark'; 
                                        break;
                                    case 'Alfa':
                                        $badgeClass = 'bg-danger'; 
                                        break;
                                    default:
                                        $badgeClass = 'bg-secondary';
                                }
                            @endphp
                            <span class="badge {{ $badgeClass }}">
                                {{ $absen->keterangan }}
                            </span>
                        </td>

                        <td>
                            @if (in_array($absen->keterangan, ['Izin', 'Sakit']) && $absen->surat)
                                <a href="https://drive.google.com/file/d/{{ $absen->surat }}/view" target="_self"
                                    class="btn btn-sm btn-outline-success">
                                    <i class="fas fa-file-alt me-1"></i> Lihat Surat
                                </a>
                            @else
                                -
                            @endif
                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="6">Belum ada riwayat absensi.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
