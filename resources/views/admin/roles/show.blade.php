@extends('admin.layouts.app')

@section('content')

<div class="container">
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">{{ __('messages.roles') }}</div>
        <div class="ms-auto">
            <a href="{{ route('admin.roles.index') }}" class="btn btn-outline-secondary">{{ __('messages.back') }}</a>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-sm border-0 rounded-lg">
                <div class="card-header text-center bg-light">
                    <h5 class="mb-0 text-primary">{{ __('messages.view_role') }}</h5>
                </div>
                <div class="card-body p-4">
                    <div class="mb-4">
                        <h6 class="fw-bold">{{ __('messages.role_name') }}</h6>
                        <p class="text-muted">{{ $role->name }}</p>
                    </div>

                    <!-- Permissions Table -->
                    <div class="mb-4">
                        <h6 class="fw-bold">{{ __('messages.permissions') }}</h6>
                        <div class="table-responsive">
                            <table class="table table-bordered text-center align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>{{ __('messages.module') }}</th>
                                        <th class="text-center">{{ __('messages.view') }}</th>
                                        <th class="text-center">{{ __('messages.create') }}</th>
                                        <th class="text-center">{{ __('messages.edit') }}</th>
                                        <th class="text-center">{{ __('messages.delete') }}</th>
                                        <th class="text-center">{{ __('messages.approve') }}</th>
                                        <th class="text-center">{{ __('messages.reject') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($permissions as $module => $modulePermissions)
                                        <tr>
                                            <td class="fw-bold text-start ps-3">{{ ucfirst($module) }}</td>
                                            @php
                                                $actions = ['view', 'create', 'edit', 'delete', 'approve', 'reject'];
                                            @endphp
                                            @foreach ($actions as $action)
                                                <td class="text-center">
                                                    @php
                                                        $permission = $modulePermissions->firstWhere('name', "{$module}-{$action}");
                                                    @endphp
                                                    @if ($permission && in_array($permission->id, $rolePermissions))
                                                        <span class="badge bg-success">✔</span>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
