@extends('layouts.app')

@section('title', 'Data Pertemuan Kuis')

<style>
    .pertemuan-item {
        cursor: pointer;
        transition: background-color 0.3s;
    }

    .pertemuan-item:hover {
        background-color: #F7F7F7;
    }

    .pertemuan-item.active {
        background-color: #F7F7F7 !important;
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
                        <h4 class="mb-3">Daftar Kuis Siswa {{ $kelasData->nama_kelas }}</h4>                    
                        <!-- Button to trigger modal -->
                        <button type="button" class="btn btn-sm btn-primary" data-toggle="modal"
                            data-target="#addKurikulumModal">
                            Tambahkan
                        </button>
                    </div>

                    <a href="{{ route('nilai.export', ['pembelajaran_id' => $pembelajaran->id]) }}" class="btn btn-sm btn-success mb-3">
                        <i class="fa fa-download"></i> Export Nilai Kuis
                    </a>                    

                    <!-- Sidebar dan Content -->
                    <div class="row">
                        <!-- Sidebar -->
                        <div class="col-12 col-md-3 mb-3">
                            <div class="card shadow-sm" style="background-color: #F2F9FF; overflow-y: auto;">
                                <div class="card-body">
                                    <ul class="list-group" id="sidebar-pertemuan">
                                        <h5 class="mb-3">Daftar Pertemuan Kuis</h5>
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

                        <!-- Isi Pertemuan Materi -->
                        <div class="col-md-9">
                            <div class="card shadow-sm" style="background-color: #F2F9FF; overflow-y: auto;">
                                <div class="card-body">
                                    <h5 class="mb-3" id="judul-pertemuan">Klik Pertemuan untuk melihat kuis yang
                                        diinputkan</h5>
                                    <div class="list-group" id="kuis-container"
                                        data-mapel="{{ strtolower(str_replace(' ', '-', $pembelajaran->nama_mapel)) }}"
                                        data-kelas="{{ strtolower(str_replace(' ', '-', $pembelajaran->kelas->nama_kelas)) }}"
                                        data-tahun="{{ str_replace('/', '-', $pembelajaran->tahunAjaran->nama_tahun) }}"
                                        data-semester="{{ strtolower(str_replace(' ', '-', $pembelajaran->semester)) }}">
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
                    <h5 class="modal-title" id="addKurikulumModalLabel">Tambah Kuis {{ $kelasData->nama_kelas }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('pertemuan-kuis.store', ['pembelajaran_id' => $pembelajaran->id]) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">

                        <div class="form-group">
                            <label for="exampleSelectKelas">Pertemuan  <span class="text-danger">*</span></label>
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
                            <label for="exampleSelectKelas">Kuis <span class="text-danger">*</span></label>
                            <select name="kuis_id" class="form-control @error('kuis_id') is-invalid @enderror"
                                id="exampleSelectguru">
                                <option value="">Pilih Kuis</option>
                                @foreach ($kuis as $item)
                                    <option value="{{ $item->id }}"
                                        {{ old('kuis_id') == $item->id ? 'selected' : '' }}>
                                        {{ $item->judul }}
                                    </option>
                                @endforeach
                            </select>
                            @error('kuis_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="deadline">Deadline Tugas <span class="text-danger">*</span></label>
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


     <!-- Modal Edit Materi -->
     <div class="modal fade" id="editKuisModal" tabindex="-1" aria-labelledby="editKuisModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <form id="editKuisForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title" id="editKuisModalLabel">Edit Kuis</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="pembelajaran_id" value="{{ $pembelajaran->id }}">
                        <input type="hidden" name="pertemuan_kuis_id" id="editPertemuanKuisId">

                        <div class="form-group">
                            <label for="editPertemuanSelect">Pertemuan <span class="text-danger">*</span></label>
                            <select name="pertemuan_id" id="editPertemuanSelect" class="form-control">
                                @foreach ($pertemuanSemua as $item)
                                    <option value="{{ $item->id }}">{{ $item->judul }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="editKuisSelect">Kuis <span class="text-danger">*</span></label>
                            <select name="kuis_id" id="editTugasSelect" class="form-control">
                                @foreach ($kuis as $item)
                                    <option value="{{ $item->id }}">{{ $item->judul }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="editDeadlineKuis">Deadline Kuis <span class="text-danger">*</span></label>
                            <input type="datetime-local" class="form-control @error('deadline') is-invalid @enderror"
                                id="editDeadlineKuis" name="deadline" value="{{ old('deadline') }}">
                            @error('deadline')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
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
                    url: `/guru/kuis/${pertemuanId}?pembelajaran_id=${pembelajaranId}`,
                    type: "GET",
                    success: function(response) {
                        let kuisContainer = $("#kuis-container");
                        kuisContainer.empty();

                        if (response.length > 0) {
                            response.forEach(function(item) {

                                let kuisItem = `
                                    <div class="list-group-item d-flex justify-content-between align-items-center border rounded p-2 mb-2 kuis-item"
                                        data-id="${item.id}">
                                        <div>
                                            <i class="fas fa-file-alt text-primary"></i>
                                            <span class="ml-2">${item.kuis.judul}</span><br>
                                            <small class="ml-4">Token: <code id="token-${item.id}">${item.token}</code></small>
                                        </div>
                                        <div>
                                            <button class="btn btn-light btn-sm copy-token" data-token="${item.token}" data-token-id="${item.id}">
                                                <i class="fas fa-copy"></i> Salin
                                            </button>
                                            <button class="btn btn-info btn-sm lihat-siswa" data-id="${item.kuis.id}">
                                                <i class="fas fa-users"></i> Lihat Siswa
                                            </button>
                                            <button class="btn btn-warning btn-sm edit-kuis" 
                                                data-toggle="modal" 
                                                data-target="#editKuisModal" 
                                                data-id="${item.id}" 
                                                data-kuis-id="${item.kuis.id}" 
                                                data-pertemuan-id="${item.pertemuan_id}" 
                                                data-deadline="${item.deadline}">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-danger btn-sm delete-kuis" data-id="${item.id}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>`;


                                kuisContainer.append(kuisItem);
                            });

                            // Event klik untuk membuka file
                            $(document).on("click", ".kuis-item", function(e) {

                                if ($(e.target).closest(".edit-kuis, .delete-kuis")
                                    .length > 0) {
                                    return;
                                }

                                let fileUrl = $(this).data("file-url");
                                if (fileUrl !== "#") {
                                    window.open(fileUrl, "_blank");
                                } else {
                                    alert("File tidak tersedia.");
                                }
                            });

                            $(".lihat-siswa").click(function(e) {
                                e.preventDefault();
                                e.stopPropagation();

                                let container = $("#kuis-container");
                                let mapelSlug = container.data("mapel");
                                let kelasSlug = container.data("kelas");
                                let tahunSlug = container.data("tahun");
                                let semesterSlug = container.data("semester");

                                let kuisId = $(this).data(
                                "id"); // Ambil ID tugas yg diklik

                                let redirectUrl =
                                    `/guru/submit-kuis/${mapelSlug}/${kelasSlug}/${tahunSlug}/${semesterSlug}/list-kuis?kuis_id=${kuisId}`;
                                window.location.href = redirectUrl;
                            });


                            // Tampilkan data ke dalam modal edit saat tombol edit diklik
                            $(document).on("click", ".edit-kuis", function(e) {
                                e
                                    .stopPropagation(); 

                                let id = $(this).data("id");
                                let kuisId = $(this).data("kuis-id");
                                let pertemuanId = $(this).data("pertemuan-id");
                                let deadline = $(this).data("deadline");

                                $("#editPertemuanKuisId").val(id);
                                $("#editPertemuanSelect").val(pertemuanId);
                                $("#editKuisSelect").val(kuisId);
                                $("#editDeadlineKuis").val(deadline);

                                let actionUrl = `/guru/pertemuan-kuis/${id}`;
                                $("#editKuisForm").attr("action", actionUrl);
                            });


                            // Event klik untuk hapus kuis dengan SweetAlert
                            $(".delete-kuis").click(function(e) {
                                e.preventDefault();
                                e
                                    .stopPropagation(); 

                                let kuisId = $(this).data("id");
                                let parentItem = $(this).closest(".kuis-item");

                                Swal.fire({
                                    title: "Apakah Anda yakin?",
                                    text: "kuis ini akan dihapus secara permanen!",
                                    icon: "warning",
                                    showCancelButton: true,
                                    confirmButtonColor: "#d33",
                                    cancelButtonColor: "#3085d6",
                                    confirmButtonText: "Ya, hapus!",
                                    cancelButtonText: "Batal"
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        $.ajax({
                                            url: "/guru/pertemuan-kuis/" +
                                                kuisId,
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
                                                        "kuis telah dihapus.",
                                                        "success"
                                                    );
                                                } else {
                                                    Swal.fire(
                                                        "Gagal!",
                                                        "Gagal menghapus kuis.",
                                                        "error"
                                                    );
                                                }
                                            },
                                            error: function() {
                                                Swal.fire(
                                                    "Error!",
                                                    "Terjadi kesalahan saat menghapus kuis.",
                                                    "error"
                                                );
                                            }
                                        });
                                    }
                                });
                            });


                            // Handler tombol salin token
                            $(".copy-token").click(function(e) {
                                e.stopPropagation(); 
                                let token = $(this).data("token");

                                // Salin ke clipboard
                                navigator.clipboard.writeText(token).then(function() {
                                    Swal.fire({
                                        title: "Disalin!",
                                        text: "Token berhasil disalin ke clipboard.",
                                        icon: "success",
                                        timer: 1500,
                                        showConfirmButton: false
                                    });
                                }).catch(function() {
                                    Swal.fire("Oops!", "Gagal menyalin token.",
                                        "error");
                                });
                            });



                        } else {
                            kuisContainer.append(
                                "<p class='text-muted'>Tidak ada kuis untuk pertemuan ini.</p>"
                            );
                        }
                    }
                });
            });
        });
    </script>
@endsection
