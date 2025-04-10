@extends('layouts.app')

@section('title', 'Permission')

@section('content')
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <!-- Button Section -->
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <p class="card-title">Daftar Permission</p>
                            </div>
                            <!-- Table Section -->
                            <div class="table-responsive">
                                <table id="myTable" class="display expandable-table" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th style="text-align: center">No</th>
                                            <th>Nama Kelas</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($permissions as $item)
                                            <tr>
                                                <td style="text-align: center"> {{ $loop->iteration }} </td>
                                                <td> {{ $item->name }} </td>
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
