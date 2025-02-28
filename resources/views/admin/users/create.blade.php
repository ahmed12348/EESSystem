@extends('admin.layouts.app')

@section('title', __('messages.create_user'))

@section('content')
<div class="container">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Breadcrumb -->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">{{ __('messages.users') }}</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item">
                        <a href="{{ url('/') }}"><i class="bx bx-home-alt"></i></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">{{ __('messages.create_user') }}</li>
                </ol>
            </nav>
        </div>
        <div class="ms-auto">
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">{{ __('messages.back') }}</a>
        </div>
    </div>
    <!-- End Breadcrumb -->

    <!-- User Creation Form -->
    <div class="row">
        <div class="col-xl-9 mx-auto">
            <h6 class="mb-0 text-uppercase">{{ __('messages.create_user') }}</h6>
            <hr />
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <!-- Name -->
                        <div class="mb-1">
                            <label for="name" class="form-label">{{ __('messages.name') }}</label>
                            <input class="form-control" type="text" id="name" name="name"
                                placeholder="{{ __('messages.name') }}" required>
                            @error('name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Phone -->
                        <div class="mb-1">
                            <label for="phone" class="form-label">{{ __('messages.phone') }}</label>
                            <input class="form-control" type="text" id="phone" name="phone"
                                placeholder="{{ __('messages.enter_phone') }}" required>
                            @error('phone')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="mb-1">
                            <label for="email" class="form-label">{{ __('messages.email') }}</label>
                            <input class="form-control" type="email" id="email" name="email"
                                placeholder="{{ __('messages.email') }}" required>
                            @error('email')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div class="mb-1">
                            <label for="password" class="form-label">{{ __('messages.password') }}</label>
                            <input class="form-control" type="password" id="password" name="password"
                                placeholder="{{ __('messages.password') }}" required>
                        </div>

                        <!-- Role -->
                        <div class="mb-1">
                            <label for="role" class="form-label">{{ __('messages.role') }}</label>
                            <select class="form-select select2" id="role" name="role">
                                @foreach ($roles as $role)
                                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                                @endforeach
                            </select>
                            @error('role')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Photo -->
                        <div class="mb-3">
                            <label for="photo" class="form-label">{{ __('messages.picture') }}</label>
                            <input class="form-control" type="file" id="photo" name="photo">
                            @error('photo')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                            <img id="photo_preview" src="#" class="mt-2" style="display:none; max-width: 100px;">
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="btn btn-primary">{{ __('messages.create_user') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            // $('.select2').select2();

            $('#photo').on('change', function() {
                let reader = new FileReader();
                reader.onload = function(e) {
                    $('#photo_preview').attr('src', e.target.result).show();
                };
                reader.readAsDataURL(this.files[0]);
            });
        });
    </script>
@endpush
@endsection
