

<?php $__env->startSection('title', 'Dashboard Siswa'); ?>

<?php $__env->startSection('content'); ?>

    
    <div class="card shadow-sm p-4 mb-4 bg-white rounded-3">
        <h4 class="mb-4 text-success">Deadline Terdekat</h4>

        <div class="row">
            
            <?php $__currentLoopData = $tugasDeadline; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tugas): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col-md-6 mb-3">
                    <div class="border-start border-4 border-primary ps-3">
                        <h6 class="text-primary fw-bold mb-1"><?php echo e($tugas->tugas->judul); ?></h6>
                        <small class="text-muted d-block mb-1">
                            Mapel: <?php echo e($tugas->pembelajaran->nama_mapel); ?>

                        </small>
                        <small class="text-danger fw-semibold">
                            Deadline: <?php echo e(\Carbon\Carbon::parse($tugas->deadline)->translatedFormat('d M Y H:i')); ?>

                        </small>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            
            <?php $__currentLoopData = $kuisDeadline; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kuis): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col-md-6 mb-3">
                    <div class="border-start border-4 border-warning ps-3">
                        <h6 class="text-warning fw-bold mb-1"><?php echo e($kuis->kuis->judul); ?></h6>
                        <small class="text-muted d-block mb-1">
                            Mapel: <?php echo e($kuis->pembelajaran->nama_mapel); ?>

                        </small>
                        <small class="text-danger fw-semibold">
                            Deadline: <?php echo e(\Carbon\Carbon::parse($kuis->deadline)->translatedFormat('d M Y H:i')); ?>

                        </small>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            <?php if($tugasDeadline->isEmpty() && $kuisDeadline->isEmpty()): ?>
                <div class="col-12">
                    <div class="alert alert-secondary">
                        Tidak ada tugas atau kuis yang akan segera deadline.
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>


    
    <div class="card shadow-sm p-4 mb-4 bg-white rounded-3">
        <h4 class="mb-4 text-success">Status Pembelajaran Terakhir</h4>

        <div class="row">
            <?php $__currentLoopData = $statusPembelajaran; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="col-md-12 mb-3">
                <div class="card p-3 border-0 shadow-sm bg-white rounded-3">
                    <div class="d-flex align-items-center mb-3">
                        <div class="icon-container" style="width: 40px; height: 40px; border-radius: 50%; background-color: <?php echo e($status->status == 'selesai' ? '#28a745' : '#ffc107'); ?>; display: flex; align-items: center; justify-content: center;">
                            <i class="fas <?php echo e($status->status == 'selesai' ? 'fa-check' : 'fa-clock'); ?> text-white"></i>
                        </div>
                        <h6 class="ms-3 fw-bold mb-0 text-dark"><?php echo e($status->pembelajaran->nama_mapel); ?></h6>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        
                        <small class="text-muted">
                            Terakhir: <?php echo e(\Carbon\Carbon::parse($status->updated_at)->translatedFormat('d M Y H:i')); ?>

                        </small>
                    </div>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>



    
    <div class="card shadow-sm p-4 mb-4 bg-white rounded-3">
        <h4 class="mb-4 text-success">Progress Belajar</h4>

        
        <form method="GET" id="filterForm" class="mb-4">
            <div class="row align-items-center">
                <div class="col-md-3 col-sm-4">
                    <label for="pembelajaran_key" class="form-label fw-semibold mb-0">Pilih Pembelajaran:</label>
                </div>
                <div class="col-md-9 col-sm-8">
                    <select name="pembelajaran_key" id="pembelajaran_key" class="form-select"
                        onchange="document.getElementById('filterForm').submit();">
                        <option value="">-- Semua Pembelajaran --</option>
                        <?php $__currentLoopData = $pembelajaranList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                $key = $p->nama_mapel . '|' . $p->kelas->id . '|' . $p->tahunAjaran->id;
                            ?>
                            <option value="<?php echo e($key); ?>"
                                <?php echo e(request('pembelajaran_key') == $key ? 'selected' : ''); ?>>
                                <?php echo e($p->nama_mapel); ?> - <?php echo e($p->kelas->nama_kelas); ?> - <?php echo e($p->tahunAjaran->nama_tahun); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
            </div>
        </form>


        
        <?php if($progressList->isNotEmpty()): ?>
            <div class="row">
                <?php $__currentLoopData = $progressList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $progress): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $onlyOneChart = $progress['tugas_total'] == 0 || $progress['kuis_total'] == 0;
                        $canvasSize = $onlyOneChart ? 220 : 150;
                    ?>

                    <div class="col-md-12 mb-4">
                        <h6 class="text-primary text-center mb-4"><?php echo e($progress['pembelajaran']->nama); ?></h6>
                        <div class="row justify-content-center gap-2">
                            <?php if($progress['tugas_total'] > 0): ?>
                                <div
                                    class="<?php echo e($onlyOneChart ? 'col-md-6' : 'col-md-5'); ?> d-flex flex-column align-items-center justify-content-center">
                                    <canvas id="tugasChart-<?php echo e($i); ?>" width="<?php echo e($canvasSize); ?>"
                                        height="<?php echo e($canvasSize); ?>"></canvas>
                                    <p class="mt-3 small text-muted text-center">
                                        Tugas: <?php echo e($progress['tugas_selesai']); ?>/<?php echo e($progress['tugas_total']); ?>

                                    </p>
                                </div>
                            <?php endif; ?>

                            <?php if($progress['kuis_total'] > 0): ?>
                                <div
                                    class="<?php echo e($onlyOneChart ? 'col-md-6' : 'col-md-5'); ?> d-flex flex-column align-items-center justify-content-center">
                                    <canvas id="kuisChart-<?php echo e($i); ?>" width="<?php echo e($canvasSize); ?>"
                                        height="<?php echo e($canvasSize); ?>"></canvas>
                                    <p class="mt-3 small text-muted text-center">
                                        Kuis: <?php echo e($progress['kuis_selesai']); ?>/<?php echo e($progress['kuis_total']); ?>

                                    </p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php else: ?>
            <div class="alert alert-warning mt-3">
                Tidak ada data pembelajaran untuk mapel ini.
            </div>
        <?php endif; ?>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        <?php $__currentLoopData = $progressList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $progress): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            // Tugas Chart
            new Chart(document.getElementById('tugasChart-<?php echo e($i); ?>'), {
                type: 'doughnut',
                data: {
                    labels: ['Selesai', 'Belum'],
                    datasets: [{
                        data: [
                            <?php echo e($progress['tugas_selesai']); ?>,
                            <?php echo e($progress['tugas_total'] - $progress['tugas_selesai']); ?>

                        ],
                        backgroundColor: ['#0d6efd', '#e9ecef'],
                        borderWidth: 1
                    }]
                },
                options: {
                    cutout: '60%',
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return context.label + ': ' + context.raw;
                                }
                            }
                        }
                    }
                }
            });

            // Kuis Chart
            new Chart(document.getElementById('kuisChart-<?php echo e($i); ?>'), {
                type: 'doughnut',
                data: {
                    labels: ['Selesai', 'Belum'],
                    datasets: [{
                        data: [
                            <?php echo e($progress['kuis_selesai']); ?>,
                            <?php echo e($progress['kuis_total'] - $progress['kuis_selesai']); ?>

                        ],
                        backgroundColor: ['#ffc107', '#e9ecef'],
                        borderWidth: 1
                    }]
                },
                options: {
                    cutout: '60%',
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return context.label + ': ' + context.raw;
                                }
                            }
                        }
                    }
                }
            });
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\DATA MATKUL\SEMESTER 6\TA\PROJECT\e-learn-laravel\resources\views/pages/siswa/dashboard/index.blade.php ENDPATH**/ ?>