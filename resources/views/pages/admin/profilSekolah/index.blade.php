@extends('layouts.app')

@section('title', 'Profile Sekolah')

@section('content')
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">Profile Sekolah</h4>

                                    <form action="{{ route('profilesekolah.update') }}" method="POST" class="forms-sample"
                                        enctype="multipart/form-data">
                                        @csrf


                                        <div class="form-group">
                                            <label for="exampleInputUsername">Nama Sekolah</label>
                                            <input name="nama_sekolah"
                                                class="form-control @error('nama_sekolah') is-invalid @enderror"
                                                value="{{ old('nama_sekolah') }}" type="text"
                                                id="exampleInputnama_sekolah" placeholder="nama_sekolah">
                                            @if ($errors->has('nama_sekolah'))
                                                <div class="text-danger">{{ $errors->first('nama_pkk') }}</div>
                                            @endif
                                        </div>

                                        <div class="form-group">
                                            <label for="exampleTextarea1">Alamat</label>
                                            <textarea name="alamat" class="form-control" id="exampleTextarea1" rows="4">{{ old('alamat') }}</textarea>
                                            @if ($errors->has('alamat'))
                                                <div class="text-danger">{{ $errors->first('alamat') }}</div>
                                            @endif
                                        </div>

                                        <div class="form-group">
                                            <label for="exampleInputName1">Akreditasi</label>
                                            <input name="akreditas"
                                                class="form-control @error('akreditas') is-invalid @enderror"
                                                value="{{ old('akreditas') }}" type="text" id="exampleInputakreditas1"
                                                placeholder="Alamat">
                                            @if ($errors->has('akreditas'))
                                                <div class="text-danger">{{ $errors->first('akreditas') }}</div>
                                            @endif
                                        </div>

                                        <div class="form-group">
                                            <label for="exampleInputName1">Email</label>
                                            <input name="email"
                                                class="form-control @error('email') is-invalid @enderror"
                                                value="{{ old('email') }}" type="email" id="exampleInputemail1"
                                                placeholder="Alamat">
                                            @if ($errors->has('email'))
                                                <div class="text-danger">{{ $errors->first('email') }}</div>
                                            @endif
                                        </div>

                                        <div class="form-group">
                                            <label for="exampleInputName1">NO HP</label>
                                            <input name="no_hp"
                                                class="form-control @error('no_hp') is-invalid @enderror"
                                                value="{{ old('no_hp') }}" type="text" id="exampleInputno_hp1"
                                                placeholder="Alamat">
                                            @if ($errors->has('no_hp'))
                                                <div class="text-danger">{{ $errors->first('no_hp') }}</div>
                                            @endif
                                        </div>

                                        <div class="form-group">
                                            <label>Logo</label>
                                            <div class="tampil-foto mb-2"></div>
                                            <input type="file" name="foto"
                                                class="form-control  @error('foto') is-invalid @enderror" accept="image/*"
                                                onchange="preview('.tampil-foto', this.files[0], 300)">
                                            @if ($errors->has('foto'))
                                                <div class="text-danger">{{ $errors->first('foto') }}</div>
                                            @endif
                                            <small class="text-muted">Maximum file size: 2 MB</small>
                                        </div>

                                        <div class="form-group d-flex justify-content-end">
                                            <button type="submit" class="btn btn-primary mr-2">Submit</button>
                                        </div>

                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('scripts')
    <script>
        $(function() {
            showData();
            $('.form-setting').validator().on('submit', function(e) {
                if (!e.preventDefault()) {
                    $.ajax({
                            url: $('.form-setting').attr('action'),
                            type: $('.form-setting').attr('method'),
                            data: new FormData($('.form-setting')[0]),
                            async: false,
                            processData: false,
                            contentType: false
                        })
                        .done(response => {
                            showData();
                            $('.alert').fadeOut();
                        })
                        .fail(errors => {
                            alert('Tidak dapat menyimpan data');
                            return;
                        });
                }
            });
        });

        function showData() {
            $.get('{{ route('profilesekolah.show') }}')
                .done(response => {
                    $('[name=nama_sekolah]').val(response.nama_sekolah);
                    $('[name=alamat]').val(response.alamat);
                    $('[name=akreditas]').val(response.akreditas);
                    $('[name=email]').val(response.email);
                    $('[name=no_hp]').val(response.no_hp);
                    $('.tampil-foto').html(
                        `<img src="{{ url('storage/logo_sekolah/') }}/${response.foto}" width="100">`);
                })
                .fail(errors => {
                    alert('Tidak dapat menampilkan data');
                    return;
                })
        }
    </script>


    <script>
        function previewImage(event) {
            const input = event.target;
            const reader = new FileReader();

            reader.onload = function() {
                const preview = document.getElementById('previewFoto');
                preview.src = reader.result; // Tampilkan gambar baru
            };

            if (input.files && input.files[0]) {
                reader.readAsDataURL(input.files[0]); // Baca file yang diunggah
            }
        }
    </script>
@endpush
