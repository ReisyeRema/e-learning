@extends('layouts.app')

@section('title', 'Data Wali Kelas')

<style>
    .switch {
        position: relative;
        display: inline-block;
        width: 50px;
        height: 26px;
    }

    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        transition: .4s;
        border-radius: 34px;
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 20px;
        width: 20px;
        left: 3px;
        bottom: 3px;
        background-color: white;
        transition: .4s;
        border-radius: 50%;
    }

    input:checked+.slider {
        background-color: #4caf50;
    }

    input:checked+.slider:before {
        transform: translateX(24px);
    }

    .center-toggle {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100%;
        margin-top: 15px;
    }

    .center-button {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100%;
    }
</style>

@section('content')
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <!-- Button Section -->
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <p class="card-title">Daftar Wali Kelas</p>
                                <!-- Button to trigger modal -->
                                <button type="button" class="btn btn-primary" data-toggle="modal"
                                    data-target="#addKelasModal">
                                    Tambah Data
                                </button>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <form method="GET" action="{{ route('wali-kelas.index') }}" id="filterExportForm">
                                        <div class="form-row align-items-center">
                                            <!-- Export Button -->
                                            <div class="col-md-9">
                                                <button type="button" id="exportBtn"
                                                    class="btn btn-sm btn-inverse-success btn-icon-text">
                                                    <i class="ti-download btn-icon-prepend"></i>
                                                    Export XLSX
                                                </button>
                                            </div>

                                            <!-- Tahun Ajaran Dropdown -->
                                            <div class="col-auto">
                                                <select name="tahun_ajaran_id" class="form-control form-control-sm"
                                                    id="tahunAjaranSelect">
                                                    <option value="">-- Semua Tahun Ajaran --</option>
                                                    @foreach ($tahunAjaran as $t)
                                                        <option value="{{ $t->id }}"
                                                            {{ request('tahun_ajaran_id') == $t->id ? 'selected' : '' }}>
                                                            {{ $t->nama_tahun }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <!-- Filter Button -->
                                            <div class="col-auto">
                                                <button type="submit" class="btn btn-sm btn-outline-info">
                                                    <i class="ti-filter btn-icon-append"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>


                            <!-- Table Section -->
                            <div class="table-responsive">
                                <table id="myTable" class="display expandable-table" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th style="text-align: center">No</th>
                                            <th>Nama Guru</th>
                                            <th>Nama Kelas</th>
                                            <th>Tahun Ajaran</th>
                                            <th>
                                                <center>Status</center>
                                            </th>
                                            <th width="15%">
                                                <center>Aksi</center>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($waliKelas as $item)
                                            <tr>
                                                <td style="text-align: center"> {{ $loop->iteration }} </td>
                                                <td> {{ optional($item->guru)->name }} </td>
                                                <td> {{ optional($item->kelas)->nama_kelas }} </td>
                                                <td>{{ optional($item->tahunAjaran)->nama_tahun }}</td>
                                                <td>
                                                    <!-- Radio Aktif -->
                                                    <div class="center-toggle">
                                                        <form action="{{ route('wali-kelas.toggleAktif', $item->id) }}"
                                                            method="POST" style="display: inline;">
                                                            @csrf
                                                            @method('PUT')
                                                            <label class="switch">
                                                                <input type="checkbox" name="aktif"
                                                                    onchange="this.form.submit()"
                                                                    {{ $item->aktif ? 'checked' : '' }}>
                                                                <span class="slider"></span>
                                                            </label>
                                                        </form>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <!-- Button to trigger modal edit -->
                                                        <button type="button"
                                                            class="btn btn-sm btn-outline-success btn-fw mr-3"
                                                            data-toggle="modal"
                                                            data-target="#editKelasModal{{ $item->id }}">
                                                            Edit
                                                        </button>
                                                        <button type="button" class="btn btn-sm btn-outline-danger btn-fw"
                                                            onclick="confirmDelete({{ $item->id }})">
                                                            Delete
                                                        </button>
                                                        <form id="deleteForm{{ $item->id }}"
                                                            action="{{ route('wali-kelas.destroy', $item->id) }}"
                                                            method="POST" style="display: none;">
                                                            @csrf
                                                            @method('DELETE')
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>

                                            <!-- Modal for Editing Kelas -->
                                            <div class="modal fade" id="editKelasModal{{ $item->id }}" tabindex="-1"
                                                aria-labelledby="editKelasModalLabel{{ $item->id }}"
                                                aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title"
                                                                id="editKelasModalLabel{{ $item->id }}">
                                                                Edit Kelas</h5>
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <form action="{{ route('wali-kelas.update', $item->id) }}"
                                                            method="POST">
                                                            @csrf
                                                            @method('PUT')
                                                            <div class="modal-body">
                                                                <div class="form-group">
                                                                    <label for="exampleSelectKelas">Guru <span
                                                                            class="text-danger">*</span></label>
                                                                    <select name="guru_id"
                                                                        class="form-control @error('guru_id') is-invalid @enderror"
                                                                        id="exampleSelectguru">
                                                                        <option value="">Pilih guru</option>
                                                                        @foreach ($guru as $g)
                                                                            <option value="{{ $g->id }}"
                                                                                {{ old('guru_id', $item->guru_id) == $g->id ? 'selected' : '' }}>
                                                                                {{ $g->name }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                    @error('guru_id')
                                                                        <span class="invalid-feedback" role="alert">
                                                                            <strong>{{ $message }}</strong>
                                                                        </span>
                                                                    @enderror
                                                                </div>

                                                                <div class="form-group">
                                                                    <label for="exampleSelectKelas">Kelas <span
                                                                            class="text-danger">*</span></label>
                                                                    <select name="kelas_id"
                                                                        class="form-control @error('kelas_id') is-invalid @enderror"
                                                                        id="exampleSelectKelas">
                                                                        <option value="">Pilih Kelas</option>
                                                                        @foreach ($kelas as $k)
                                                                            <option value="{{ $k->id }}"
                                                                                {{ old('kelas_id', $item->kelas_id) == $k->id ? 'selected' : '' }}>
                                                                                {{ $k->nama_kelas }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                    @error('kelas_id')
                                                                        <span class="invalid-feedback" role="alert">
                                                                            <strong>{{ $message }}</strong>
                                                                        </span>
                                                                    @enderror
                                                                </div>

                                                                <div class="form-group">
                                                                    <label for="exampleSelectKelas">Tahun Ajaran <span
                                                                            class="text-danger">*</span></label>
                                                                    <select name="tahun_ajaran_id"
                                                                        class="form-control @error('tahun_ajaran_id') is-invalid @enderror"
                                                                        id="exampleSelectKelas">
                                                                        <option value="">Pilih Kelas</option>
                                                                        @foreach ($tahunAjaran as $t)
                                                                            <option value="{{ $t->id }}"
                                                                                {{ old('tahun_ajaran_id', $item->tahun_ajaran_id) == $t->id ? 'selected' : '' }}>
                                                                                {{ $t->nama_tahun }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                    @error('tahun_ajaran_id')
                                                                        <span class="invalid-feedback" role="alert">
                                                                            <strong>{{ $message }}</strong>
                                                                        </span>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-light"
                                                                    data-dismiss="modal">Close</button>
                                                                <button type="submit" class="btn btn-primary">Save
                                                                    changes</button>
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
        </div>
    </div>

    <!-- Modal for adding new kelas -->
    <div class="modal fade" id="addKelasModal" tabindex="-1" aria-labelledby="addKelasModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addKelasModalLabel">Tambah Kelas</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('wali-kelas.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="exampleSelectKelas">Guru <span class="text-danger">*</span></label>
                            <select name="guru_id" class="form-control @error('guru_id') is-invalid @enderror"
                                id="exampleSelectguru">
                                <option value="">Pilih guru</option>
                                @foreach ($guru as $item)
                                    <option value="{{ $item->id }}"
                                        {{ old('guru_id') == $item->id ? 'selected' : '' }}>
                                        {{ $item->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('guru_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="exampleSelectKelas">Kelas <span class="text-danger">*</span></label>
                            <select name="kelas_id" class="form-control @error('kelas_id') is-invalid @enderror"
                                id="exampleSelectKelas">
                                <option value="">Pilih Kelas</option>
                                @foreach ($kelas as $item)
                                    <option value="{{ $item->id }}"
                                        {{ old('kelas_id') == $item->id ? 'selected' : '' }}>
                                        {{ $item->nama_kelas }}
                                    </option>
                                @endforeach
                            </select>
                            @error('kelas_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="exampleSelectKelas">Tahun Ajaran <span class="text-danger">*</span></label>
                            <select name="tahun_ajaran_id"
                                class="form-control @error('tahun_ajaran_id') is-invalid @enderror"
                                id="exampleSelectKelas">
                                <option value="">Pilih Tahun Ajaran</option>
                                @foreach ($tahunAjaran as $item)
                                    <option value="{{ $item->id }}"
                                        {{ old('tahun_ajaran_id') == $item->id ? 'selected' : '' }}>
                                        {{ $item->nama_tahun }}
                                    </option>
                                @endforeach
                            </select>
                            @error('tahun_ajaran_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection


@push('scripts')
    <script>
        document.getElementById("exportBtn").addEventListener("click", function() {
            const tahunId = document.getElementById("tahunAjaranSelect").value;
            const url = "{{ route('wali-kelas.export') }}?tahun_ajaran_id=" + tahunId;
            window.location.href = url;
        });
    </script>
@endpush
