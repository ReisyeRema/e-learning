@extends('layouts.app')

@section('title', 'Data Pertemuan Materi')

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
                        <h4 class="mb-3">Daftar Materi Siswa {{ $kelasData->nama_kelas }}</h4>
                        <!-- Button to trigger modal -->
                        <button type="button" class="btn btn-sm btn-primary" data-toggle="modal"
                            data-target="#addKurikulumModal">
                            Tambahkan
                        </button>
                    </div>

                    <!-- Sidebar dan Content -->
                    <div class="row">
                        <!-- Sidebar -->
                        <div class="col-12 col-md-3 mb-3">
                            <div class="card shadow-sm" style="background-color: #F2F9FF; overflow-y: auto;">
                                <div class="card-body">
                                    <ul class="list-group" id="sidebar-pertemuan">
                                        <h5 class="mb-3">Daftar Pertemuan Materi</h5>
                                        @foreach ($pertemuan as $item)
                                            <li class="list-group-item d-flex justify-content-between align-items-center pertemuan-item"
                                                data-pertemuan="{{ $item->id }}"
                                                data-pembelajaran="{{ $pembelajaran->id }}">
                                                {{ $item->judul }}
                                                {{-- <a href="#" class="text-danger delete-pertemuan" data-id="{{ $item->id }}">
                                                    <i class="fas fa-trash-alt"></i>
                                                </a>                                                 --}}
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Isi Pertemuan Materi -->
                        <div class="col-md-9">
                            <div class="card shadow-sm" style="background-color: #F2F9FF; overflow-y: auto;">
                                <div class="card-body">
                                    <h5 class="mb-3" id="judul-pertemuan">Klik Pertemuan untuk melihat materi yang
                                        diinputkan</h5>
                                    <div class="list-group" id="materi-container">
                                        <!-- Materi akan dimasukkan di sini -->
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
                    <h5 class="modal-title" id="addKurikulumModalLabel">Tambah Materi {{ $kelasData->nama_kelas }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('pertemuan-materi.store', ['pembelajaran_id' => $pembelajaran->id]) }}"
                    method="POST" enctype="multipart/form-data">
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
                            <label for="exampleSelectKelas">Materi</label>
                            <select name="materi_id" class="form-control @error('materi_id') is-invalid @enderror"
                                id="exampleSelectguru">
                                <option value="">Pilih Materi</option>
                                @foreach ($materi as $item)
                                    <option value="{{ $item->id }}"
                                        {{ old('materi_id') == $item->id ? 'selected' : '' }}>
                                        {{ $item->judul }}
                                    </option>
                                @endforeach
                            </select>
                            @error('materi_id')
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
    <script>
        $(document).ready(function() {
            $(".pertemuan-item").click(function() {
                let pertemuanId = $(this).data("pertemuan");
                let pembelajaranId = $(this).data("pembelajaran");
                let pertemuanJudul = $(this).text().trim();

                // Hapus class 'active' dari semua item dan tambahkan ke yang diklik
                $(".pertemuan-item").removeClass("active");
                $(this).addClass("active");

                // Update judul pertemuan
                $("#judul-pertemuan").text(pertemuanJudul);

                $.ajax({
                    url: `/guru/materi/${pertemuanId}?pembelajaran_id=${pembelajaranId}`,
                    type: "GET",
                    success: function(response) {
                        let materiContainer = $("#materi-container");
                        materiContainer.empty();

                        if (response.length > 0) {
                            response.forEach(function(item) {
                                let fileUrl = item.materi.file_path ?
                                    `https://drive.google.com/file/d/${item.materi.file_path}/view` :
                                    "#";

                                let materiItem = `
                        <div class="list-group-item d-flex justify-content-between align-items-center border rounded p-2 mb-2 materi-item"
                            data-id="${item.id}" data-file-url="${fileUrl}">
                            <div>
                                <i class="fas fa-file-alt text-primary"></i>
                                <span class="ml-2">${item.materi.judul}</span>
                            </div>
                            <button class="btn btn-danger btn-sm delete-materi" data-id="${item.id}">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>`;

                                materiContainer.append(materiItem);
                            });

                            // Event klik untuk membuka file
                            $(".materi-item").click(function() {
                                let fileUrl = $(this).data("file-url");
                                if (fileUrl !== "#") {
                                    window.open(fileUrl, "_blank");
                                } else {
                                    alert("File tidak tersedia.");
                                }
                            });

                            // Event klik untuk hapus materi dengan SweetAlert
                            $(".delete-materi").click(function(e) {
                                e.preventDefault();
                                e
                                    .stopPropagation(); // Mencegah event bubbling ke elemen .materi-item

                                let materiId = $(this).data("id");
                                let parentItem = $(this).closest(".materi-item");

                                Swal.fire({
                                    title: "Apakah Anda yakin?",
                                    text: "Materi ini akan dihapus secara permanen!",
                                    icon: "warning",
                                    showCancelButton: true,
                                    confirmButtonColor: "#d33",
                                    cancelButtonColor: "#3085d6",
                                    confirmButtonText: "Ya, hapus!",
                                    cancelButtonText: "Batal"
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        $.ajax({
                                            url: "/guru/pertemuan-materi/" +
                                                materiId,
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
                                                        "Materi telah dihapus.",
                                                        "success"
                                                    );
                                                } else {
                                                    Swal.fire(
                                                        "Gagal!",
                                                        "Gagal menghapus materi.",
                                                        "error"
                                                    );
                                                }
                                            },
                                            error: function() {
                                                Swal.fire(
                                                    "Error!",
                                                    "Terjadi kesalahan saat menghapus materi.",
                                                    "error"
                                                );
                                            }
                                        });
                                    }
                                });
                            });


                        } else {
                            materiContainer.append(
                                "<p class='text-muted'>Tidak ada materi untuk pertemuan ini.</p>"
                            );
                        }
                    }
                });
            });
        });
    </script>
@endsection
