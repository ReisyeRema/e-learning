@extends('layouts.app')

@section('title', 'Data User')

@section('content')
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">Tambah Data Users</h4>

                                    <form action="{{ route('users.store') }}" method="POST" class="forms-sample"
                                        enctype="multipart/form-data">
                                        @csrf

                                        <div class="form-group">
                                            <label for="exampleInputName1">Name</label>
                                            <input name="name" class="form-control @error('name') is-invalid @enderror"
                                                value="{{ old('name') }}" type="text" id="exampleInputName1"
                                                placeholder="Name">
                                            @error('name')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="exampleInputName1">Username</label>
                                            <input name="username"
                                                class="form-control @error('username') is-invalid @enderror"
                                                value="{{ old('username') }}" type="text" id="exampleInputusername1"
                                                placeholder="username">
                                            @error('username')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="exampleInputEmail3">Email address</label>
                                            <input name="email" class="form-control @error('email') is-invalid @enderror"
                                                value="{{ old('email') }}" type="email" id="exampleInputEmail3"
                                                placeholder="Email">
                                            @error('email')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="exampleInputPassword4">Password</label>
                                            <input name="password"
                                                class="form-control  @error('password') is-invalid @enderror"
                                                value="{{ old('password') }}" type="password" id="exampleInputPassword4"
                                                placeholder="Password">

                                            @error('password')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="exampleSelectGender">Role</label>
                                            <select name="roles[]"
                                                class="form-control  @error('roles[]') is-invalid @enderror"
                                                value="{{ old('roles[]') }}" id="exampleSelectGender">
                                                <option value="">Select Role</option>
                                                @foreach ($roles as $item)
                                                    <option value="{{ $item }}">{{ $item }}</option>
                                                @endforeach
                                            </select>
                                            @error('roles[]')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>


                                        <div class="form-group">
                                            <label>Foto</label>
                                            <input type="file" name="foto"
                                                class="form-control  @error('foto') is-invalid @enderror" accept="image/*">

                                            @error('foto')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                            <small class="text-muted">Maximum file size: 2 MB</small>
                                        </div>


                                        <div class="form-group d-flex justify-content-end">
                                            <button type="submit" class="btn btn-primary mr-2">Submit</button>
                                            <a href="{{ route('users.index') }}" class="btn btn-light">Cancel</a>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
