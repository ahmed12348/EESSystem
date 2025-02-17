@extends('admin.layouts.app')

@section('content')

<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        {{-- <h4 class="fw-bold">{{ __('messages.search_results') }}</h4>
        <form action="{{ route('admin.search.index') }}" method="GET" class="d-flex">
            <input type="text" name="search" class="form-control form-control-sm me-2" placeholder="{{ __('messages.search_by_product') }}">
            <button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-search"></i> {{ __('messages.search') }}</button>
        </form> --}}
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>{{ __('messages.product_name') }}</th>
                        {{-- <th>{{ __('messages.product_id') }}</th> --}}
                        <th>{{ __('messages.number_of_search') }}</th>
                        <th>{{ __('messages.date') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($searchResults as $search)
                    <tr>
                        <td>{{ $search->product->name ?? __('messages.not_available') }}</td>
                        {{-- <td>{{ $search->product_id }}</td> --}}
                        <td>{{ $search->number }}</td>
                        <td>{{ $search->created_at->format('Y-m-d H:i') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center">{{ __('messages.no_search_results_found') }}</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-3">
                {{ $searchResults->links() }}
            </div>
        </div>
    </div>
</div>

@endsection
