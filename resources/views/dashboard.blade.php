@extends('layouts.app')

@section('title', 'Dashboard')
<link href="https://cdn.materialdesignicons.com/6.5.95/css/materialdesignicons.min.css" rel="stylesheet">

@section('content')
    <div class="row">
        <div class="col-12 grid-margin">
            <div class="row">

                {{-- FILTER --}}

                @if (Auth::user()->hasRole('Guru'))
                    <div class="col-12 mb-3">
                        <div class="card h-100">
                            <div class="card-body d-flex flex-column justify-content-between">
                                <form method="GET" class="text-center">
                                    <label for="pembelajaran" class="form-label fw-semibold">Filter Pembelajaran:</label>
                                    <select name="pembelajaran" id="pembelajaran" class="form-control mx-auto w-75"
                                        onchange="this.form.submit()">
                                        <option value="">-- Semua Pembelajaran --</option>
                                        @foreach ($pembelajaranList as $pembelajaran)
                                            <option value="{{ $pembelajaran->id }}"
                                                {{ request('pembelajaran') == $pembelajaran->id ? 'selected' : '' }}>
                                                {{ $pembelajaran->nama_mapel }} -
                                                {{ $pembelajaran->kelas->nama_kelas }} -
                                                {{ $pembelajaran->tahunAjaran->nama_tahun ?? 'N/A' }}
                                            </option>
                                        @endforeach
                                    </select>
                                </form>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Card Profile -->
                <div class="col-12 col-sm-6 col-md-4 mb-3">
                    <div class="card h-100">
                        <div class="card-body d-flex flex-column align-items-center my-3">
                            <!-- Foto Profil -->
                            <div class="mb-3 text-center">
                                <img id="previewFoto"
                                    src="{{ Auth::user()->foto ? asset('storage/foto_user/' . Auth::user()->foto) : asset('assets/img/profil.png') }}"
                                    alt="Foto Profil" class="rounded-circle img-fluid"
                                    style="max-width: 100px; max-height: 100px;">
                            </div>

                            <!-- Nama dan Email -->
                            <h5 class="font-weight-bold text-center">{{ Auth::user()->name }}</h5>
                            <label class="badge badge-pill badge-info">{{ Auth::user()->email }}</label>
                            <a class="btn btn-outline-primary btn-sm mt-2" href="{{ route('profile.edit') }}">Lihat
                                Profile</a>

                            <!-- Informasi Sekolah -->
                            <hr class="w-100">
                            <p class="font-weight-bold mb-0 text-center">SMA NEGERI 2 KERINCI KANAN</p>
                            @if (!empty(Auth::user()->getRoleNames()))
                                @if (session()->has('active_role'))
                                    <p class="text-muted">{{ session('active_role') }}</p>
                                @endif
                            @endif

                            <!-- Informasi Total Mata Pelajaran -->
                            @if (Auth::user()->hasRole('Guru'))
                                <div class="d-flex justify-content-between align-items-center w-100 mt-3">
                                    <div class="d-flex align-items-center">
                                        <img src="{{ asset('assets/img/books.png') }}" alt="Icon"
                                            style="width: 30px; height: 30px;" class="mr-2">
                                        <h6 class="mt-2 mb-0">Total Mata Pelajaran</h6>
                                    </div>
                                    <span class="badge badge-info badge-pill">{{ $pembelajaranList->count() }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>


                @if (Auth::user()->hasRole('Super Admin'))
                    <!-- Card 2 -->
                    <div class="col-12 col-sm-6 col-md-4 mb-3">
                        <div class="card h-100">
                            <div class="card-body">
                                <h5 class="card-title text-center">Pengguna per Role</h5>
                                <div style="height: 300px; margin-bottom: 30px;">
                                    <canvas id="userRoleChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Card 3 -->
                    <div class="col-12 col-sm-6 col-md-4 mb-3">
                        <div class="card h-100">
                            <div class="card-body">
                                <h5 class="card-title text-center">Aktivitas Login Perhari</h5>
                                <div style="height: 300px; margin-bottom: 30px;">
                                    <canvas id="loginActivityChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Card 4 -->
                    <div class="col-12 col-sm-6 col-md-4 mb-3">
                        <div class="card h-100">
                            <div class="card-body">
                                <h5 class="card-title text-center">Akses terbanyak Per Role</h5>
                                <div style="height: 300px; margin-bottom: 30px;">
                                    <canvas id="roleAccessChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif


                {{-- Admin --}}

                @if (Auth::user()->hasRole('Admin'))
                    <!-- Card 5 -->
                    <div class="col-12 col-sm-6 col-md-4 mb-3">
                        <div class="card h-100">
                            <div class="card-body">
                                <h5 class="card-title">Jumlah Kurikulum, Kelas, Guru, Siswa, Pembelajaran</h5>
                                <div style="height: 300px;">
                                    <canvas id="countsChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Card 6: Grafik Distribusi Guru Per Mapel -->
                    <div class="col-12 col-sm-6 col-md-4 mb-3">
                        <div class="card h-100">
                            <div class="card-body">
                                <h5 class="card-title">Distribusi Guru Per Mapel</h5>

                                <!-- Filter Tahun Ajaran dan Kelas (sebelahan) -->
                                <form method="GET" action="{{ route('dashboard') }}">
                                    <div class="row">
                                        <div class="col-md-6 form-group">
                                            <label for="tahun_ajaran">Pilih Tahun Ajaran</label>
                                            <select class="form-control" name="tahun_ajaran" id="tahun_ajaran"
                                                onchange="this.form.submit()">
                                                <option value="">Pilih Tahun Ajaran</option>
                                                @foreach ($tahunAjarans as $tahun)
                                                    <option value="{{ $tahun->id }}"
                                                        {{ request('tahun_ajaran') == $tahun->id ? 'selected' : '' }}>
                                                        {{ $tahun->nama_tahun }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-6 form-group">
                                            <label for="kelas">Pilih Kelas</label>
                                            <select class="form-control" name="kelas" id="kelas"
                                                onchange="this.form.submit()">
                                                <option value="">Pilih Kelas</option>
                                                @foreach ($kelas as $kls)
                                                    <option value="{{ $kls->id }}"
                                                        {{ request('kelas') == $kls->id ? 'selected' : '' }}>
                                                        {{ $kls->nama_kelas }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </form>

                                <div style="height: 300px;">
                                    <canvas id="guruPerMapelChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Card: Jumlah Pembelajaran Aktif Per Tahun Ajaran -->
                    <div class="col-12 col-sm-6 col-md-6 mb-3">
                        <div class="card h-100">
                            <div class="card-body">
                                <h5 class="card-title">Jumlah Pembelajaran Aktif Per Tahun Ajaran</h5>

                                <!-- Filter Rentang Tahun Ajaran -->
                                <form method="GET" action="{{ route('dashboard') }}" class="row g-2 mb-3">
                                    <div class="col-md-5">
                                        <label for="tahun_awal" class="form-label">Tahun Ajaran Awal</label>
                                        <select class="form-control" name="tahun_awal" id="tahun_awal">
                                            <option value="">Pilih Tahun Awal</option>
                                            @foreach ($tahunAjarans as $tahun)
                                                <option value="{{ $tahun->id }}"
                                                    {{ request('tahun_awal') == $tahun->id ? 'selected' : '' }}>
                                                    {{ $tahun->nama_tahun }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-5">
                                        <label for="tahun_akhir" class="form-label">Tahun Ajaran Akhir</label>
                                        <select class="form-control" name="tahun_akhir" id="tahun_akhir">
                                            <option value="">Pilih Tahun Akhir</option>
                                            @foreach ($tahunAjarans as $tahun)
                                                <option value="{{ $tahun->id }}"
                                                    {{ request('tahun_akhir') == $tahun->id ? 'selected' : '' }}>
                                                    {{ $tahun->nama_tahun }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-2 d-flex align-items-end">
                                        <button type="submit" class="btn btn-primary w-100"
                                            aria-label="Terapkan Filter">
                                            <i class="mdi mdi-magnify"></i>
                                        </button>
                                    </div>

                                </form>

                                <div style="height: 300px;">
                                    <canvas id="pembelajaranChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>



                    <!-- Card 8 -->
                    <div class="col-12 col-sm-6 col-md-6 mb-3">
                        <div class="card h-100">
                            <div class="card-body py-3 px-4">
                                <h6 class="card-title fw-bold mb-3">Riwayat Aktivitas Terakhir</h6>

                                @if ($activities->isEmpty())
                                    <p class="text-muted small mb-0">Belum ada aktivitas yang tercatat.</p>
                                @else
                                    <div style="max-height: 360px; overflow-y: auto;">
                                        <div class="list-group list-group-flush">
                                            @foreach ($activities as $activity)
                                                <div
                                                    class="list-group-item d-flex align-items-start px-0 py-2 border-0 border-bottom">
                                                    <div class="me-3 mr-5">
                                                        <div class="bg-success-subtle text-success rounded-circle d-flex justify-content-center align-items-center"
                                                            style="width: 35px; height: 35px;">
                                                            <i class="fas fa-list fa-xl mt-4 ml-4 text-primary"></i>
                                                        </div>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <div class="fw-semibold fs-6 text-dark">
                                                            {{ $activity->activity }}
                                                        </div>
                                                        <div class="text-muted fs-6">
                                                            {{ $activity->details }}
                                                        </div>
                                                        <div class="text-muted fs-6">
                                                            <i class="fas fa-clock me-1"></i>
                                                            {{ \Carbon\Carbon::parse($activity->performed_at)->translatedFormat('l, d F Y - H:i') }}
                                                        </div>
                                                    </div>

                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>




                @endif


                {{-- Guru --}}
                @if (Auth::user()->hasRole('Guru'))

                    <!-- Card 9 -->
                    <!-- Card Statistik Guru -->
                    <div class="col-12 col-sm-6 col-md-4 mb-3">
                        <div class="card h-100">
                            <div class="card-body d-flex flex-column justify-content-between h-100">
                                <div>
                                    <h5 class="card-title text-center">Jumlah Materi, Tugas, dan Kuis</h5>
                                </div>

                                {{-- Jumlah Materi, Tugas, dan Kuis --}}
                                <ul class="list-group list-group-flush mt-auto">
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        Materi
                                        <span
                                            class="badge bg-primary rounded-pill text-white">{{ $jumlahMateri ?? 0 }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        Tugas
                                        <span
                                            class="badge bg-success rounded-pill text-white">{{ $jumlahTugas ?? 0 }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        Kuis
                                        <span
                                            class="badge bg-warning rounded-pill text-white">{{ $jumlahKuis ?? 0 }}</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>


                    <!-- Notifikasi Deadline Tugas dan Kuis -->
                    <div class="col-12 col-sm-6 col-md-4 mb-3">
                        <div class="card h-100">
                            <div class="card-body">
                                <h5 class="card-title">Notifikasi Deadline Tugas dan Kuis</h5>
                                <div style="max-height: 300px; overflow-y: auto;">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-borderless">
                                            <thead>
                                                <tr>
                                                    <th>Jenis</th>
                                                    <th>Judul - Mata Pelajaran</th>
                                                    <th>Kelas</th>
                                                    <th>Deadline</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($upcomingTugas as $item)
                                                    <tr>
                                                        <td>
                                                            <span class="badge badge-primary">Tugas</span>
                                                        </td>
                                                        <td class="font-weight-bold">
                                                            {{ $item->tugas->judul }} -
                                                            {{ $item->pembelajaran->nama_mapel }}
                                                        </td>
                                                        <td>{{ $item->pembelajaran->kelas->nama_kelas ?? '-' }}</td>
                                                        <td>{{ \Carbon\Carbon::parse($item->deadline)->format('d M Y') }}
                                                        </td>
                                                    </tr>
                                                @empty
                                                @endforelse

                                                @forelse ($upcomingKuis as $item)
                                                    <tr>
                                                        <td>
                                                            <span class="badge badge-success">Kuis</span>
                                                        </td>
                                                        <td class="font-weight-bold">
                                                            {{ $item->kuis->kategori }} -
                                                            {{ $item->pembelajaran->nama_mapel }}
                                                        </td>
                                                        <td>{{ $item->pembelajaran->kelas->nama_kelas ?? '-' }}</td>
                                                        <td>{{ \Carbon\Carbon::parse($item->deadline)->format('d M Y') }}
                                                        </td>
                                                    </tr>
                                                @empty
                                                @endforelse

                                                @if ($upcomingTugas->isEmpty() && $upcomingKuis->isEmpty())
                                                    <tr>
                                                        <td colspan="4" class="text-center text-muted">
                                                            Tidak ada deadline dalam waktu dekat
                                                        </td>
                                                    </tr>
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>



                    <!-- Card: Progress Tugas -->
                    <div class="col-12 mb-4">
                        <div class="card h-100">
                            <div class="card-body d-flex flex-column justify-content-between h-100">
                                <div>
                                    <h5 class="card-title text-center">Progress Pengumpulan Tugas</h5>
                                </div>

                                {{-- Canvas dengan batas lebar dan tinggi tetap di dalam card --}}
                                <div class="mt-4" style="height: 300px; max-width: 100%; overflow-x: auto;">
                                    <canvas id="progressTugasChart" style="width: 100%; height: 100%;"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Card: Progress Kuis -->
                    <div class="col-12 mb-4">
                        <div class="card h-100">
                            <div class="card-body d-flex flex-column justify-content-between h-100">
                                <div>
                                    <h5 class="card-title text-center">Progress Pengumpulan Kuis</h5>

                                </div>

                                {{-- Canvas kuis --}}
                                <div class="mt-4" style="height: 300px; max-width: 100%; overflow-x: auto;">
                                    <canvas id="progressKuisChart" style="width: 100%; height: 100%;"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                @endif


                {{-- Siswa --}}
                @role('Siswa')
                    <!-- Card 13 -->
                    <div class="col-12 col-sm-6 col-md-4 mb-3">
                        <div class="card h-100">
                            <div class="card-body">
                                <h5 class="card-title">Progress Belajar Per Mapel</h5>
                                <div style="height: 300px;">
                                    <canvas id=""></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Card 14 -->
                    <div class="col-12 col-sm-6 col-md-4 mb-3">
                        <div class="card h-100">
                            <div class="card-body">
                                <h5 class="card-title">Tugas dan Kuis yang harus di selesaikan</h5>
                                <div style="height: 300px;">
                                    <canvas id=""></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Card 15 -->
                    <div class="col-12 col-sm-6 col-md-4 mb-3">
                        <div class="card h-100">
                            <div class="card-body">
                                <h5 class="card-title">Deadline Terdekat</h5>
                                <div style="height: 300px;">
                                    <canvas id=""></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                @endrole

            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Super Admin
            // Grafik Pengguna per Role
            @if (Auth::user()->hasRole('Super Admin'))
                const ctxUserRole = document.getElementById('userRoleChart').getContext('2d');
                new Chart(ctxUserRole, {
                    type: 'doughnut',
                    data: {
                        labels: {!! json_encode($roleNames) !!},
                        datasets: [{
                            label: 'Jumlah Pengguna',
                            data: {!! json_encode($userCounts) !!},
                            backgroundColor: [
                                '#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b', '#858796'
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom'
                            }
                        }
                    }
                });


                // Aktivitas Login Perhari
                const ctxLogin = document.getElementById('loginActivityChart').getContext('2d');
                const loginChart = new Chart(ctxLogin, {
                    type: 'bar',
                    data: {
                        labels: {!! json_encode($dates) !!},
                        datasets: [{
                            label: 'Jumlah Login',
                            data: {!! json_encode($totals) !!},
                            backgroundColor: 'rgba(54, 162, 235, 0.7)',
                            borderRadius: 6,
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    precision: 0
                                }
                            }
                        }
                    }
                });


                // Akses terbanyak per role
                const ctxRoleAccess = document.getElementById('roleAccessChart').getContext('2d');
                const roleAccessChart = new Chart(ctxRoleAccess, {
                    type: 'bar',
                    data: {
                        labels: {!! json_encode($loginRoleLabels) !!},
                        datasets: [{
                            label: 'Total Login',
                            data: {!! json_encode($loginRoleCounts) !!},
                            backgroundColor: 'rgba(255, 159, 64, 0.7)',
                            borderRadius: 6,
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    precision: 0
                                }
                            }
                        }
                    }
                });
            @endif





            // Admin
            // Grafik Jumlah Kurikulum, Kelas, Guru, Siswa, Pembelajaran
            @if (Auth::user()->hasRole('Admin'))
                const ctxCounts = document.getElementById('countsChart').getContext('2d');
                new Chart(ctxCounts, {
                    type: 'bar',
                    data: {
                        labels: ['Kurikulum', 'Kelas', 'Guru', 'Siswa', 'Pembelajaran'],
                        datasets: [{
                            label: 'Jumlah',
                            data: [
                                {{ $kurikulumCount }},
                                {{ $kelasCount }},
                                {{ $guruCount }},
                                {{ $siswaCount }},
                                {{ $pembelajaranCount }}
                            ],
                            backgroundColor: [
                                '#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b'
                            ],
                            borderColor: [
                                '#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b'
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            }
                        },
                        scales: {
                            x: {
                                beginAtZero: true
                            },
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });


                // Data untuk grafik Pembelajaran Aktif Per tahun Ajaran
                const ctxPembelajaran = document.getElementById('pembelajaranChart').getContext('2d');

                @if (!empty($pembelajaranAktifPerTahun))
                    new Chart(ctxPembelajaran, {
                        type: 'bar',
                        data: {
                            labels: {!! json_encode(collect($pembelajaranAktifPerTahun)->pluck('nama')) !!},
                            datasets: [{
                                label: 'Jumlah Pembelajaran Aktif',
                                data: {!! json_encode(collect($pembelajaranAktifPerTahun)->pluck('jumlah')) !!},
                                backgroundColor: '#36b9cc',
                                borderColor: '#36b9cc',
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: false
                                }
                            },
                            scales: {
                                x: {
                                    beginAtZero: true
                                },
                                y: {
                                    beginAtZero: true,
                                    precision: 0
                                }
                            }
                        }
                    });
                @endif


                // Data untuk grafik Distribusi Guru per Mata Pelajaran (Pie Chart)
                const ctxGuruPerMapel = document.getElementById('guruPerMapelChart').getContext('2d');

                new Chart(ctxGuruPerMapel, {
                    type: 'pie', // Ubah tipe menjadi 'pie'
                    data: {
                        labels: @json($labels), // Mata Pelajaran (X-axis)
                        datasets: [{
                            label: 'Jumlah Guru',
                            data: @json($data), // Jumlah Guru (Y-axis)
                            backgroundColor: [
                                '#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b',
                                '#858796',
                                '#ff8c00', '#d3d3d3', '#00008b', '#ff1493'
                            ], // Tambahkan warna-warna berbeda untuk setiap segmen
                            borderColor: '#ffffff',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'top',
                                labels: {
                                    boxWidth: 15
                                }
                            }
                        }
                    }
                });
            @endif



            // Guru
            @if (Auth::user()->hasRole('Guru'))

                // progress tugas
                const tugasData = @json($progressTugas);
                const tugasLabels = tugasData.map(item => item.nama);
                const tugasCollected = tugasData.map(item => item.terkumpul);
                const tugasTotal = tugasData.map(item => item.total);

                new Chart(document.getElementById('progressTugasChart'), {
                    type: 'bar',
                    data: {
                        labels: tugasLabels,
                        datasets: [{
                                label: 'Terkumpul',
                                data: tugasCollected,
                                backgroundColor: 'rgba(54, 162, 235, 0.7)'
                            },
                            {
                                label: 'Total',
                                data: tugasTotal,
                                backgroundColor: 'rgba(201, 203, 207, 0.7)'
                            }
                        ]
                    },
                    options: {
                        indexAxis: 'y',
                        responsive: true,
                        scales: {
                            x: {
                                beginAtZero: true
                            }
                        }
                    }
                });


                // prgress kuis
                const kuisData = @json($progressKuis);
                const kuisLabels = kuisData.map(item => item.nama);
                const kuisCollected = kuisData.map(item => item.terkumpul);
                const kuisTotal = kuisData.map(item => item.total);

                new Chart(document.getElementById('progressKuisChart'), {
                    type: 'bar',
                    data: {
                        labels: kuisLabels,
                        datasets: [{
                                label: 'Terkumpul',
                                data: kuisCollected,
                                backgroundColor: 'rgba(255, 205, 86, 0.7)'
                            },
                            {
                                label: 'Total',
                                data: kuisTotal,
                                backgroundColor: 'rgba(201, 203, 207, 0.7)'
                            }
                        ]
                    },
                    options: {
                        indexAxis: 'y',
                        responsive: true,
                        scales: {
                            x: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            @endif






            // Siswa
        });
    </script>
@endpush
