 <!-- Scroll Top -->
 <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

 <!-- Preloader -->
 <div id="preloader"></div>

 <!-- Vendor JS Files -->
 <script src="{{ asset('assets/frontend/landing-page/assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
 <script src="{{ asset('assets/frontend/landing-page/assets/vendor/php-email-form/validate.js') }}"></script>
 <script src="{{ asset('assets/frontend/landing-page/assets/vendor/aos/aos.js') }}"></script>
 <script src="{{ asset('assets/frontend/landing-page/assets/vendor/glightbox/js/glightbox.min.js') }}"></script>
 <script src="{{ asset('assets/frontend/landing-page/assets/vendor/purecounter/purecounter_vanilla.js') }}"></script>
 <script src="{{ asset('assets/frontend/landing-page/assets/vendor/imagesloaded/imagesloaded.pkgd.min.js') }}"></script>
 <script src="{{ asset('assets/frontend/landing-page/assets/vendor/isotope-layout/isotope.pkgd.min.js') }}"></script>
 <script src="{{ asset('assets/frontend/landing-page/assets/vendor/swiper/swiper-bundle.min.js') }}"></script>

 <!-- Main JS File -->
 <script src="{{ asset('assets/frontend/landing-page/assets/js/main.js') }}"></script>

 <script>
    function previewImage(event) {
        const input = event.target;
        const reader = new FileReader();

        reader.onload = function() {
            const preview = document.getElementById('previewFoto');
            preview.src = reader.result; // Tampilkan gambar baru
        };

        if (input.files && input.files[0]) {
            reader.readAsDataURL(input.files[0]); // Baca file yang diunggah
        }
    }
</script>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        flatpickr("#tanggalLahir", {
            dateFormat: "Y-m-d", // Format tanggal sesuai database (YYYY-MM-DD)
            allowInput: true, // Memungkinkan pengguna mengetik tanggal secara manual
            autoclose: true,
            todayHighlight: true
        });
    });
</script>


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<script>
    function showSuccessAlert(message = "Berhasil!") {
        Swal.fire({
            icon: 'success',
            title: '<h4 style="font-size: 18px; margin-bottom: 10px;">' + message + '</h4>',
            confirmButtonText: 'OK',
            confirmButtonColor: '#3085d6',
            width: '400px',
            customClass: {
                popup: 'swal2-popup-custom'
            }
        });
    }

    function showErrorAlert(message = "Terjadi kesalahan!") {
        Swal.fire({
            icon: 'error',
            title: message,
            confirmButtonText: 'OK',
            confirmButtonColor: '#d33',
            width: '400px',
            customClass: {
                popup: 'swal2-popup-custom'
            }
        });
    }

    function confirmDelete(id) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data ini akan dihapus permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Hapus!',
            cancelButtonText: 'Batal',
            width: '400px',
            customClass: {
                popup: 'swal2-popup-custom'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('deleteForm' + id).submit();
            }
        });
    }

    @if (session('success'))
        showSuccessAlert("{{ session('success') }}");
    @endif

    @if (session('error'))
        showErrorAlert("{{ session('error') }}");
    @endif
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.toggle-password').forEach(function(button) {
            button.addEventListener('click', function() {
                const targetId = this.getAttribute('data-target');
                const targetInput = document.getElementById(targetId);
                const icon = this.querySelector('i');

                if (targetInput.type === 'password') {
                    targetInput.type = 'text';
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                } else {
                    targetInput.type = 'password';
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                }
            });
        });
    });
</script>