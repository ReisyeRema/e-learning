@extends('layouts.forum')

@section('content')
    <div class="text-end mb-4">
        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#tambahForumModal">
            Tanya Yuk
        </button>
    </div>

    <!-- Post Card -->
    @foreach ($forum as $frm)
        @php
            $isGuru = Auth::user()->hasRole('Guru');
            $routeName = $isGuru ? 'forum-diskusi-guru.view' : 'forum-diskusi.view';
        @endphp
        <div class="card p-4 mb-4 shadow border-0 rounded-4 post-card">
            <div class="d-flex justify-content-between">
                <div class="d-flex">
                    <img src="{{ $frm->user->foto ? asset('storage/' . $frm->user->foto) : asset('assets/img/profil.png') }}"
                        class="profile-pic me-3 shadow-sm" alt="user">
                    <div>
                        <h5 class="fw-semibold mb-1">{{ $frm->judul }}</h5>
                        <small class="text-muted">
                            {{ $frm->created_at != $frm->updated_at ? 'Diedit ' . $frm->updated_at->diffForHumans() : $frm->created_at->diffForHumans() }}
                            • Oleh <span class="text-primary fw-semibold">{{ $frm->user->name }}</span>
                        </small>
                    </div>
                </div>
                <div class="text-end d-flex flex-column justify-content-between align-items-end">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <div class="comment-count text-secondary">
                            <i class="bi bi-chat-dots-fill me-1"></i> {{ $frm->komentar ? $frm->komentar->count() : 0 }}
                        </div>

                        @auth
                            @if (Auth::id() === $frm->user_id)
                                <div class="dropdown">
                                    <button class="btn btn-light btn-sm p-1" type="button"
                                        id="dropdownMenuButton{{ $frm->id }}" data-bs-toggle="dropdown"
                                        aria-expanded="false">
                                        <i class="bi bi-three-dots-vertical"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end"
                                        aria-labelledby="dropdownMenuButton{{ $frm->id }}">
                                        <li>
                                            <button class="dropdown-item text-warning" data-bs-toggle="modal"
                                                data-bs-target="#editForumModal{{ $frm->id }}">
                                                <i class="bi bi-pencil-square me-2"></i>Edit
                                            </button>
                                        </li>
                                        <li>
                                            <form id="delete-form-{{ $frm->id }}"
                                                action="{{ route($isGuru ? 'guru.forum.destroy' : 'siswa.forum.destroy', $frm->id) }}"
                                                method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="dropdown-item text-danger btn-delete-forum"
                                                    data-id="{{ $frm->id }}">
                                                    <i class="bi bi-trash3 me-2"></i>Hapus
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            @endif
                        @endauth
                    </div>

                    <a href="{{ route($routeName, [
                        'mapel' => Str::slug($pembelajaran->nama_mapel),
                        'kelas' => Str::slug($pembelajaran->kelas->nama_kelas),
                        'tahunAjaran' => str_replace('/', '-', $pembelajaran->tahunAjaran->nama_tahun),
                        'semester' => Str::slug($pembelajaran->semester),
                        'forum' => $frm->id,
                    ]) }}"
                        class="btn btn-sm btn-primary">Lihat</a>
                </div>
            </div>
        </div>
    @endforeach

    {{-- Modal Tambah Forum --}}
    <div class="modal fade" id="tambahForumModal" tabindex="-1" aria-labelledby="tambahForumModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            @php
                $isGuru = Auth::user()->hasRole('Guru');
                $routeName = $isGuru ? 'guru.forum.store' : 'siswa.forum.store';
            @endphp
            <form action="{{ route($routeName) }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="tambahForumModalLabel">Tambah Forum</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="judul">Judul</label>
                            <input type="text" name="judul" class="form-control" value="{{ old('judul') }}" required>
                        </div>
                        <div class="form-group">
                            <label for="konten">Konten</label>
                            <textarea name="konten" class="form-control summernote" rows="4" required>{!! old('konten') !!}</textarea>
                        </div>
                        <div class="form-group">
                            <input type="hidden" name="pembelajaran_id" value="{{ $pembelajaran->id }}">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Kirim</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @foreach ($forum as $frm)
        @auth
            @if (Auth::id() === $frm->user_id)
                <!-- Modal Edit Forum -->
                <div class="modal fade" id="editForumModal{{ $frm->id }}" tabindex="-1"
                    aria-labelledby="editForumModalLabel{{ $frm->id }}" aria-hidden="true">
                    <div class="modal-dialog modal-xl" role="document">
                        @php
                            $isGuru = Auth::user()->hasRole('Guru');
                            $updateRoute = $isGuru
                                ? route('guru.forum.update', $frm->id)
                                : route('siswa.forum.update', $frm->id);
                        @endphp
                        <form action="{{ $updateRoute }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editForumModalLabel{{ $frm->id }}">Edit Forum</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label for="judul">Judul</label>
                                        <input type="text" name="judul" class="form-control" value="{{ old('judul', $frm->judul) }}"
                                            required>
                                    </div>
                                    <div class="form-group">
                                        <label for="konten">Konten</label>
                                        <textarea name="konten" class="form-control summernote" rows="4" required>{!! old('konten', $frm->konten) !!}</textarea>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-warning">Update</button>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            @endif
        @endauth
    @endforeach
@endsection

@push('styles')
    <link href="{{ asset('skydash/summernote/summernote.min.css') }}" rel="stylesheet">
@endpush

@push('scripts')
    {{-- jQuery jika dibutuhkan --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    {{-- Bootstrap 5 Bundle with Popper --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- include summernote css/js-->
    <script src="{{ asset('skydash/summernote/summernote.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            // Untuk modal tambah forum
            $('#tambahForumModal').on('shown.bs.modal', function() {
                $(this).find('.summernote').not('.summernote-initialized').each(function() {
                    $(this).summernote({
                        placeholder: 'Tulis pertanyaanmu di sini...',
                        tabsize: 2,
                        height: 300
                    }).addClass('summernote-initialized');
                });
            });

            // Untuk semua modal edit forum
            $('div[id^="editForumModal"]').on('shown.bs.modal', function() {
                $(this).find('.summernote').not('.summernote-initialized').each(function() {
                    $(this).summernote({
                        placeholder: 'Edit pertanyaanmu...',
                        tabsize: 2,
                        height: 300
                    }).addClass('summernote-initialized');
                });
            });
        });
    </script>
@endpush
