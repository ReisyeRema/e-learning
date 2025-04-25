<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $kuis->kategori }} - {{ $kuis->judul }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" />

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f3f4f6, #ffffff);
            min-height: 100vh;
            padding: 50px;
        }

        body::before {
            content: "";
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: url('{{ asset('assets/frontend/landing-page/assets/img/bg.jpg') }}') no-repeat center center;
            background-size: cover;
            filter: blur(30px);
            z-index: -1;
        }

        .container {
            max-width: 1450px;
            background: white;
            padding: 50px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        }

        h3 {
            font-weight: 700;
            color: #333;
            margin-bottom: 30px;
        }

        .question {
            background-color: #f8f9fa;
            padding: 25px;
            border-radius: 16px;
            margin-bottom: 30px;
            border-left: 6px solid #4f46e5;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.03);
            transition: all 0.3s ease;
        }

        .question:hover {
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
        }

        .question-number {
            font-weight: 600;
            color: #4f46e5;
            margin-bottom: 10px;
        }

        .btn-success {
            padding: 12px 30px;
            font-size: 16px;
            font-weight: 600;
            border-radius: 12px;
        }

        textarea.form-control {
            border-radius: 10px;
        }

        .btn-light {
            border-radius: 10px;
        }
    </style>
</head>

<body>
    <main class="main">
        <div class="container">

            <!-- Tombol Kembali -->
            <div class="mb-4">
                <a href="{{ route('mata-pelajaran.show', [
                    'mapel' => Str::slug($pembelajaran->nama_mapel),
                    'kelas' => Str::slug($pembelajaran->kelas->nama_kelas),
                    'tahunAjaran' => str_replace('/', '-', $pembelajaran->tahunAjaran->nama_tahun),
                ]) }}"
                    class="btn btn-light shadow-sm">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>


            <h3 class="text-center">🧠 {{ $kuis->judul }}</h3>

            <form id="quiz-form" action="{{ route('kuis.kumpulkan') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="kuis_id" value="{{ $kuis->id }}">

                @php $no = 1; @endphp

                @if ($soalKuis->isEmpty())
                    <div class="alert alert-warning text-center">Belum ada soal yang tersedia untuk kuis ini.</div>
                @endif


                @foreach ($soalKuis as $soal)
                    <div class="question">
                        <div class="question-number">Soal {{ $no++ }}</div>
                        <p><strong>{{ $soal->teks_soal }}</strong></p>

                        @if ($soal->gambar)
                            <img src="{{ asset('storage/' . $soal->gambar) }}" alt="Gambar Soal"
                                class="img-fluid mb-3">
                        @endif

                        @if ($soal->type_soal === 'Objective')
                            @foreach ($soal->pilihan_jawaban as $key => $pilihan)
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="soal_{{ $soal->id }}"
                                        id="soal_{{ $soal->id }}_{{ $key }}"
                                        value="{{ $key }}">
                                    <label class="form-check-label"
                                        for="soal_{{ $soal->id }}_{{ $key }}">{{ $pilihan }}</label>
                                </div>
                            @endforeach
                        @elseif ($soal->type_soal === 'Essay')
                            <textarea class="form-control mt-2" name="soal_{{ $soal->id }}" rows="4"
                                placeholder="Tulis jawaban Anda di sini..."></textarea>
                        @elseif ($soal->type_soal === 'TrueFalse')
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="soal_{{ $soal->id }}"
                                    id="true_{{ $soal->id }}" value="true">
                                <label class="form-check-label" for="true_{{ $soal->id }}">Benar</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="soal_{{ $soal->id }}"
                                    id="false_{{ $soal->id }}" value="false">
                                <label class="form-check-label" for="false_{{ $soal->id }}">Salah</label>
                            </div>
                        @else
                            <p><em>Tipe soal tidak dikenali</em></p>
                        @endif
                    </div>
                @endforeach

                @if ($soalKuis->count() > 0)
                    <div class="text-center mt-4">
                        <button type="submit" class="btn btn-success shadow">Kumpulkan Jawaban</button>
                    </div>
                @endif

            </form>

        </div>
    </main>

    <script>
        document.getElementById("quiz-form").addEventListener("submit", function(e) {
            e.preventDefault();

            const form = e.target;
            const formData = new FormData(form);
            const unanswered = [];

            @foreach ($soalKuis as $soal)
                const soalId = "soal_{{ $soal->id }}";
                const answer = formData.get(soalId);
                if (!answer || answer.trim() === "") {
                    unanswered.push({
                        id: soalId
                    });
                }
            @endforeach

            if (unanswered.length > 0) {
                alert("Harap jawab semua soal terlebih dahulu sebelum mengumpulkan.");
                return;
            }

            fetch("{{ route('kuis.kumpulkan') }}", {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content'),
                    },
                    body: formData,
                })
                .then(response => response.json())
                .then(data => {
                    alert("Jawaban berhasil dikumpulkan!");
                    window.location.reload(); // Atau redirect ke halaman lain jika perlu
                })
                .catch(error => {
                    console.error("Terjadi kesalahan:", error);
                    alert("Terjadi kesalahan saat mengirim jawaban.");
                });
        });
    </script>

</body>

</html>
