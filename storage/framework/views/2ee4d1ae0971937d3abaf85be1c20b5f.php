<style>
    body {
        background-color: #f8f9fa;
        font-family: 'Poppins', sans-serif;
    }

    .container {
        padding-top: 10px;
    }

    h2 {
        text-align: center;
        font-weight: 800;
        margin-bottom: 50px;
        color: #212529;
        letter-spacing: 1.5px;
    }

    .card {
        border: none;
        border-radius: 20px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        overflow: hidden;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        background: #fff;
    }

    .card:hover {
        transform: translateY(-12px);
        box-shadow: 0 18px 40px rgba(0, 0, 0, 0.25);
    }

    .card-cover {
        width: 100%;
        height: 200px;
        object-fit: cover;
    }

    .card-body {
        padding: 24px;
        text-align: center;
    }

    .card-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #343a40;
        margin-bottom: 15px;
    }

    .card-text {
        font-size: 1rem;
        color: #6c757d;
        margin-bottom: 12px;
    }

    .badge-guru {
        background: #0d6efd;
        color: #fff;
        font-size: 0.9rem;
        padding: 8px 14px;
        border-radius: 25px;
        font-weight: 600;
    }

    .btn-daftar {
        background: linear-gradient(135deg, #20c997, #198754);
        color: #fff;
        padding: 12px 24px;
        border: none;
        border-radius: 30px;
        cursor: pointer;
        transition: background 0.3s ease, transform 0.3s ease;
        font-weight: 600;
        margin-top: 5px;
    }

    .btn-daftar:hover {
        background: linear-gradient(135deg, #198754, #157347);
        transform: translateY(-3px);
    }

    .list-group-item.active {
        background-color: #48a6a7 !important;
        border-color: #48a6a7 !important;
        color: #fff;
    }

    .bg-sidebar {
        background-color: #f2f4f4;
    }
    .bg-sidebar h5{
        color: #48a6a7;
        font-weight: bold;
    }

    @media (max-width: 768px) {
        .container {
            padding-top: 40px;
        }
    }
</style>
<?php /**PATH D:\DATA MATKUL\SEMESTER 6\TA\PROJECT\e-learn-laravel\resources\views/includes/frontend/style-kelas.blade.php ENDPATH**/ ?>