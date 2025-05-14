@extends('layouts.app')

@section('title', 'Data Pembelajaran')

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
                                                    <div class="d-flex align-items-center">
                                                        <a href="{{ route('pembelajaran.edit', $item->id) }}" class="btn btn-sm btn-outline-success btn-fw mr-3">Edit</a>

                                                        <form id="deleteForm{{ $item->id }}" action="{{ route('pembelajaran.destroy', $item->id) }}" method="POST" style="display: inline;">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="button" class="btn btn-sm btn-outline-danger btn-fw" onclick="confirmDelete('{{ $item->id }}')">Delete</button>
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
