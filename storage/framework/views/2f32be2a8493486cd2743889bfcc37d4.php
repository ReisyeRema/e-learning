<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo e($kuis->kategori); ?> - <?php echo e($kuis->judul); ?></title>
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


            <h3 class="text-center">🧠 <?php echo e($kuis->judul); ?></h3>

            <form id="quiz-form" action="<?php echo e(route('kuis.kumpulkan')); ?>" method="POST" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="kuis_id" value="<?php echo e($kuis->id); ?>">

                <?php $no = 1; ?>

                <?php if($soalKuis->isEmpty()): ?>
                    <div class="alert alert-warning text-center">Belum ada soal yang tersedia untuk kuis ini.</div>
                <?php endif; ?>


                <?php $__currentLoopData = $soalKuis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $soal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="question">
                        <div class="question-number">Soal <?php echo e($no++); ?></div>
                        <p><strong><?php echo e($soal->teks_soal); ?></strong></p>

                        <?php if($soal->gambar): ?>
                            <img src="<?php echo e(asset('storage/' . $soal->gambar)); ?>" alt="Gambar Soal" class="img-fluid mb-3">
                        <?php endif; ?>

                        <?php if($soal->type_soal === 'Objective'): ?>
                            <?php $__currentLoopData = $soal->pilihan_jawaban; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $pilihan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="soal_<?php echo e($soal->id); ?>"
                                        id="soal_<?php echo e($soal->id); ?>_<?php echo e($key); ?>"
                                        value="<?php echo e($key); ?>">
                                    <label class="form-check-label"
                                        for="soal_<?php echo e($soal->id); ?>_<?php echo e($key); ?>"><?php echo e($pilihan); ?></label>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php elseif($soal->type_soal === 'Essay'): ?>
                            <textarea class="form-control mt-2" name="soal_<?php echo e($soal->id); ?>" rows="4"
                                placeholder="Tulis jawaban Anda di sini..."></textarea>
                        <?php elseif($soal->type_soal === 'TrueFalse'): ?>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="soal_<?php echo e($soal->id); ?>"
                                    id="true_<?php echo e($soal->id); ?>" value="true">
                                <label class="form-check-label" for="true_<?php echo e($soal->id); ?>">Benar</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="soal_<?php echo e($soal->id); ?>"
                                    id="false_<?php echo e($soal->id); ?>" value="false">
                                <label class="form-check-label" for="false_<?php echo e($soal->id); ?>">Salah</label>
                            </div>
                        <?php else: ?>
                            <p><em>Tipe soal tidak dikenali</em></p>
                        <?php endif; ?>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                <?php if($soalKuis->count() > 0): ?>
                    <div class="text-center mt-4">
                        <button type="submit" class="btn btn-success shadow">Kumpulkan Jawaban</button>
                    </div>
                <?php endif; ?>

            </form>

        </div>
    </main>

    <script>
        document.getElementById("quiz-form").addEventListener("submit", function(e) {
            e.preventDefault();

            const form = e.target;
            const formData = new FormData(form);
            const unanswered = [];

            <?php $__currentLoopData = $soalKuis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $soal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                const soalId = "soal_<?php echo e($soal->id); ?>";
                const answer = formData.get(soalId);
                if (!answer || answer.trim() === "") {
                    unanswered.push({
                        id: soalId
                    });
                }
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            if (unanswered.length > 0) {
                alert("Harap jawab semua soal terlebih dahulu sebelum mengumpulkan.");
                return;
            }

            fetch("<?php echo e(route('kuis.kumpulkan')); ?>", {
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
<?php /**PATH D:\DATA MATKUL\SEMESTER 6\TA\PROJECT\e-learn-laravel\resources\views/pages/siswa/kuis/action.blade.php ENDPATH**/ ?>