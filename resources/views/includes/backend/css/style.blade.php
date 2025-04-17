 <!-- plugins:css -->
 <link rel="stylesheet" href="{{ asset('skydash/vendors/feather/feather.css') }}">
 <link rel="stylesheet" href="{{ asset('skydash/vendors/ti-icons/css/themify-icons.css') }}">
 <link rel="stylesheet" href="{{ asset('skydash/vendors/css/vendor.bundle.base.css') }}">
 <!-- endinject -->
 <!-- Plugin css for this page -->
 <link rel="stylesheet" href="{{ asset('skydash/vendors/datatables.net-bs4/dataTables.bootstrap4.css') }}">
 <link rel="stylesheet" href="{{ asset('skydash/vendors/ti-icons/css/themify-icons.css') }}">
 <link rel="stylesheet" type="text/css" href="{{ asset('skydash/js/select.dataTables.min.css') }}">
 <!-- End plugin css for this page -->
 <!-- inject:css -->
 <link rel="stylesheet" href="{{ asset('skydash/css/vertical-layout-light/style.css') }}">
 <!-- endinject -->
 <link rel="shortcut icon" href="{{ url('storage/logo_sekolah/' . $profilSekolah->foto) }}" />
 <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">


 <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
 <link rel="stylesheet" href="https://cdn.datatables.net/2.2.1/css/dataTables.dataTables.min.css">


 <link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" rel="stylesheet">

 <style>
     .custom-swal-icon {
         margin-top: 10px;
     }

     .profile-picture {
         width: 120px;
         height: 120px;
         border: 3px solid #424a53;
         border-radius: 50%;
         display: flex;
         align-items: center;
         justify-content: center;
         overflow: hidden;
     }

     .profile-picture img {
         width: 120px;
         height: 120px;
         object-fit: cover;
         border-radius: 50%;
     }

     .upload-box {
         border: 2px dashed #007bff;
         border-radius: 5px;
         padding: 30px;
         text-align: center;
         cursor: pointer;
         position: relative;
         background-color: #fdf9f9;
     }

     .upload-area {
         color: #333;
         font-size: 16px;
     }

     .upload-area i {
         font-size: 24px;
         color: #007bff;
         display: block;
         margin-bottom: 10px;
     }

     .browse-text {
         color: #007bff;
         font-weight: bold;
         cursor: pointer;
     }

     .file-input {
         position: absolute;
         width: 100%;
         height: 100%;
         opacity: 0;
         cursor: pointer;
         left: 0;
         top: 0;
     }
 </style>

<style>
    .sidebar .nav-items {
        position: relative;
        list-style: none;
        transition: background 0.3s ease-in-out;
        border-radius: 8px;
        margin: 2px;
    }

    .sidebar .nav-items:hover,
    .sidebar .nav-items.active {
        background: #4B49AC;
    }

    .sidebar .nav-items.active .nav-link {
        background: #4B49AC;
        color: white;
        border-radius: 8px;
    }

    .sidebar .nav-items .nav-link {
        display: flex;
        align-items: center;
        padding: 10px 15px;
        color: #333;
        text-decoration: none;
        transition: color 0.3s ease-in-out, background 0.3s ease-in-out;
        border-radius: 8px;
    }

    .sidebar .nav-items .nav-link:hover {
        color: #ffffff;
    }

    .sidebar .nav-items.active .menu-icon {
        color: white;
    }

    .sidebar .nav-items .menu-item {
        display: flex;
        align-items: center;
        gap: 12px;
        width: 100%;
    }

    .sidebar .nav-items .menu-title {
        font-size: 14px;
        font-weight: 500;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .sidebar .menu-icon {
        font-size: 18px;
        min-width: 30px;
        text-align: center;
        color: #555;
        transition: color 0.3s ease-in-out;
    }

    .sidebar .nav-items .nav-link:hover .menu-icon {
        color: #ffffff;
    }
</style>
