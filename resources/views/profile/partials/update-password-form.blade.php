<section>
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h2 class="card-title">Update Password</h2>
                <p class="card-description">
                    Pastikan akun Anda menggunakan kata sandi yang panjang dan acak agar tetap aman.
                </p>
                <form class="forms-sample" method="post" action="{{ route('password.update') }}">

                    @csrf
                    @method('put')

                    <div class="form-group row">
                        <label for="update_password_current_password" class="col-sm-3 col-form-label">Password Lama</label>
                        <div class="col-sm-9">
                            <div class="input-group">
                                <input name="current_password" type="password" class="form-control" id="update_password_current_password" placeholder="Password Lama" autocomplete="current-password">
                                <div class="ml-2">
                                    <button type="button" class="btn btn-sm btn-inverse-info toggle-password" data-target="update_password_current_password">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                            @if ($errors->updatePassword->has('current_password'))
                                <span class="text-danger mt-2 d-block">
                                    {{ $errors->updatePassword->first('current_password') }}
                                </span>
                            @endif
                        </div>
                    </div>
                    

                    <div class="form-group row">
                        <label for="update_password_password" class="col-sm-3 col-form-label">Password Baru</label>
                        <div class="col-sm-9">
                            <div class="input-group">
                                <input id="update_password_password" name="password" type="password" class="form-control" placeholder="Password Baru" autocomplete="new-password">
                                <div class="ml-2">
                                    <button type="button" class="btn btn-sm btn-inverse-info toggle-password" data-target="update_password_password">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                            @if ($errors->updatePassword->has('password'))
                                <span class="text-danger mt-2 d-block">
                                    {{ $errors->updatePassword->first('password') }}
                                </span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <label for="update_password_password_confirmation" class="col-sm-3 col-form-label">Konfirmasi Password</label>
                        <div class="col-sm-9">
                            <div class="input-group">
                                <input name="password_confirmation" type="password" class="form-control" id="update_password_password_confirmation" placeholder="Konfirmasi Password" autocomplete="new-password">
                                <div class="ml-2">
                                    <button type="button" class="btn btn-sm btn-inverse-info toggle-password" data-target="update_password_password_confirmation">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                            @if ($errors->updatePassword->has('password_confirmation'))
                                <span class="text-danger mt-2 d-block">
                                    {{ $errors->updatePassword->first('password_confirmation') }}
                                </span>
                            @endif
                        </div>
                    </div>                    

                    <div class="flex items-center gap-4">
                        <button class="btn btn-primary mr-2">{{ __('Save') }}</button>

                        @if (session('status') === 'password-updated')
                            <script>
                                document.addEventListener('DOMContentLoaded', function() {
                                    showSuccessAlert('Password berhasil diperbarui!');
                                });
                            </script>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

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
