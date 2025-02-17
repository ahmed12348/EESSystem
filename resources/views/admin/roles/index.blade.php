@extends('admin.layouts.app')

@section('title', __('messages.roles_dashboard'))

@section('content')
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">{{ __('messages.roles') }}</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ __('messages.role_management') }}</li>
                </ol>
            </nav>
        </div>
        
        <div class="ms-auto">
            <div class="btn-group">
                    <a class="btn btn-info text-white" href="{{ route('admin.roles.create') }}">
                        <i class="bi bi-plus-circle"></i> {{ __('messages.create_new_role') }}
                    </a>
            </div>
        </div>
    </div>
    <!--end breadcrumb-->

    <div class="card">
        <div class="card-body">
            <div class="d-flex align-items-center">
                <h5 class="mb-0">{{ __('messages.roles') }}</h5>

                <form class="ms-auto position-relative" method="GET" action="{{ route('admin.roles.index') }}">
                    <div class="position-absolute top-50 translate-middle-y search-icon px-3">
                        <i class="bi bi-search"></i>
                    </div>
                    <input class="form-control ps-5" type="text" name="search" placeholder="{{ __('messages.search') }}"
                        value="{{ request()->query('search') }}">
                </form>
            </div>

            <div class="table-responsive mt-3">
                <table class="table align-middle">
                    <thead class="table-secondary">
                        <tr>
                            <th>#</th>
                            <th>{{ __('messages.name') }}</th>
                            <th width="280px">{{ __('messages.action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($roles as $key => $role)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $role->name }}</td>
                                <td>
                                    <div class="table-actions d-flex align-items-center gap-3 fs-6">
                                        <a href="{{ route('admin.roles.edit', $role->id) }}" class="text-primary"
                                            data-bs-toggle="tooltip" data-bs-placement="bottom" title="{{ __('messages.edit') }}">
                                            <i class="bi bi-pencil-fill"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted">{{ __('messages.no_roles_found') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {!! $roles->render() !!}
@endsection
