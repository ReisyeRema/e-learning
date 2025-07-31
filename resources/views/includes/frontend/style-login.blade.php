<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Poppins', sans-serif;
    }

    body {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        padding: 20px;
        background: url('{{ asset('assets/frontend/landing-page/assets/img/bg-login.jpg') }}') no-repeat center center;
        background-size: cover;
    }

    .container {
        display: flex;
        width: 1000px;
        background: white;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        align-items: center;
        position: relative;
        isolation: isolate;
        min-height: 400px;
    }

    .image-section {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 4rem;
        background: #ffffff;
    }

    .image-section img {
        width: 120%;
        max-width: 500px;
        animation: fadeIn 1.2s ease-in-out;
    }

    /* Animasi */
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Bagian kanan (form login) */
    .login-section {
        flex: 1;
        padding: 3.5rem 4rem 3rem;
        background: linear-gradient(135deg, #f9f9f9 0%, #ffffff 100%);
        position: relative;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    /* Tambahkan garis dekoratif horizontal di atas heading */
    .login-section::before {
        content: "";
        position: absolute;
        top: 2.5rem;
        left: 4rem;
        width: 370px;
        height: 4px;
        background: #28a745;
        border-radius: 4px;
    }

    .login-section h2 {
        font-size: 2rem;
        font-weight: 600;
        color: #0c0c0c;
        margin-bottom: 0.5rem;
        text-align: center;
    }

    .login-section p {
        font-size: 1rem;
        color: #6c757d;
        margin-bottom: 2rem;
        text-align: center;
    }


    .input-group {
        position: relative;
        margin-bottom: 1rem;
    }

    .input-group input {
        width: 100%;
        padding: 12px;
        border: 1px solid #28a745;
        border-radius: 8px;
        outline: none;
        background: #ffffff;
        color: #333;
        font-size: 1rem;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.03);
        transition: border 0.3s ease, box-shadow 0.3s ease;
    }

    .logo-wrapper {
        text-align: center;
        margin-bottom: 10px;
    }

    .logo-img {
        height: 100px;
        width: 100px;
        object-fit: contain;
        border-radius: 10px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        animation: zoomIn 0.8s ease-in-out;
    }

    .input-group input::placeholder {
        color: #6c757d;
    }

    .input-group input:focus {
        border-color: #218838;
        box-shadow: 0 0 0 3px rgba(40, 167, 69, 0.1);
    }

    .login-btn {
        width: 100%;
        padding: 12px;
        background: #28a745;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-size: 1rem;
        font-weight: bold;
        color: white;
        margin-top: 1rem;
        transition: 0.3s ease-in-out;
    }

    .login-btn:hover {
        background: #218838;
        transform: translateY(-2px);
    }

    .remember-forgot {
        display: flex;
        justify-content: space-between;
        font-size: 0.9rem;
        margin-top: 0.5rem;
        color: #333;
    }

    .remember-forgot a {
        color: #28a745;
        font-weight: bold;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 5px;
        transition: color 0.3s ease-in-out;
    }

    .remember-forgot a:hover {
        color: #218838;
        text-decoration: underline;
    }


    /* Responsive */
    @media (max-width: 1024px) {
        .container {
            flex-direction: column;
            min-height: auto;
        }

        .image-section {
            display: none;
        }

        .login-section {
            padding: 3rem;
        }
    }

    @media (max-width: 480px) {
        .login-section {
            padding: 2rem;
        }

        .login-section h2 {
            font-size: 1.5rem;
        }

        .login-section p {
            font-size: 0.9rem;
        }

        .input-group input {
            font-size: 0.9rem;
            padding: 10px;
        }

        .login-btn {
            font-size: 0.9rem;
            padding: 10px;
        }

        .remember-forgot {
            font-size: 0.8rem;
            flex-direction: column;
            align-items: center;
            gap: 5px;
        }
    }
</style>

<style>
    .password-container {
        position: relative;
    }

    .password-container input {
        padding-right: 40px;
    }

    .password-container .eye-icon {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        cursor: pointer;
    }

    .swal-btn-red {
        background-color: #d33 !important;
        color: white !important;
        font-size: 16px;
        padding: 10px 20px;
        border-radius: 5px;
    }

    .swal-btn-green {
        background-color: #38c172 !important;
        color: white !important;
        border: none;
        padding: 8px 20px;
        border-radius: 5px;
    }


    .swal-btn-red:hover {
        background-color: #a00 !important;
    }

    .login-footer {
        text-align: center;
        font-size: 0.9rem;
        color: #777;
        margin-top: 20px;
    }

    .image-section {
        position: relative;
    }

    .back-to-landing {
        position: absolute;
        top: 20px;
        left: 20px;
        background-color: rgba(255, 255, 255, 0.9);
        padding: 8px 15px;
        border-radius: 25px;
        font-weight: 600;
        color: #48a6a7;
        text-decoration: none;
        z-index: 10;
        transition: background-color 0.3s ease, color 0.3s ease;
    }

    .back-to-landing i {
        margin-right: 8px;
    }

    .back-to-landing:hover {
        background-color: #48a6a7;
        color: white;
    }
</style>
