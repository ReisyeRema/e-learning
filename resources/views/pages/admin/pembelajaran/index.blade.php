@extends('layouts.app')

@section('title', 'Data Pembelajaran')

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
                                <p class="card-title">Daftar Data Pembelajaran</p>
                                <!-- Button to trigger modal -->
                                <a href="{{ route('pembelajaran.create') }}" class="btn btn-primary">Tambah Data</a>
                            </div>
                            <!-- Table Section -->
                            <div class="table-responsive">

                                <table id="myTable" class="display expandable-table" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th style="text-align: center">No</th>
                                            <th>Cover</th>
                                            <th>Mata Pelajaran</th>
                                            <th>Kelas - Tahun Ajaran - Semester</th>
                                            <th>Guru</th>
                                            <th>Status Aktif</th>
                                            <th width="15%">
                                                <center>Aksi</center>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($pembelajaran as $item)
                                            <tr>
                                                <td style="text-align: center"> {{ $loop->iteration }} </td>
                                                <td>
                                                    @if ($item->cover)
                                                    <img src="{{ asset('storage/covers/' . $item->cover) }}"
                                                        alt="Foto" width="50">
                                                    @else
                                                        No Image
                                                    @endif
                                                </td>
                                                <td> {{ $item->nama_mapel }} </td>
                                                <td> {{ optional($item->kelas)->nama_kelas}} - {{ optional($item->tahunAjaran)->nama_tahun}} - {{ $item->semester }}</td>
                                                <td> {{ optional($item->guru)->name}} </td>
                                                <td>
                                                    <!-- Radio Aktif -->
                                                    <div class="center-toggle">
                                                        <form action="{{ route('pembelajaran.toggleAktif', $item->id) }}" method="POST"
                                                            style="display: inline;">
                                                            @csrf
                                                            @method('PUT')
                                                            <label class="switch">
                                                                <input type="checkbox" name="aktif" onchange="this.form.submit()"
                                                                    {{ $item->aktif ? 'checked' : '' }}>
                                                                <span class="slider"></span>
                                                            </label>
                                                        </form>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <a href="{{ route('pembelajaran.edit', $item->id) }}" class="btn btn-sm btn-outline-success btn-fw mr-3">Edit</a>

                                                        <form id="deleteForm{{ $item->id }}" action="{{ route('pembelajaran.destroy', $item->id) }}" method="POST" style="display: inline;">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="button" class="btn btn-sm btn-outline-danger btn-fw mt-3" onclick="confirmDelete('{{ $item->id }}')">Delete</button>
                                                        </form>

                                                    </div>
                                                </td>
                                            </tr>
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
@endsection
