<link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">


<style>
    body {
        display: flex;
        flex-direction: column;
        min-height: 100vh;
        margin: 0;
    }

    .main {
        flex: 1;
        padding: 100px;
        flex-direction: row;
        display: flex;
    }

    .sidebar {
        width: 300px;
        /* Lebar sidebar tetap */
        padding: 25px;
        position: sticky;
        height: fit-content;
        /* Sesuai dengan isi, tidak ikut memanjang */
        background: #48a6a7;
        color: white;
        box-shadow: 2px 0 5px rgba(221, 220, 220, 0.2);
        border-radius: 5%;

    }

    .sidebar h2 {
        text-align: center;
        font-size: 22px;
        margin-bottom: 20px;
        border-bottom: 2px solid white;
        padding-bottom: 10px;
        font-weight: bold;
        color: white
    }

    .sidebar ul {
        list-style: none;
        padding: 0;
    }

    .sidebar ul li {
        padding: 10px;
        margin: 10px 0;
        border-radius: 5px;
        transition: 0.3s;
    }

    .sidebar ul li a {
        text-decoration: none;
        color: white;
        font-size: 16px;
        display: flex;
        align-items: center;
        font-weight: bold;
    }

    .sidebar ul li a i {
        width: 25px;
        text-align: center;
        margin-right: 10px;
    }

    .sidebar ul li:hover {
        background: white;
    }

    .sidebar ul li:hover a {
        color: #48a6a7;
    }

    .sidebar ul li.active {
        background: white;
        font-weight: bold;
    }

    .sidebar ul li.active a {
        color: #48a6a7;
    }

    .content {
        flex: 1;
        padding: 10px;
        margin-left: 50px;
    }

    .profile-header {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 20px;
    }

    .profile-header img {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        object-fit: cover;
    }

    .profile-header .text {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
    }

    .profile-header .text p {
        margin: 0;
        font-size: 20px;
        color: #586E72;
        min-width: 150px;
    }

    .profile-header .text h2 {
        margin: 0;
        font-size: 24px;
        font-weight: bold;
    }

    .profile-divider-2 {
        border: none;
        height: 3px;
        background: #939393;
        margin: 25px 0;
    }

    .data-diri-box {
        background: white;
        border-radius: 8px;
        padding: 20px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .data-diri-box h3 {
        font-size: 18px;
        font-weight: bold;
    }

    .data-diri-box img {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        object-fit: cover;
        display: block;
        margin: 20px auto;
    }

    @media (max-width: 800px) {
        .sidebar {
            display: none;
        }

        .content {
            padding: 15px;
        }

        .profile-header {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        .profile-header img {
            width: 120px;
            height: 120px;
        }

        .profile-link {
            position: absolute;
            right: 10px;
        }
    }

    /* Responsif untuk layar sangat kecil (480px ke bawah) */
    @media (max-width: 480px) {
        .content {
            padding: 10px;
        }

        .profile-header h2 {
            font-size: 18px;
        }

        .profile-divider {
            margin-bottom: 2rem;
        }
    }
</style>


<style>
    /* Style untuk select dropdown menggunakan Bootstrap */
    select.form-control {
        background-color: #fff;
        border: 1px solid #ced4da;
        border-radius: 5px;
        padding: 10px;
        font-size: 16px;
        color: #495057;
        cursor: pointer;
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;
        background-image: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 4 5'><path fill='%23495057' d='M2 5L0 0h4z'/></svg>");
        background-repeat: no-repeat;
        background-position: right 10px center;
        background-size: 5px;
    }

    select.form-control:focus {
        border-color: #007bff;
        box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
    }

    /* Style ikon kalender */
    .input-group span i.fas.fa-calendar-alt {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        color: #007bff;
        cursor: pointer;
    }

    label {
        font-weight: bold;
    }
</style>