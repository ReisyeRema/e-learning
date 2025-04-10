<footer id="footer" class="footer" style="background-color: #fcfbfb">

    <div class="container footer-top">
        <div class="row gy-4">
            <div class="col-lg-4 col-md-6 footer-about">
                <a href="index.html" class="d-flex align-items-center">
                    <span class="sitename"><?php echo e($profileSekolah->nama_sekolah); ?></span>
                </a>
                <div class="footer-contact pt-3">
                    <p><?php echo e($profileSekolah->alamat); ?></p>
                    <p>Siak, Pekanbaru, Riau</p>
                    <p class="mt-3"><strong>Phone:</strong> <span><?php echo e($profileSekolah->no_hp); ?></span></p>
                    <p><strong>Email:</strong> <span><?php echo e($profileSekolah->email); ?></span></p>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 footer-links">
                <h4>Useful Links</h4>
                <ul class="list-unstyled">
                    <li><i class="bi bi-chevron-right"></i> <a href="<?php echo e(route('landing-page.index')); ?>#hero">Beranda</a></li>
                    <li><i class="bi bi-chevron-right"></i> <a href="<?php echo e(route('landing-page.index')); ?>#about">Tentang Kami</a></li>
                    <li><i class="bi bi-chevron-right"></i> <a href="<?php echo e(route('landing-page.index')); ?>#class">Kelas</a></li>
                    <li><i class="bi bi-chevron-right"></i> <a href="<?php echo e(route('landing-page.index')); ?>#contact">Kontak</a></li>
                </ul>
            </div>

            <div class="col-lg-4 col-md-12 text-lg-end">
                <h4>Follow Us</h4>
                <p>Cras fermentum odio eu feugiat lide par naso tierra videa magna derita valies</p>
                <div class="social-links d-flex justify-content-lg-end">
                    <a href=""><i class="bi bi-twitter-x"></i></a>
                    <a href=""><i class="bi bi-facebook"></i></a>
                    <a href=""><i class="bi bi-instagram"></i></a>
                    <a href=""><i class="bi bi-linkedin"></i></a>
                </div>
            </div>
        </div>
    </div>

    <div class="container copyright text-center mt-4">
        <p>© <span>Copyright</span> <strong class="px-1 sitename">eNno</strong> <span>All Rights Reserved</span></p>
        <div class="credits">
            Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a>
        </div>
    </div>

</footer>
<?php /**PATH D:\DATA MATKUL\SEMESTER 6\TA\PROJECT\e-learn-laravel\resources\views/components/frontend/footer.blade.php ENDPATH**/ ?>