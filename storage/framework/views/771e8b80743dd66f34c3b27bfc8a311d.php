<footer id="footer" class="footer">

    <div class="container footer-top">
        <div class="row gy-4">
            <div class="col-lg-4 col-md-6 footer-about text-white">
                <a href="index.html" class="d-flex align-items-center">
                    <span class="sitename text-white"><?php echo e($profileSekolah->nama_sekolah); ?></span>
                </a>
                <div class="footer-contact pt-3">
                    <p><?php echo e($profileSekolah->alamat); ?></p>
                    <p>Siak, Pekanbaru, Riau</p>
                    <p class="mt-3"><strong>Phone:</strong> <span><?php echo e($profileSekolah->no_hp); ?></span></p>
                    <p><strong>Email:</strong> <span><?php echo e($profileSekolah->email); ?></span></p>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 footer-links">
                <h4 class="text-white">Useful Links</h4>
                <ul class="list-unstyled">
                    <li><i class="bi bi-chevron-right text-white"></i> <a href="<?php echo e(route('landing-page.index')); ?>#hero" class="text-white">Beranda</a>
                    </li>
                    <li><i class="bi bi-chevron-right text-white"></i> <a href="<?php echo e(route('landing-page.index')); ?>#about" class="text-white">Tentang
                            Kami</a></li>
                    <li><i class="bi bi-chevron-right text-white"></i> <a href="<?php echo e(route('landing-page.index')); ?>#class" class="text-white">Kelas</a>
                    </li>
                    <li><i class="bi bi-chevron-right text-white"></i> <a
                            href="<?php echo e(route('landing-page.index')); ?>#contact" class="text-white">Kontak</a></li>
                </ul>
            </div>

            <div class="col-lg-4 col-md-12 text-lg-end">
                <h4 class="text-white">Ikuti Kami</h4>
                <p  class="text-white">Ikuti kami di media sosial untuk mendapatkan informasi terbaru seputar pembelajaran dan update
                    e-learning.</p>
                <div class="social-links d-flex justify-content-lg-end">
                    <a href=""><i class="bi bi-twitter-x"></i></a>
                    <a href=""><i class="bi bi-facebook"></i></a>
                    <a href=""><i class="bi bi-instagram"></i></a>
                    <a href=""><i class="bi bi-linkedin"></i></a>
                </div>
            </div>
        </div>
    </div>

    <div class="container copyright text-center mt-4 text-white">
        <p>© <span>Copyright</span> <strong class="px-1 sitename">Reisye Rema</strong> <span>All Rights Reserved</span></p>
        <div class="credits">
            Designed by <a href="https://bootstrapmade.com/" class="text-black">BootstrapMade</a>
        </div>
    </div>

</footer>
<?php /**PATH D:\DATA MATKUL\SEMESTER 6\TA\PROJECT\e-learn-laravel\resources\views/components/frontend/footer.blade.php ENDPATH**/ ?>