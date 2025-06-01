@extends('layouts.forum')

@section('content')
    <div class="container mt-3">
        <div class="card shadow rounded-4">
            <div class="card-body">
                <h4 class="card-title fw-bold">{{ $forum->judul }}</h4>
                <small class="text-muted">
                    {{ $forum->created_at != $forum->updated_at ? 'Diedit ' . $forum->updated_at->diffForHumans() : $forum->created_at->diffForHumans() }}
                    • Oleh <span class="text-primary fw-semibold">{{ $forum->user->name }}</span>
                </small>
                <p class="mt-2">{!! $forum->konten !!}</p>
            </div>

            <div class="border-top px-4 py-3 d-flex justify-content-between align-items-center bg-light">
                {{-- <button class="btn btn-outline-primary">
                    <i class="bi bi-hand-thumbs-up"></i> Suka
                </button> --}}
                <button class="btn btn-outline-secondary" id="btn-komentar-utama">
                    <i class="bi bi-chat-dots"></i> Komentar
                </button>
            </div>

            <div class="px-4 py-3 bg-white">
                <form action="" method="POST" id="komentar-utama" style="display: none;">
                    @csrf
                    <input type="hidden" name="forum_id" value="{{ $forum->id }}">
                    <input type="hidden" name="parent" value="0">
                    <textarea name="konten" class="form-control mb-2" id="komentar-utama" rows="3" placeholder="Tulis komentar..."></textarea>
                    <button type="submit" class="btn btn-primary">Kirim</button>
                </form>
            </div>

            <div class="card-body pt-4">
                <h5 class="mb-4 fw-semibold">Komentar</h5>

                <!-- Komentar Utama -->
                @foreach ($forum->komentar()->where('parent', 0)->orderBy('created_at', 'desc')->get() as $komentar)
                    <div class="d-flex mb-4">
                        <!-- Avatar -->
                        <img src="{{ asset('assets/img/profil.png') }}" class="rounded-circle me-3" width="45"
                            height="45" alt="Profile">

                        <!-- Konten komentar + form -->
                        <div class="flex-grow-1">
                            <!-- Komentar -->
                            <p class="mb-1">
                                <strong class="text-primary">{{ $komentar->user->name }}</strong> {{ $komentar->konten }}
                            </p>
                            <small class="text-muted">{{ $komentar->created_at->diffForHumans() }}</small>

                            <!-- Form balasan -->
                            <form method="POST" class="mt-2">
                                @csrf
                                <input type="hidden" name="forum_id" value="{{ $forum->id }}">
                                <input type="hidden" name="parent" value="{{ $komentar->id }}">

                                <!-- ROW khusus form -->
                                <div class="row g-2">
                                    <div class="col">
                                        <input type="text" name="konten" class="form-control"
                                            placeholder="Tulis komentar...">
                                    </div>
                                    <div class="col-auto">
                                        <button type="submit" class="btn btn-primary btn-sm">Kirim</button>
                                    </div>
                                </div>
                            </form>

                            <!-- Balasan -->
                            @foreach ($komentar->childs()->orderBy('created_at', 'desc')->get() as $child)
                                <div class="mt-3 ps-3 border-start">
                                    <p class="mb-1">
                                        <strong class="text-primary">{{ $child->user->name }}</strong>
                                        {{ $child->konten }}
                                    </p>
                                    <small class="text-muted">{{ $child->created_at->diffForHumans() }}</small>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <hr>
                @endforeach

            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#btn-komentar-utama').click(function() {
                $('#komentar-utama').slideToggle();
            });
        });
    </script>
@endpush
