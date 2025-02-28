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
                <div class="card-header text-center">
                    <h5 class="mb-0">{{ __('messages.edit_role') }}</h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('admin.roles.update', $role->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label for="name" class="form-label fw-bold">{{ __('messages.role_name') }}</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ $role->name }}" required>
                        </div>

                        <!-- Permissions Table -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">{{ __('messages.permissions') }}</label>
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
                                                        @if ($permission)
                                                            <div class="form-check d-flex justify-content-center">
                                                                <input class="form-check-input" type="checkbox" id="permission_{{ $permission->id }}" name="permissions[]" value="{{ $permission->id }}" 
                                                                @if(in_array($permission->id, $rolePermissions)) checked @endif>
                                                            </div>
                                                        @else
                                                            <span>-</span>
                                                        @endif
                                                    </td>
                                                @endforeach
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-success w-100 py-2">{{ __('messages.update') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
