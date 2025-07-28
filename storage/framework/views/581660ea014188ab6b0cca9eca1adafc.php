<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Index - eNno Bootstrap Template</title>
    <meta name="description" content="">
    <meta name="keywords" content="">

    <?php echo $__env->make('includes.frontend.style', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <style>
        .service-item {
            background-color: #fff;
            /* agar bayangan terlihat jelas */
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
            /* bayangan halus */
            transition: all 0.3s ease;
        }

        .service-item:hover {
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
            /* bayangan lebih besar saat hover */
            transform: translateY(-5px);
            /* sedikit naik saat hover */
        }
    </style>
</head>

<body class="index-page">

    <?php echo $__env->make('components.frontend.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <main class="main">

        <!-- Hero Section -->
        <section id="hero" class="hero section">

            <div class="container">
                <div class="row gy-4">
                    <div class="col-lg-6 order-2 order-lg-1 d-flex flex-column justify-content-center"
                        data-aos="fade-up">
                        <h1>Belajar Lebih Mudah dan Terstruktur</h1>
                        <p>Akses materi, tugas, dan kuis secara online dengan platform e-learning kami. Tingkatkan
                            pengalaman belajar di mana saja, kapan saja.</p>
                        <div class="d-flex">
                            <a href="<?php echo e(route('login-siswa')); ?>" class="btn-get-started">Get Started</a>
                        </div>
                    </div>
                    <div class="col-lg-6 order-1 order-lg-2 hero-img" data-aos="zoom-out" data-aos-delay="100">
                        <img src="<?php echo e(asset('assets/frontend/landing-page/assets/img/hero-img.png')); ?>"
                            class="img-fluid animated" alt="">
                    </div>
                </div>
            </div>

        </section><!-- /Hero Section -->

        <!-- Stats Section -->
        <section id="stats" class="stats section">
            <div class="container" data-aos="fade-up" data-aos-delay="100">
                <div class="row gy-4">

                    <div class="col-lg-3 col-md-6">
                        <div class="stats-item text-center w-100 h-100">
                            <span data-purecounter-start="0" data-purecounter-end="<?php echo e($jumlahGuru); ?>"
                                data-purecounter-duration="1" class="purecounter"></span>
                            <p>Guru</p>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <div class="stats-item text-center w-100 h-100">
                            <span data-purecounter-start="0" data-purecounter-end="<?php echo e($jumlahSiswa); ?>"
                                data-purecounter-duration="1" class="purecounter"></span>
                            <p>Siswa</p>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <div class="stats-item text-center w-100 h-100">
                            <span data-purecounter-start="0" data-purecounter-end="<?php echo e($jumlahKelas); ?>"
                                data-purecounter-duration="1" class="purecounter"></span>
                            <p>Kelas</p>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <div class="stats-item text-center w-100 h-100">
                            <span data-purecounter-start="0" data-purecounter-end="<?php echo e($jumlahMapel); ?>"
                                data-purecounter-duration="1" class="purecounter"></span>
                            <p>Mata Pelajaran</p>
                        </div>
                    </div>

                </div>
            </div>
        </section>
        <!-- /Stats Section -->


        <!-- Tentang Kami Section -->
        <section id="about" class="about section">

            <!-- Section Title -->
            <div class="container section-title" data-aos="fade-up">
                <span>Tentang Kami<br></span>
                <h2>Platform E-Learning</h2>
                <p>Kami hadir untuk memudahkan proses belajar mengajar secara online dengan fitur yang lengkap, efisien,
                    dan mudah digunakan.</p>
            </div>

            <div class="container">

                <div class="row gy-4">
                    <div class="col-lg-6 position-relative align-self-start" data-aos="fade-up" data-aos-delay="100">
                        <img src="<?php echo e(asset('assets/frontend/landing-page/assets/img/about.png')); ?>" class="img-fluid"
                            alt="">
                        <a href="https://www.youtube.com/watch?v=Y7f98aduVJ8" class="glightbox pulsating-play-btn"></a>
                    </div>
                    <div class="col-lg-6 content" data-aos="fade-up" data-aos-delay="200">
                        <h3>Meningkatkan Pengalaman Belajar Secara Digital</h3>
                        <p class="fst-italic">
                            Sistem kami dirancang untuk mendukung pembelajaran daring yang interaktif dan fleksibel,
                            cocok untuk siswa, guru, maupun admin sekolah.
                        </p>
                        <ul>
                            <li><i class="bi bi-check2-all"></i> <span>Manajemen materi, tugas, dan kuis yang terpusat
                                    dan terstruktur.</span></li>
                            <li><i class="bi bi-check2-all"></i> <span>Pemantauan progres siswa secara real-time.</span>
                            </li>
                            <li><i class="bi bi-check2-all"></i> <span>Antarmuka yang ramah pengguna dan mudah digunakan
                                    di berbagai perangkat.</span></li>
                        </ul>
                        <p>
                            Kami percaya bahwa teknologi dapat mempermudah akses pendidikan. Dengan platform ini,
                            pembelajaran bisa dilakukan kapan saja dan di mana saja.
                        </p>
                    </div>
                </div>

            </div>

        </section><!-- /About Section -->

        <!-- Class Section -->
        <section id="class" class="services section light-background">
            <div class="container section-title" data-aos="fade-up">
                <span>Kelas</span>
                <p class="mt-5">Daftar kelas yang tersedia dalam sistem</p>
            </div>

            <div class="container">
                <div class="row gy-4">
                    <?php $__currentLoopData = $kelas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kls): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
                            <div class="service-item position-relative">
                                <div class="icon" style="background: #48a6a7">
                                    <i class="bi bi-book"></i>
                                </div>
                                <a href="<?php echo e(route('kelas.show', $kls->id)); ?>" class="stretched-link">
                                    <h3 style="color: #48a6a7"><?php echo e($kls->nama_kelas); ?></h3>
                                </a>
                                <p>Kelas ini memiliki sejumlah siswa yang terdaftar.</p>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </section>
        <!-- Class Section -->


        <!-- Contact Section -->
        <section id="contact" class="contact section">

            <!-- Section Title -->
            <div class="container section-title" data-aos="fade-up">
                <span>Hubungi Kami</span>
                <h2>Kontak</h2>
                <p>Jika Anda memiliki pertanyaan, saran, atau membutuhkan bantuan terkait platform e-learning kami,
                    jangan ragu untuk menghubungi kami. Tim kami siap membantu Anda.</p>
            </div><!-- End Section Title -->


            <div class="container" data-aos="fade-up" data-aos-delay="100">

                <div class="row gy-4">

                    <div class="col-lg-5">

                        <div class="info-wrap">
                            <div class="info-item d-flex" data-aos="fade-up" data-aos-delay="200">
                                <i class="bi bi-geo-alt flex-shrink-0"></i>
                                <div>
                                    <h3>Address</h3>
                                    <p><?php echo e($profileSekolah->nama_sekolah); ?></p>
                                </div>
                            </div><!-- End Info Item -->

                            <div class="info-item d-flex" data-aos="fade-up" data-aos-delay="300">
                                <i class="bi bi-telephone flex-shrink-0"></i>
                                <div>
                                    <h3>Call Us</h3>
                                    <p><?php echo e($profileSekolah->no_hp); ?></p>
                                </div>
                            </div><!-- End Info Item -->

                            <div class="info-item d-flex" data-aos="fade-up" data-aos-delay="400">
                                <i class="bi bi-envelope flex-shrink-0"></i>
                                <div>
                                    <h3>Email Us</h3>
                                    <p><?php echo e($profileSekolah->email); ?></p>
                                </div>
                            </div><!-- End Info Item -->

                            <iframe
                                src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d48389.78314118045!2d-74.006138!3d40.710059!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89c25a22a3bda30d%3A0xb89d1fe6bc499443!2sDowntown%20Conference%20Center!5e0!3m2!1sen!2sus!4v1676961268712!5m2!1sen!2sus"
                                frameborder="0" style="border:0; width: 100%; height: 270px;" allowfullscreen=""
                                loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                        </div>
                    </div>

                    <div class="col-lg-7">
                        <?php if(session('success')): ?>
                            <div class="alert alert-success"><?php echo e(session('success')); ?></div>
                        <?php endif; ?>
                    
                        <form action="<?php echo e(route('kontak.submit')); ?>" method="post">
                            <?php echo csrf_field(); ?>
                            <div class="row gy-4">
                    
                                <div class="col-md-6">
                                    <label for="name-field" class="pb-2">Your Name</label>
                                    <input name="nama" type="text" id="name-field" class="form-control" required>
                                </div>
                    
                                <div class="col-md-6">
                                    <label for="email-field" class="pb-2">Your Email</label>
                                    <input name="email" type="email" class="form-control" id="email-field" required>
                                </div>
                    
                                <div class="col-md-12">
                                    <label for="subject-field" class="pb-2">Subject</label>
                                    <input name="subjek" type="text" class="form-control" id="subject-field" required>
                                </div>
                    
                                <div class="col-md-12">
                                    <label for="message-field" class="pb-2">Message</label>
                                    <textarea name="pesan" class="form-control" rows="10" id="message-field" required></textarea>
                                </div>
                    
                                <div class="col-md-12 text-center">                    
                                    <button type="submit" class="btn btn-success">Send Message</button>
                                </div>
                    
                            </div>
                        </form>
                    </div><!-- End Contact Form -->

                </div>

            </div>

        </section><!-- /Contact Section -->

    </main>

    <?php echo $__env->make('components.frontend.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <?php echo $__env->make('includes.frontend.script', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

</body>

</html>
<?php /**PATH D:\DATA MATKUL\SEMESTER 6\TA\PROJECT\e-learn-laravel\resources\views/pages/frontend/index.blade.php ENDPATH**/ ?>