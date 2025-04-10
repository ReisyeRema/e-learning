@extends('layouts.app')

@section('title', 'Data Guru')

@section('content')
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <!-- Button Section -->
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <p class="card-title">Daftar Data Guru</p>
                                <!-- Button to trigger modal -->
                                <a href="{{ route('guru.create') }}" class="btn btn-primary">Tambah Data</a>
                            </div>
                            <!-- Table Section -->
                            <div class="table-responsive">

                                <a href="{{ route('guru.export') }}" class="btn btn-sm btn-inverse-success btn-icon-text">
                                    <i class="ti-download btn-icon-prepend"></i>                                                    
                                    Download XMLS
                                </a>

                                <table id="myTable" class="display expandable-table" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th style="text-align: center">No</th>
                                            <th>Nama</th>
                                            <th>Username</th>
                                            <th>Email</th>
                                            <th>Foto</th>
                                            <th width="15%">
                                                <center>Aksi</center>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($users as $user)
                                            <tr>
                                                <td style="text-align: center"> {{ $loop->iteration }} </td>
                                                <td> {{ $user->name }} </td>
                                                <td> {{ $user->username }} </td>
                                                <td> {{ $user->email }} </td>
                                                <td>
                                                    @if ($user->foto) 
                                                        <img src="{{ asset('storage/foto_user/' . $user->foto) }}" alt="Foto" width="50">
                                                    @else
                                                        No Image
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <a href="{{ route('guru.edit', $user->guru->id) }}" class="btn btn-sm btn-outline-success btn-fw mr-3">Edit</a>

                                                        <form id="deleteForm{{ $user->guru->id }}" action="{{ route('guru.destroy', $user->guru->id) }}" method="POST" style="display: inline;">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="button" class="btn btn-sm btn-outline-danger btn-fw" onclick="confirmDelete('{{ $user->guru->id }}')">Delete</button>
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
