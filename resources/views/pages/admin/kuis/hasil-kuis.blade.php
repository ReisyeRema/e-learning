{{-- @extends('layouts.app')

@section('title', 'Penilaian Kuis')

@section('content')
<div class="container">
    <h4>Penilaian Kuis: {{ $kuis->judul }} - {{ $hasil->siswa->name }}</h4>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('hasil-kuis.updateEssay', ['kuis' => $kuis->id, 'siswa' => $hasil->siswa_id]) }}" method="POST">
        @csrf
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Soal</th>
                    <th>Jawaban Siswa</th>
                    <th>Status</th>
                    <th>Skor</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($jawabanUser as $soalId => $jawaban)
                @php
                    $soal = $soalList[$soalId] ?? null;
                @endphp
            
                @if ($soal)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{!! $soal->teks_soal !!}</td>
                    <td>{!! $jawaban['jawaban'] ?? '-' !!}</td>
                    <td>
                        @if ($soal->type_soal === 'Essay')
                            <select name="jawaban_benar[{{ $soalId }}]" class="form-control">
                                <option value="1" {{ ($jawaban['is_benar'] ?? false) ? 'selected' : '' }}>Benar</option>
                                <option value="0" {{ !($jawaban['is_benar'] ?? false) ? 'selected' : '' }}>Salah</option>
                            </select>
                        @else
                            <span class="badge {{ ($jawaban['is_benar'] ?? false) ? 'badge-success' : 'badge-danger' }}">
                                {{ ($jawaban['is_benar'] ?? false) ? 'Benar' : 'Salah' }}
                            </span>
                        @endif
                    </td>
                    <td>{{ ($jawaban['is_benar'] ?? false) ? ($soal->skor ?? 0) : 0 }}</td>
                </tr>
                @endif
            @endforeach
            
            </tbody>
        </table>

        <button class="btn btn-primary">Simpan Penilaian</button>
    </form>
</div>
@endsection --}}


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
                                <p class="card-title">Penilaian Kuis: {{ $kuis->judul }} - {{ $hasil->siswa->name }}</p>
                            </div>

                            <!-- Table Section -->
                            <form
                                action="{{ route('hasil-kuis.updateEssay', ['kuis' => $kuis->id, 'siswa' => $hasil->siswa_id]) }}"
                                method="POST">
                                @csrf
                                <div class="table-responsive">
                                    <table id="myTable" class="display expandable-table" style="width:100%">

                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Soal</th>
                                                <th>Jawaban Siswa</th>
                                                <th>Jawaban Benar</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($jawabanUser as $soalId => $jawaban)
                                                @php
                                                    $soal = $soalList[$soalId] ?? null;
                                                @endphp

                                                @if ($soal)
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>{!! $soal->teks_soal !!}</td>
                                                        <td>{!! $jawaban['jawaban'] ?? '-' !!}</td>
                                                        <td>{{ $soal->jawaban_benar ?? '-' }}</td>
                                                        <td>
                                                            @if ($soal->type_soal === 'Essay')
                                                                @php
                                                                    // Tentukan nilai default jika belum pernah dinilai
                                                                    $defaultBenar =
                                                                        $jawaban['is_benar'] ??
                                                                        trim(strip_tags($jawaban['jawaban'] ?? '')) ===
                                                                            trim(
                                                                                strip_tags($soal->jawaban_benar ?? ''),
                                                                            );
                                                                @endphp
                                                                <select name="jawaban_benar[{{ $soalId }}]"
                                                                    class="form-control">
                                                                    <option value="1"
                                                                        {{ $defaultBenar ? 'selected' : '' }}>Benar</option>
                                                                    <option value="0"
                                                                        {{ !$defaultBenar ? 'selected' : '' }}>Salah
                                                                    </option>
                                                                </select>
                                                            @else
                                                                @php
                                                                    $status =
                                                                        ($jawaban['jawaban'] ?? '') ===
                                                                        ($soal->jawaban_benar ?? '')
                                                                            ? 'Benar'
                                                                            : 'Salah';
                                                                    $badgeClass =
                                                                        $status === 'Benar'
                                                                            ? 'badge-success'
                                                                            : 'badge-danger';
                                                                @endphp
                                                                <span
                                                                    class="badge {{ $badgeClass }}">{{ $status }}</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                        </tbody>
                                    </table>
                                    <button type="submit" class="btn btn-primary mt-3">Simpan Penilaian</button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
