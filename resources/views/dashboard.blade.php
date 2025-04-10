@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="row">
        <div class="col-md-12 grid-margin">

            <div class="row">
                <div class="col-md-3 mb-3">
                    <div class="card">
                        <div class="card-body d-flex flex-column align-items-center my-3">
                            <!-- Foto Profil -->
                            <div class="mb-3">
                                <img id="previewFoto"
                                    src="{{ Auth::user()->foto ? asset('storage/foto_user/' . Auth::user()->foto) : asset('assets/img/profil.png') }}"
                                    alt="Foto Profil" class="rounded-circle img-fluid"
                                    style="max-width: 100px; max-height: 100px;">
                            </div>
                            <!-- Nama dan Email -->
                            <h5 class="font-weight-bold">{{ Auth::user()->name }}</h5>
                            <label class="badge badge-pill badge-info">{{ Auth::user()->email }}</label>
                            <a class="btn btn-outline-primary btn-sm" href="{{ route('profile.edit') }}">Lihat Profile</a>

                            <!-- Informasi Sekolah -->
                            <hr class="w-100">
                            <p class="font-weight-bold mb-0">SMA NEGERI 2 KERINCI KANAN</p>
                            @if (!empty(Auth::user()->getRoleNames()))
                                @foreach (Auth::user()->getRoleNames() as $rolename)
                                    <p class="text-muted">{{ $rolename }}</p>
                                @endforeach
                            @endif


                            <!-- Informasi Total Mata Pelajaran -->
                            <div class="d-flex justify-content-between align-items-center w-100">
                                <div class="d-flex align-items-center">
                                    <img src="{{ asset('assets/img/books.png') }}" alt="Icon"
                                        style="width: 30px; height: 30px;" class="mr-2">
                                    <h6 class="mt-2">Total Mata Pelajaran</h6>
                                </div>
                                <span class="badge badge-info badge-pill">5</span>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Card 2</h5>
                            <p class="card-text">This is the content of card 2.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Card 3</h5>
                            <p class="card-text">This is the content of card 3.</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
