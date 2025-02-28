@extends('admin.layouts.app')

@section('title', __('messages.customers_list'))

@section('content')
<div class="container">
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">{{ __('messages.customers') }}</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.index') }}"><i class="bx bx-home-alt"></i></a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ __('messages.customers_list') }}</li>
                </ol>
            </nav>
        </div>
        <div class="ms-auto">
            <a class="btn btn-info text-white" href="{{ route('admin.customers.create') }}">
                <i class="bi bi-plus-circle"></i> {{ __('messages.create_customer') }}
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="d-flex align-items-center">
                <h5 class="mb-0">{{ __('messages.customers') }}</h5>
                <form class="ms-auto position-relative" method="GET" action="{{ route('admin.customers.index') }}">
                    <div class="position-absolute top-50 translate-middle-y search-icon px-3">
                        <i class="bi bi-search"></i>
                    </div>
                    <input class="form-control ps-5" type="text" name="search" placeholder="{{ __('messages.search') }}" value="{{ request()->query('search') }}">
                </form>
            </div>

            <div class="table-responsive mt-3">
                <table class="table align-middle table-hover">
                    <thead class="table-secondary">
                        <tr>
                            <th>#</th>
                            <th>{{ __('messages.business_name') }}</th>
                        

                            <th>{{ __('messages.status') }}</th>
                            <th>{{ __('messages.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($customers as $index => $customer)
                        <tr>
                            <td>{{ $index + 1 }}</td> 
                                <td>{{ $customer->vendor->business_name ?? __('messages.no_customer') }}</td>

                                <td>
                                    @if ($customer->status === 'approved')
                                        <span class="badge bg-success">{{ __('messages.approved') }}</span>
                                    @elseif ($customer->status === 'pending')
                                        <span class="badge bg-warning">{{ __('messages.pending') }}</span>
                                    @elseif ($customer->status === 'rejected')
                                        <span class="badge bg-danger">{{ __('messages.rejected') }}</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="table-actions d-flex align-items-center gap-2 fs-6">
                                        <a href="{{ route('admin.customers.edit', $customer->id) }}" class="text-primary" data-bs-toggle="tooltip" title="{{ __('messages.edit') }}">
                                            <i class="bi bi-pencil-fill"></i>
                                        </a>
                                        <form action="{{ route('admin.customers.destroy', $customer->id) }}" method="POST" onsubmit="return confirm('{{ __('messages.delete_confirmation_customer') }}')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-link p-0 text-danger" data-bs-toggle="tooltip" title="{{ __('messages.delete') }}">
                                                <i class="bi bi-trash-fill"></i>
                                            </button>
                                        </form>
                                        <a href="{{ route('admin.customers.show', $customer->id) }}" class="text-warning" data-bs-toggle="tooltip" title="{{ __('messages.show') }}">
                                            <i class="bi bi-eye-fill"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-1">
                {{ $customers->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
