@extends('layouts.app')

@section('title', 'Data Siswa')

@section('content')
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <!-- Button Section -->
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <p class="card-title">Daftar Data Siswa</p>
                                <a href="{{ route('siswa.create') }}" class="btn btn-primary">Tambah Data</a>
                            </div>

                            <!-- Dropdown Filter Section -->
                            <div class="row">
                                <div class="col-md-3">
                                    <form method="GET" action="{{ route('siswa.export') }}">
                                        <input type="hidden" name="kelas_id" value="{{ request('kelas_id') }}">
                                        <!-- Masukkan filter kelas -->
                                        <button type="submit" class="btn btn-sm btn-inverse-success btn-icon-text">
                                            <i class="ti-download btn-icon-prepend"></i>
                                            Download XLSX
                                        </button>
                                    </form>
                                </div>


                                <!-- Filter Kelas -->
                                <div class="col-md-3 offset-md-6">
                                    <form method="GET" action="{{ route('siswa.index') }}"
                                        class="d-flex justify-content-end">
                                        <select name="kelas_id" id="kelas_id" class="form-control mr-2">
                                            <option value="">-- Semua Kelas --</option>
                                            @foreach ($kelas as $k)
                                                <option value="{{ $k->id }}"
                                                    {{ request('kelas_id') == $k->id ? 'selected' : '' }}>
                                                    {{ $k->nama_kelas }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <button type="submit" class="btn btn-sm btn-outline-info">
                                            <i class="ti-filter btn-icon-append"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>

                            <!-- Table Section -->
                            {{-- <div class="table-responsive">
                                <table id="myTable" class="display expandable-table" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th style="text-align: center">No</th>
                                            <th>Nama</th>
                                            <th>Username</th>
                                            <th>Email</th>
                                            <th>Kelas</th>
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
                                                <td> {{ $user->siswa->kelas->nama_kelas ?? '-' }} </td>
                                                <td>
                                                    @if ($user->foto)
                                                        <img src="{{ asset('storage/foto_user/' . $user->foto) }}"
                                                            alt="Foto" width="50">
                                                    @else
                                                        No Image
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <a href="{{ route('siswa.edit', $user->siswa->id) }}"
                                                            class="btn btn-sm btn-outline-success btn-fw mr-3">Edit</a>

                                                        <form id="deleteForm{{ $user->siswa->id }}"
                                                            action="{{ route('siswa.destroy', $user->siswa->id) }}"
                                                            method="POST" style="display: inline;">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="button"
                                                                class="btn btn-sm btn-outline-danger btn-fw"
                                                                onclick="confirmDelete('{{ $user->siswa->id }}')">Delete</button>
                                                        </form>

                                                        @if (!$user->is_verified)
                                                            <form action="{{ route('siswa.verify', $user->id) }}"
                                                                method="POST" style="display:inline;">
                                                                @csrf
                                                                @method('PATCH')
                                                                <button type="submit"
                                                                    class="btn btn-sm btn-outline-primary btn-fw ml-2">Verifikasi</button>
                                                            </form>
                                                        @else
                                                            <span class="badge badge-success ml-2">Terverifikasi</span>
                                                        @endif

                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div> --}}

                            <!-- Tombol Verifikasi -->
                            <div class="mb-3 mt-3">
                                <button type="button" class="btn btn-outline-success" onclick="submitBulkVerification()">
                                    Verifikasi yang Dipilih
                                </button>

                                <button type="button" class="btn btn-outline-danger" onclick="submitBulkUnverification()">
                                    Batalkan Verifikasi
                                </button>
                            </div>


                            <!-- Form Verifikasi -->
                            <form id="bulkVerifyForm" method="POST" action="{{ route('siswa.verify.multiple') }}"
                                style="display: none;">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="user_ids" id="selectedUserIdsVerify">
                            </form>

                            <!-- Form Batalkan Verifikasi -->
                            <form id="bulkUnverifyForm" method="POST" action="{{ route('siswa.unverify.multiple') }}"
                                style="display: none;">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="user_ids" id="selectedUserIdsUnverify">
                            </form>


                            <!-- Tabel Siswa -->
                            <div class="table-responsive">
                                <table id="myTable" class="display expandable-table" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th><input type="checkbox" id="checkAll"></th>
                                            <th style="text-align: center">No</th>
                                            <th>Nama</th>
                                            <th>Username</th>
                                            <th>Email</th>
                                            <th>Kelas</th>
                                            <th>Status Verifikasi</th>
                                            <th width="15%">
                                                <center>Aksi</center>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($users as $user)
                                            <tr>
                                                <td>
                                                    <input type="checkbox" class="checkItem" value="{{ $user->id }}">
                                                </td>
                                                <td style="text-align: center"> {{ $loop->iteration }} </td>
                                                <td> {{ $user->name }} </td>
                                                <td> {{ $user->username }} </td>
                                                <td> {{ $user->email }} </td>
                                                <td> {{ $user->siswa->kelas->nama_kelas ?? '-' }} </td>
                                                <td>
                                                    @if ($user->is_verified)
                                                        <span class="badge badge-success ml-2">Terverifikasi</span>
                                                    @else
                                                        <span class="badge badge-danger ml-2">Tidak Terverifikasi</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <a href="{{ route('siswa.edit', $user->siswa->id) }}"
                                                            class="btn btn-sm btn-outline-success btn-fw mr-2">Edit</a>

                                                        <form id="deleteForm{{ $user->siswa->id }}"
                                                            action="{{ route('siswa.destroy', $user->siswa->id) }}"
                                                            method="POST" style="display: inline;">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="button"
                                                                class="btn btn-sm btn-outline-danger btn-fw"
                                                                onclick="confirmDelete('{{ $user->siswa->id }}')">Delete</button>
                                                        </form>

                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            {{ $users->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Checkbox "Pilih Semua"
        document.getElementById('checkAll').addEventListener('change', function() {
            const checked = this.checked;
            document.querySelectorAll('.checkItem').forEach(cb => cb.checked = checked);
        });

        // Fungsi Submit Verifikasi
        function submitBulkVerification() {
            const selected = getSelectedIds();
            if (selected.length === 0) {
                alert('Pilih setidaknya satu siswa untuk diverifikasi.');
                return;
            }
            document.getElementById('selectedUserIdsVerify').value = selected.join(',');
            document.getElementById('bulkVerifyForm').submit();
        }

        // Fungsi Submit Unverifikasi
        function submitBulkUnverification() {
            const selected = getSelectedIds();
            if (selected.length === 0) {
                alert('Pilih setidaknya satu siswa untuk dibatalkan verifikasinya.');
                return;
            }
            document.getElementById('selectedUserIdsUnverify').value = selected.join(',');
            document.getElementById('bulkUnverifyForm').submit();
        }

        // Ambil ID terpilih
        function getSelectedIds() {
            return Array.from(document.querySelectorAll('.checkItem:checked')).map(cb => cb.value);
        }
    </script>
@endpush
