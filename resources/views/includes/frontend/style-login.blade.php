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
        background: #f2ffed;
        padding: 20px;
        /* Hijau */
    }

    .container {
        display: flex;
        width: 90%;
        width: 1150px;
        background: linear-gradient(135deg, #ffffff, #f4f4f4);
        border-radius: 15px;
        min-height: 750px box-shadow: 0 20px 50px rgba(0, 0, 0, 0.15);
        overflow: hidden;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
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
        width: 100%;
        max-width: 450px;
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
        padding: 5rem;
        text-align: center;
        margin-top: 60px
    }

    .login-section h2 {
        margin-bottom: 1.5rem;
        font-size: 1.8rem;
        color: #28a745;
    }

    .login-section p {
        margin-bottom: 2rem;
        font-size: 1rem;
        color: #555;
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
    }

    .input-group input::placeholder {
        color: #6c757d;
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

    .swal-btn-red:hover {
        background-color: #a00 !important;
    }
</style>
