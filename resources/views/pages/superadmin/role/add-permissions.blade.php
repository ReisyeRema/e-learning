@extends('layouts.app')

@section('title', 'Data Role')

@section('content')
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body p-5">
                    <div class="row">

                        <!-- Role Name -->
                        <div class="mb-2">
                            <h1 class="card-title text-primary">
                                <i class="fa fa-th-list"></i> Role: {{ $role->name }}
                            </h1>
                        </div>

                        <!-- Form to Add Permissions -->
                        <form action="{{ route('roles.addPermissionToRole', $role->id) }}" method="POST" class="w-100">
                            @csrf
                            @method('PUT')

                            <!-- Permissions Section -->
                            <div class="form-group">
                                <label for="permissions">Permissions</label>
                                <div class="row">
                                    @foreach ($permissions as $item)
                                        <div class="col-md-3">
                                            <div class="form-check form-check-flat">
                                                <label class="form-check-label">
                                                    <input class="checkbox" type="checkbox" name="permission[]" 
                                                    value="{{ $item->name }}" 
                                                            {{ in_array($item->id, $rolePermissions) ? 'checked' : '' }}>
                                                    {{ $item->name }}
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                    
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="tile-footer mt-4">
                                <button class="btn btn-primary" type="submit">Update</button>
                                <a href="{{ route('roles.index') }}" class="btn btn-light">Cancel</a>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
