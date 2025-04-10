@extends('layouts.app')

@section('title', 'Data Siswa')

@section('content')
    <div class="row">
        @include('components.nav')

        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <!-- Title Section -->
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <p class="card-title">Daftar Siswa - {{ $pembelajaran->nama_mapel }}
                                    ({{ $kelasData->nama_kelas }})</p>
                            </div>

                            <!-- Form Utama untuk Semua Aksi -->
                            <!-- Form Utama untuk Semua Aksi -->
                            <form id="batchActionForm" method="POST">
                                @csrf
                                <input type="hidden" name="pembelajaran_id" value="{{ $pembelajaran->id }}">
                                <input type="hidden" id="actionType" name="action">
                                <input type="hidden" id="siswaIdsInput" name="siswa_ids">

                                <!-- Tombol Aksi -->
                                <div class="d-flex justify-content-start mb-3">
                                    <button type="button"
                                        onclick="submitForm('approve', '{{ route('enrollment.batchUpdate') }}')"
                                        class="btn btn-sm btn-success mr-2">Approve Selected</button>
                                    <button type="button"
                                        onclick="submitForm('reject', '{{ route('enrollment.batchUpdate') }}')"
                                        class="btn btn-sm btn-danger ms-2 mr-2">Reject Selected</button>
                                    <button type="button"
                                        onclick="confirmBatchDelete('{{ route('enrollment.batchDelete') }}')"
                                        class="btn btn-sm btn-outline-danger ms-2 mr-2">Delete Selected</button>
                                </div>

                                <!-- Table -->
                                <div class="table-responsive mt-3">
                                    <table id="myTable" class="display expandable-table" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th><input type="checkbox" id="selectAll"></th> {{-- Pilih Semua --}}
                                                <th style="text-align: center">No</th>
                                                <th>Nama</th>
                                                <th>Username</th>
                                                <th>Email</th>
                                                <th>Kelas</th>
                                                <th width="20%">
                                                    <center>Status</center>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($siswaList as $index => $siswa)
                                                @php
                                                    $enrollment = $siswa->enrollments->first();
                                                    $status = $enrollment->status ?? 'pending';
                                                @endphp
                                                <tr>
                                                    <td><input type="checkbox" name="siswa_ids[]"
                                                            value="{{ $siswa->id }}"></td>
                                                    <td style="text-align: center"> {{ $index + 1 }} </td>
                                                    <td> {{ $siswa->name }} </td>
                                                    <td> {{ $siswa->username }} </td>
                                                    <td> {{ $siswa->email }} </td>
                                                    <td> {{ $kelasData->nama_kelas }} </td>
                                                    <td class="text-center">
                                                        <span
                                                            class="badge {{ $status == 'approved' ? 'badge-success' : ($status == 'rejected' ? 'badge-danger' : 'badge-warning') }}">
                                                            {{ ucfirst($status) }}
                                                        </span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </form>

                            <script>
                                // Checkbox Select All
                                document.getElementById('selectAll').addEventListener('change', function() {
                                    let checkboxes = document.querySelectorAll('input[name="siswa_ids[]"]');
                                    checkboxes.forEach(cb => cb.checked = this.checked);
                                });

                                // Function untuk submit form dengan action yang sesuai
                                function submitForm(actionType, url) {
                                    let selectedIds = Array.from(document.querySelectorAll('input[name="siswa_ids[]"]:checked'))
                                        .map(cb => cb.value);

                                    if (selectedIds.length === 0) {
                                        alert('Pilih minimal satu siswa.');
                                        return;
                                    }

                                    document.getElementById('actionType').value = actionType;
                                    document.getElementById('siswaIdsInput').value = selectedIds.join(',');

                                    let form = document.getElementById('batchActionForm');
                                    form.action = url; // Set action sesuai tombol yang diklik
                                    form.submit();
                                }

                                // Konfirmasi dan Submit Form untuk Delete
                                function confirmBatchDelete(url) {
                                    let selectedIds = Array.from(document.querySelectorAll('input[name="siswa_ids[]"]:checked'))
                                        .map(cb => cb.value);

                                    if (selectedIds.length === 0) {
                                        Swal.fire({
                                            icon: 'warning',
                                            title: 'Tidak ada siswa yang dipilih!',
                                            text: 'Silakan pilih minimal satu siswa untuk dihapus.',
                                        });
                                        return;
                                    }

                                    Swal.fire({
                                        title: 'Apakah Anda yakin?',
                                        text: "Data yang dipilih akan dihapus secara permanen!",
                                        icon: 'warning',
                                        showCancelButton: true,
                                        confirmButtonColor: '#d33',
                                        cancelButtonColor: '#3085d6',
                                        confirmButtonText: 'Ya, Hapus!',
                                        cancelButtonText: 'Batal'
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            document.getElementById('actionType').value = 'delete';
                                            document.getElementById('siswaIdsInput').value = selectedIds.join(',');

                                            let form = document.getElementById('batchActionForm');
                                            form.action = url; // Set action ke batchDelete
                                            form.submit();
                                        }
                                    });
                                }
                            </script>


                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
