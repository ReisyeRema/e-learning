@extends('layouts.app')

@section('title', 'Data Absensi')


@section('content')
    <div class="row">

        @include('components.nav')

        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h4 class="mb-3">Daftar Absensi Siswa {{ $kelasData->nama_kelas }}</h4>
                        <!-- Button to trigger modal -->
                        <button type="button" class="btn btn-sm btn-primary" data-toggle="modal"
                            data-target="#addKurikulumModal">
                            Tambahkan
                        </button>
                    </div>
                    <a href="{{ route('absensi.export', ['pembelajaran_id' => $pembelajaran->id]) }}"
                        class="btn btn-sm btn-success mb-3">
                        <i class="fa fa-download"></i> Export Absensi Kelas {{ $kelasData->nama_kelas }}
                    </a>
                    <div class="table-responsive">
                        <table id="myTable" class="display expandable-table" style="width:100%">
                            <thead>
                                <tr>
                                    <th style="text-align: center">No</th>
                                    <th>Pertemuan</th>
                                    <th>Tanggal</th>
                                    <th>Jam Mulai - Jam Selesai</th>
                                    <th width="15%">
                                        <center>Aksi</center>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($absensi as $item)
                                    <tr>
                                        <td style="text-align: center"> {{ $loop->iteration }} </td>
                                        <td>{{ $item->pertemuan->judul ?? '-' }}</td>
                                        <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}</td>
                                        <td>{{ $item->jam_mulai }} - {{ $item->jam_selesai }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <a href="{{ route('detail-absensi.index', [
                                                    'mapel' => Str::slug($pembelajaran->nama_mapel, '-'),
                                                    'kelas' => Str::slug($kelasData->nama_kelas, '-'),
                                                    'tahunAjaran' => str_replace('/', '-', $pembelajaran->tahunAjaran->nama_tahun),
                                                    'semester' => Str::slug($pembelajaran->semester, '-'),
                                                    'absensi_id' => $item->id,
                                                ]) }}"
                                                    class="btn btn-sm btn-outline-primary btn-fw mr-3">Isi Absensi</a>
                                                <button type="button" class="btn btn-sm btn-outline-success btn-fw mr-3"
                                                    data-toggle="modal" data-target="#editModal{{ $item->id }}">
                                                    Edit
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-danger btn-fw"
                                                    onclick="confirmDelete({{ $item->id }})">
                                                    Delete
                                                </button>
                                                <form id="deleteForm{{ $item->id }}"
                                                    action="{{ route('absensi.destroy', $item->id) }}" method="POST"
                                                    style="display: none;">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- Modal Edit Absensi -->
                                    <div class="modal fade" id="editModal{{ $item->id }}" tabindex="-1" role="dialog"
                                        aria-hidden="true">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <form action="{{ route('absensi.update', $item->id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Edit Absensi</h5>
                                                        <button type="button" class="close"
                                                            data-dismiss="modal"><span>&times;</span></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="form-group">
                                                            <label>Tanggal</label>
                                                            <input type="date" name="tanggal"
                                                                value="{{ old('tanggal', \Carbon\Carbon::parse($item->tanggal)->format('Y-m-d')) }}"
                                                                class="form-control">
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Jam Mulai</label>
                                                            <input type="time" name="jam_mulai"
                                                                value="{{ old('jam_mulai', $item->jam_mulai) }}"
                                                                class="form-control">
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Jam Selesai</label>
                                                            <input type="time" name="jam_selesai"
                                                                value="{{ old('jam_selesai', $item->jam_selesai) }}"
                                                                class="form-control">
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-light"
                                                            data-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-primary">Perbarui</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach

                            </tbody>

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for adding new Absensi -->
    <div class="modal fade" id="addKurikulumModal" tabindex="-1" aria-labelledby="addKurikulumModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Absensi {{ $kelasData->nama_kelas }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form method="POST" action="{{ route('absensi.store', ['pembelajaran_id' => $pembelajaran->id]) }}">
                    @csrf
                    <div class="modal-body">

                        {{-- Tanggal --}}
                        <div class="form-group">
                            <label for="tanggal">Tanggal</label>
                            <input type="date" name="tanggal" class="form-control @error('tanggal') is-invalid @enderror"
                                value="{{ old('tanggal') }}">
                            @error('tanggal')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        {{-- Waktu --}}
                        <div class="form-group">
                            <label>Waktu</label>
                            <div class="d-flex">
                                <input type="time" name="jam_mulai"
                                    class="form-control mr-2 @error('jam_mulai') is-invalid @enderror"
                                    value="{{ old('jam_mulai') }}">
                                <span class="align-self-center mr-2">sampai</span>
                                <input type="time" name="jam_selesai"
                                    class="form-control @error('jam_selesai') is-invalid @enderror"
                                    value="{{ old('jam_selesai') }}">
                            </div>
                            @error('jam_mulai')
                                <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                            @enderror
                            @error('jam_selesai')
                                <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        <hr>

                        {{-- Multisesi --}}
                        <div class="form-group row align-items-center">
                            <label class="col-md-2 col-form-label font-weight-bold">Multisesi</label>
                            <div class="col-md-10">
                                <div class="form-check">
                                    <input type="hidden" name="is_multisession" value="0">
                                    <input class="form-check-input" type="checkbox" name="is_multisession"
                                        value="1" id="multiSessionCheck"
                                        {{ old('is_multisession') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="multiSessionCheck">
                                        Ulangi sesi di atas sebagai berikut:
                                    </label>
                                </div>
                            </div>
                        </div>

                        {{-- Multisesi Options --}}
                        <div id="multisession-options" style="display: none;">
                            {{-- Ulangi Pada --}}
                            <div class="form-group row align-items-center">
                                <label class="col-md-2 col-form-label">Ulangi Pada</label>
                                <div class="col-md-10 d-flex flex-wrap">
                                    @foreach (['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'] as $day)
                                        <div class="form-check mr-4">
                                            <input class="form-check-input" type="checkbox" name="ulangi_pada[]"
                                                value="{{ $day }}" id="{{ $day }}"
                                                {{ is_array(old('ulangi_pada')) && in_array($day, old('ulangi_pada')) ? 'checked' : '' }}>
                                            <label class="form-check-label ml-1 mr-5"
                                                for="{{ $day }}">{{ $day }}</label>
                                        </div>
                                    @endforeach
                                </div>
                                @error('ulangi_pada')
                                    <div class="col-md-10 offset-md-2">
                                        <span class="invalid-feedback d-block"
                                            role="alert"><strong>{{ $message }}</strong></span>
                                    </div>
                                @enderror
                            </div>

                            {{-- Ulangi Sampai Tanggal --}}
                            <div class="form-group row align-items-center">
                                <label class="col-md-2 col-form-label">Ulangi Sampai Tanggal</label>
                                <div class="col-md-10">
                                    <input type="date"
                                        class="form-control @error('ulangi_sampai') is-invalid @enderror"
                                        id="ulangi_sampai" name="ulangi_sampai" value="{{ old('ulangi_sampai') }}">
                                    @error('ulangi_sampai')
                                        <span class="invalid-feedback"
                                            role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            </div>
                        </div>


                        {{-- Hidden --}}
                        <input type="hidden" name="pembelajaran_id" value="{{ $pembelajaran->id }}">

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>



@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            function toggleMultisessionOptions() {
                if ($('#multiSessionCheck').is(':checked')) {
                    $('#multisession-options').slideDown();
                } else {
                    $('#multisession-options').slideUp();
                }
            }

            // Cek status saat halaman dimuat
            toggleMultisessionOptions();

            // Toggle saat checkbox diubah
            $('#multiSessionCheck').change(function() {
                toggleMultisessionOptions();
            });
        });
    </script>
@endpush
