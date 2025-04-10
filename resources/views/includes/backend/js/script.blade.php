<script src="{{ asset('skydash/vendors/js/vendor.bundle.base.js') }}"></script>
<!-- endinject -->
<!-- Plugin js for this page -->
<script src="{{ asset('skydash/vendors/chart.js/Chart.min.js') }}"></script>
<script src="{{ asset('skydash/vendors/datatables.net/jquery.dataTables.js') }}"></script>
<script src="{{ asset('skydash/vendors/datatables.net-bs4/dataTables.bootstrap4.js') }}"></script>
<script src="{{ asset('skydash/js/dataTables.select.min.js') }}"></script>

<!-- End plugin js for this page -->
<!-- inject:js -->
<script src="{{ asset('skydash/js/off-canvas.js') }}"></script>
<script src="{{ asset('skydash/js/hoverable-collapse.js') }}"></script>
<script src="{{ asset('skydash/js/template.js') }}"></script>
<script src="{{ asset('skydash/js/settings.js') }}"></script>
<script src="{{ asset('skydash/js/todolist.js') }}"></script>
<!-- endinject -->
<!-- Custom js for this page-->
<script src="{{ asset('skydash/js/dashboard.js') }}"></script>
<script src="{{ asset('skydash/js/Chart.roundedBarCharts.js') }}"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"
    integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script src="https://cdn.datatables.net/2.2.1/js/dataTables.min.js"></script>

<script>
    $(document).ready(function() {
        $('#myTable').DataTable();
    });
</script>

<script>
    function showSuccessAlert(message = "Berhasil!") {
        Swal.fire({
            icon: 'success',
            title: message,
            showConfirmButton: false,
            timer: 2000,
        });
    }

    function showErrorAlert(message = "Terjadi kesalahan!") {
        Swal.fire({
            icon: 'error',
            title: message,
            showConfirmButton: false,
            timer: 2000,
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
            customClass: {
                icon: 'custom-swal-icon' // Menambahkan kelas kustom langsung ke ikon
            },
            didOpen: () => {
                // Menambahkan margin langsung ke ikon
                const swalIcon = document.querySelector('.swal2-icon');
                if (swalIcon) {
                    swalIcon.style.marginTop = '50px'; // Menambahkan margin 10px ke atas
                }
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
