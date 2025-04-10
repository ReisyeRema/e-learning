<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Kuis Matematika</title>
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
                <a href="<?php echo e(route('mata-pelajaran.show', [
                    'mapel' => Str::slug($pembelajaran->nama_mapel),
                    'kelas' => Str::slug($pembelajaran->kelas->nama_kelas),
                    'tahunAjaran' => str_replace('/', '-', $pembelajaran->tahunAjaran->nama_tahun),
                ])); ?>"
                    class="btn btn-light shadow-sm">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>


            <h3 class="text-center">🧠 Kuis Matematika Dasar</h3>

            <form id="quiz-form">

                <!-- Soal Pilihan Ganda -->
                <div class="question">
                    <div class="question-number">Soal 1</div>
                    <p><strong>Berapakah hasil dari 2 + 2?</strong></p>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="q1" id="q1a" value="a">
                        <label class="form-check-label" for="q1a">3</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="q1" id="q1b" value="b">
                        <label class="form-check-label" for="q1b">4</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="q1" id="q1c" value="c">
                        <label class="form-check-label" for="q1c">5</label>
                    </div>
                </div>

                <!-- Soal Essay -->
                <div class="question">
                    <div class="question-number">Soal 2</div>
                    <p><strong>Jelaskan pengertian dari bilangan prima.</strong></p>
                    <textarea class="form-control mt-2" name="q2" rows="4" placeholder="Tulis jawaban Anda di sini..."></textarea>
                </div>

                <!-- Soal True / False -->
                <div class="question">
                    <div class="question-number">Soal 3</div>
                    <p><strong>Angka 10 adalah bilangan genap. (Benar/Salah)</strong></p>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="q3" id="q3true" value="true">
                        <label class="form-check-label" for="q3true">Benar</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="q3" id="q3false" value="false">
                        <label class="form-check-label" for="q3false">Salah</label>
                    </div>
                </div>

                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-success shadow">Kumpulkan Jawaban</button>
                </div>
            </form>
        </div>
    </main>

    <script>
        document.getElementById("quiz-form").addEventListener("submit", function(e) {
            e.preventDefault();
            alert("Jawaban berhasil dikumpulkan!");
        });
    </script>
</body>

</html>
<?php /**PATH D:\DATA MATKUL\SEMESTER 6\TA\PROJECT\e-learn-laravel\resources\views/pages/siswa/kuis/action.blade.php ENDPATH**/ ?>