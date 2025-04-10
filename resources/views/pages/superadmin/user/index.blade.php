@extends('layouts.app')

@section('title', 'Data User')

@section('content')
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <!-- Button Section -->
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <p class="card-title">Daftar Operator</p>
                                <!-- Button to trigger modal -->
                                <a href="{{ route('users.create') }}" class="btn btn-primary">Tambah Data</a>
                            </div>
                            <!-- Table Section -->
                            <div class="table-responsive">

                                <a href="{{ route('operator.export') }}" class="btn btn-sm btn-inverse-success btn-icon-text">
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
                                            <th>Role</th>
                                            <th width="15%">
                                                <center>Aksi</center>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($users as $item)
                                            <tr>
                                                <td style="text-align: center"> {{ $loop->iteration }} </td>
                                                <td> {{ $item->name }} </td>
                                                <td> {{ $item->username }} </td>
                                                <td> {{ $item->email }} </td>
                                                <td>
                                                    @foreach ($item->roles as $role)
                                                        <span class="badge badge-info">{{ $role->name }}</span>
                                                    @endforeach
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <a href="{{ route('users.edit', $item->id) }}" class="btn btn-sm btn-outline-success btn-fw mr-3">Edit</a>

                                                        <form id="deleteForm{{ $item->id }}" action="{{ route('users.destroy', $item->id) }}" method="POST" style="display: inline;">
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
