@extends('admin.layouts.app')

@section('title', __('messages.users_list'))

@section('content')
    <!-- Breadcrumb -->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">{{ __('messages.users') }}</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.index') }}"><i class="bx bx-home-alt"></i></a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ __('messages.users_list') }}</li>
                </ol>
            </nav>
        </div>

        <div class="ms-auto">
            <div class="btn-group">
                {{-- <a class="btn btn-info text-white" href="{{ route('admin.users.create') }}">
                    <i class="bi bi-plus-circle"></i> {{ __('messages.create_user') }}
                </a> --}}
            </div>
        </div>
    </div>
    <!-- End Breadcrumb -->

    <div class="card">
        <div class="card-body">
            <div class="d-flex align-items-center">
                <h5 class="mb-0">{{ __('messages.users') }}</h5>
                <form class="ms-auto position-relative" method="GET" action="{{ route('admin.users.index') }}">
                    <div class="position-absolute top-50 translate-middle-y search-icon px-3">
                        <i class="bi bi-search"></i>
                    </div>
                    <input class="form-control ps-5" type="text" name="search" placeholder="{{ __('messages.search') }}"
                        value="{{ request()->query('search') }}">
                </form>
            </div>
        
            <div class="table-responsive mt-3">
                <table class="table align-middle table-hover">
                    <thead class="table-secondary">
                        <tr>
                            <th>{{ __('messages.business_name') }}</th>
                            <th>{{ __('messages.phone_number') }}</th>
                            <th>{{ __('messages.total_orders') }}</th>
                            <th>{{ __('messages.zone') }}</th> 
                            <th>{{ __('messages.status') }}</th>  
                            <th>{{ __('messages.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <td>{{ $user->vendor?->business_name }}</td>
                                <td>{{ $user->phone ?? __('messages.na') }}</td>
                                <td>{{ $user->orders->count() }}</td>
                                <td>{{ $user->vendor?->zone ?? __('messages.not_assigned') }}</td> 
                                <td>
                                    @if ($user->status == 'inactive')
                                        <span class="badge bg-danger">{{ __('messages.inactive') }}</span>
                                    @else
                                        <span class="badge bg-success">{{ __('messages.active') }}</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="table-actions d-flex align-items-center gap-2 fs-6">
                                        {{-- Edit Button --}}
                                        <a href="{{ route('admin.users.edit', $user->id) }}" class="text-primary"
                                            data-bs-toggle="tooltip" data-bs-placement="bottom" title="{{ __('messages.edit') }}">
                                            <i class="bi bi-pencil-fill"></i>
                                        </a>

                                        {{-- Delete Button --}}
                                        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-link p-0 text-danger"
                                                data-bs-toggle="tooltip" data-bs-placement="bottom" title="{{ __('messages.delete') }}"
                                                onclick="return confirm('{{ __('messages.delete_user_confirmation') }}')">
                                                <i class="bi bi-trash-fill"></i>
                                            </button>
                                        </form>

                                        {{-- View Button --}}
                                        <a href="{{ route('admin.users.show', $user->id) }}" class="text-warning"
                                            data-bs-toggle="tooltip" data-bs-placement="bottom" title="{{ __('messages.view') }}">
                                            <i class="bi bi-eye-fill"></i>
                                        </a>

                                        {{-- Approve/Reject Buttons --}}
                                        @if ($user->status == 'inactive')
                                            <form action="{{ route('admin.users.approve', $user->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-link p-0 text-success"
                                                    data-bs-toggle="tooltip" data-bs-placement="bottom" title="{{ __('messages.approve_user') }}"
                                                    onclick="return confirm('{{ __('messages.approve_user_confirmation') }}')">
                                                    <i class="bi bi-check-circle-fill"></i> 
                                                </button>
                                            </form>
                                        @elseif ($user->status == 'active')
                                            <form action="{{ route('admin.users.reject', $user->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-link p-0 text-danger"
                                                    data-bs-toggle="tooltip" data-bs-placement="bottom" title="{{ __('messages.reject_user') }}"
                                                    onclick="return confirm('{{ __('messages.reject_user_confirmation') }}')">
                                                    <i class="bi bi-x-circle-fill"></i> 
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-1">
                {{ $users->links() }}
            </div>
        </div>
    </div>
@endsection
