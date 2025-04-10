@extends('layouts.app')

@section('title', 'Data Profile')

@section('content')

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg mb-5">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg mb-5">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>
        </div>
    </div>

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
    
@endsection
