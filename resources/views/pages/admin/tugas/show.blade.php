@extends('layouts.app')

@section('title', 'Data Pertemuan Tugas')

<style>
    .pertemuan-item {
        cursor: pointer;
        transition: background-color 0.3s;
    }

    .pertemuan-item:hover {
        background-color: #F7F7F7;
        /* Biru muda saat hover */
    }

    .pertemuan-item.active {
        background-color: #F7F7F7 !important;
        /* Biru saat aktif */
        color: rgb(0, 0, 0) !important;
        font-weight: bold;
    }

    .materi-item {
        cursor: pointer;
        transition: background-color 0.3s;
    }

    .materi-item:hover {
        background-color: #F7F7F7;
    }

    .materi-item.active {
        background-color: #007bff !important;
        color: white !important;
        font-weight: bold;
    }
</style>

@section('content')
    <div class="row">

        @include('components.nav')

        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h4 class="mb-3">Daftar Tugas Siswa {{ $kelasData->nama_kelas }}</h4>
                        <!-- Button to trigger modal -->
                        <button type="button" class="btn btn-sm btn-primary" data-toggle="modal"
                            data-target="#addKurikulumModal">
                            Tambahkan
                        </button>
                    </div>
                    <button type="button" class="btn btn-sm btn-warning mb-3 text-white">
                        Lihat Aktivitas Siswa
                    </button>

                    <!-- Sidebar dan Content -->
                    <div class="row">
                        <!-- Sidebar -->
                        <div class="col-12 col-md-3 mb-3">
                            <div class="card shadow-sm" style="background-color: #F2F9FF; overflow-y: auto;">
                                <div class="card-body">
                                    <ul class="list-group" id="sidebar-pertemuan">
                                        <h5 class="mb-3">Daftar Pertemuan Tugas</h5>
                                        {{-- @foreach ($pertemuan as $item)
                                            <li class="list-group-item d-flex justify-content-between align-items-center pertemuan-item"
                                                data-pertemuan="{{ $item->id }}">
                                                {{ $item->judul }}
                                            </li>
                                        @endforeach --}}
                                        @foreach ($pertemuan as $item)
                                            <li class="list-group-item d-flex justify-content-between align-items-center pertemuan-item"
                                                data-pertemuan="{{ $item->id }}"
                                                data-pembelajaran="{{ $pembelajaran->id }}">
                                                {{ $item->judul }}
                                            </li>
                                        @endforeach

                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Isi Pertemuan Tugas -->
                        <div class="col-md-9">
                            <div class="card shadow-sm" style="background-color: #F2F9FF; overflow-y: auto;">
                                <div class="card-body">
                                    <h5 class="mb-3" id="judul-pertemuan">Klik Pertemuan untuk melihat tugas yang
                                        diinputkan</h5>
                                    <div class="list-group" id="tugas-container">
                                        <!-- Tugas akan dimasukkan di sini -->
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for adding new kurikulum -->
    <div class="modal fade" id="addKurikulumModal" tabindex="-1" aria-labelledby="addKurikulumModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addKurikulumModalLabel">Tambah Tugas {{ $kelasData->nama_kelas }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('pertemuan-tugas.store', ['pembelajaran_id' => $pembelajaran->id]) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">

                        <div class="form-group">
                            <label for="exampleSelectKelas">Pertemuan</label>
                            <select name="pertemuan_id" class="form-control @error('pertemuan_id') is-invalid @enderror"
                                id="exampleSelectguru">
                                <option value="">Pilih Pertemuan</option>
                                @foreach ($pertemuanSemua as $item)
                                    <option value="{{ $item->id }}"
                                        {{ old('pertemuan_id') == $item->id ? 'selected' : '' }}>
                                        {{ $item->judul }}
                                    </option>
                                @endforeach
                            </select>
                            @error('pertemuan_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <input type="hidden" name="pembelajaran_id" value="{{ $pembelajaran->id }}">

                        <div class="form-group">
                            <label for="exampleSelectKelas">Tugas</label>
                            <select name="tugas_id" class="form-control @error('tugas_id') is-invalid @enderror"
                                id="exampleSelectguru">
                                <option value="">Pilih Tugas</option>
                                @foreach ($tugas as $item)
                                    <option value="{{ $item->id }}"
                                        {{ old('tugas_id') == $item->id ? 'selected' : '' }}>
                                        {{ $item->judul }}
                                    </option>
                                @endforeach
                            </select>
                            @error('tugas_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="deadline">Deadline Tugas</label>
                            <input type="datetime-local" class="form-control @error('deadline') is-invalid @enderror"
                                id="deadline" name="deadline" value="{{ old('deadline') }}">
                            @error('deadline')
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

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    {{-- <script>
        $(document).ready(function() {
            $(".pertemuan-item").click(function() {
                let pertemuanId = $(this).data("pertemuan");
                let pertemuanJudul = $(this).text().trim();

                // Hapus class 'active' dari semua item dan tambahkan ke yang diklik
                $(".pertemuan-item").removeClass("active");
                $(this).addClass("active");

                // Update judul pertemuan
                $("#judul-pertemuan").text(pertemuanJudul);

                $.ajax({
                    url: "/tugas/" + pertemuanId,
                    type: "GET",
                    success: function(response) {
                        let tugasContainer = $("#tugas-container");
                        tugasContainer.empty();

                        if (response.length > 0) {
                            response.forEach(function(item) {
                                let fileUrl = item.tugas.file_path ?
                                    `https://drive.google.com/file/d/${item.tugas.file_path}/view` :
                                    "#";

                                let tugasItem = `
                        <div class="list-group-item d-flex justify-content-between align-items-center border rounded p-2 mb-2 tugas-item"
                            data-id="${item.id}" data-file-url="${fileUrl}">
                            <div>
                                <i class="fas fa-file-alt text-primary"></i>
                                <span class="ml-2">${item.tugas.judul}</span>
                            </div>
                            <button class="btn btn-danger btn-sm delete-tugas" data-id="${item.id}">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>`;

                                tugasContainer.append(tugasItem);
                            });

                            // Event klik untuk membuka file
                            $(".tugas-item").click(function() {
                                let fileUrl = $(this).data("file-url");
                                if (fileUrl !== "#") {
                                    window.open(fileUrl, "_blank");
                                } else {
                                    alert("File tidak tersedia.");
                                }
                            });

                            // Event klik untuk hapus tugas dengan SweetAlert
                            $(".delete-tugas").click(function(e) {
                                e.preventDefault();
                                e
                                    .stopPropagation(); // Mencegah event bubbling ke elemen .tugas-item

                                let tugasId = $(this).data("id");
                                let parentItem = $(this).closest(".tugas-item");

                                Swal.fire({
                                    title: "Apakah Anda yakin?",
                                    text: "tugas ini akan dihapus secara permanen!",
                                    icon: "warning",
                                    showCancelButton: true,
                                    confirmButtonColor: "#d33",
                                    cancelButtonColor: "#3085d6",
                                    confirmButtonText: "Ya, hapus!",
                                    cancelButtonText: "Batal"
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        $.ajax({
                                            url: "/pertemuan-tugas/" +
                                                tugasId,
                                            type: "DELETE",
                                            data: {
                                                _token: "{{ csrf_token() }}"
                                            },
                                            success: function(
                                                response) {
                                                if (response
                                                    .success) {
                                                    parentItem
                                                        .fadeOut(
                                                            300,
                                                            function() {
                                                                $(this)
                                                                    .remove();
                                                            });
                                                    Swal.fire(
                                                        "Terhapus!",
                                                        "tugas telah dihapus.",
                                                        "success"
                                                    );
                                                } else {
                                                    Swal.fire(
                                                        "Gagal!",
                                                        "Gagal menghapus tugas.",
                                                        "error"
                                                    );
                                                }
                                            },
                                            error: function() {
                                                Swal.fire(
                                                    "Error!",
                                                    "Terjadi kesalahan saat menghapus tugas.",
                                                    "error"
                                                );
                                            }
                                        });
                                    }
                                });
                            });


                        } else {
                            tugasContainer.append(
                                "<p class='text-muted'>Tidak ada materi untuk pertemuan ini.</p>"
                            );
                        }
                    }
                });
            });
        });
    </script> --}}

    <script>
        $(document).ready(function () {
            $(".pertemuan-item").click(function () {
                let pertemuanId = $(this).data("pertemuan");
                let pembelajaranId = $(this).data("pembelajaran");
                let pertemuanJudul = $(this).text().trim();
    
                $(".pertemuan-item").removeClass("active");
                $(this).addClass("active");
    
                $("#judul-pertemuan").text(pertemuanJudul);
    
                $.ajax({
                    url: `/guru/tugas/${pertemuanId}?pembelajaran_id=${pembelajaranId}`,
                    type: "GET",
                    success: function (response) {
                        let tugasContainer = $("#tugas-container");
                        tugasContainer.empty();
    
                        if (response.length > 0) {
                            response.forEach(function (item) {
                                let fileUrl = item.tugas.file_path
                                    ? `https://drive.google.com/file/d/${item.tugas.file_path}/view`
                                    : "#";
    
                                let tugasItem = `
                                    <div class="list-group-item d-flex justify-content-between align-items-center border rounded p-2 mb-2 tugas-item"
                                        data-id="${item.id}" data-file-url="${fileUrl}">
                                        <div>
                                            <i class="fas fa-file-alt text-primary"></i>
                                            <span class="ml-2">${item.tugas.judul}</span>
                                        </div>
                                        <button class="btn btn-danger btn-sm delete-tugas" data-id="${item.id}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>`;
    
                                tugasContainer.append(tugasItem);
                            });
    
                            // Buka file
                            $(".tugas-item").click(function () {
                                let fileUrl = $(this).data("file-url");
                                if (fileUrl !== "#") {
                                    window.open(fileUrl, "_blank");
                                } else {
                                    alert("File tidak tersedia.");
                                }
                            });
    
                            // Hapus tugas
                            $(".delete-tugas").click(function (e) {
                                e.preventDefault();
                                e.stopPropagation();
    
                                let tugasId = $(this).data("id");
                                let parentItem = $(this).closest(".tugas-item");
    
                                Swal.fire({
                                    title: "Apakah Anda yakin?",
                                    text: "tugas ini akan dihapus secara permanen!",
                                    icon: "warning",
                                    showCancelButton: true,
                                    confirmButtonColor: "#d33",
                                    cancelButtonColor: "#3085d6",
                                    confirmButtonText: "Ya, hapus!",
                                    cancelButtonText: "Batal"
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        $.ajax({
                                            url: "/guru/pertemuan-tugas/" + tugasId,
                                            type: "DELETE",
                                            data: {
                                                _token: "{{ csrf_token() }}"
                                            },
                                            success: function (response) {
                                                if (response.success) {
                                                    parentItem.fadeOut(300, function () {
                                                        $(this).remove();
                                                    });
                                                    Swal.fire("Terhapus!", "tugas telah dihapus.", "success");
                                                } else {
                                                    Swal.fire("Gagal!", "Gagal menghapus tugas.", "error");
                                                }
                                            },
                                            error: function () {
                                                Swal.fire("Error!", "Terjadi kesalahan saat menghapus tugas.", "error");
                                            }
                                        });
                                    }
                                });
                            });
    
                        } else {
                            tugasContainer.append(
                                "<p class='text-muted'>Tidak ada materi untuk pertemuan ini.</p>"
                            );
                        }
                    }
                });
            });
        });
    </script>
    
@endsection
