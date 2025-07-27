@extends('layouts.app')

@section('title', 'Detail Absensi')

@section('content')
    <div class="row">

        @include('components.nav')

        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h4 class="mb-3">Daftar Absensi Siswa {{ $absensi->pembelajaran->kelas->nama_kelas }} -
                            {{ $absensi->pertemuan->judul }}</h4>
                        <!-- Button to trigger modal -->
                    </div>
                    <form method="POST" action="{{ route('detail-absensi.storeOrUpdate') }}">
                        @csrf
                        <input type="hidden" name="absensi_id" value="{{ $absensi->id }}">

                        <div class="table-responsive">
                            <table id="myTable" class="display expandable-table" style="width:100%">
                                <thead>
                                    <tr>
                                        <th style="text-align: center" width="3%">No</th>
                                        <th>Siswa</th>
                                        <th width="3%">Hadir</th>
                                        <th width="3%">Izin</th>
                                        <th width="3%">Sakit</th>
                                        <th width="3%">Alfa</th>
                                        <th width="13%" style="text-align: center">Dokumen</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($enrolledSiswa as $index => $enroll)
                                        @php
                                            $siswa = $enroll->siswa;
                                            $siswaId = $siswa->id;
                                            $detail = $detailAbsensi[$siswaId] ?? null;
                                        @endphp
                                        <tr>
                                            <td style="text-align: center">{{ $index + 1 }}</td>
                                            <td>{{ $siswa->name }}</td>
                                            @foreach (['H' => 'Hadir', 'I' => 'Izin', 'S' => 'Sakit', 'A' => 'Alfa'] as $key => $label)
                                                <td>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input absensi-radio" type="radio"
                                                            name="keterangan[{{ $siswaId }}]"
                                                            value="{{ $key }}" data-siswa-id="{{ $siswaId }}"
                                                            data-absensi-id="{{ $absensi->id }}"
                                                            {{ $detail && $detail->keterangan[0] === $key ? 'checked' : '' }}>
                                                    </div>
                                                </td>
                                            @endforeach
                                            <td style="text-align: center">
                                                @if ($detail && in_array($detail->keterangan, ['Izin', 'Sakit']) && $detail->surat)
                                                    <a href="https://drive.google.com/file/d/{{ $detail->surat }}/view"
                                                        target="_self" class="btn btn-sm btn-outline-success">
                                                        <i class="fas fa-file-alt me-1"></i> Lihat Surat
                                                    </a>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        document.querySelectorAll('.absensi-radio').forEach(radio => {
            radio.addEventListener('change', function() {
                const siswaId = this.dataset.siswaId;
                const absensiId = this.dataset.absensiId;
                const keterangan = this.value;

                fetch("{{ route('detail-absensi.storeOrUpdate') }}", {
                        method: "POST",
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        },
                        body: JSON.stringify({
                            absensi_id: absensiId,
                            keterangan: {
                                [siswaId]: keterangan
                            }
                        })
                    })
                    .then(response => {
                        if (!response.ok) {
                            console.error('Gagal menyimpan');
                        }
                    })
                    .catch(error => {
                        console.error('Terjadi kesalahan saat menyimpan:', error);
                    });
            });
        });
    </script>
@endpush
