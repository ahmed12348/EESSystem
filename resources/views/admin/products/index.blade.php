@extends('admin.layouts.app')

@section('title', __('messages.products_dashboard'))

@section('content')
    <!-- Breadcrumb -->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">{{ __('messages.products') }}</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.index') }}"><i class="bx bx-home-alt"></i></a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ __('messages.product_list') }}</li>
                </ol>
            </nav>
        </div>

        <div class="ms-auto">
            <div class="btn-group">
            
                @can('products-create')
                <a class="btn btn-info text-white" href="{{ route('admin.products.create') }}">
                    <i class="bi bi-plus-circle"></i> {{ __('messages.add_new_product') }}
                </a>
                @endcan
            </div>
        </div>
    </div>
    <!-- End Breadcrumb -->

    <div class="card">
        <div class="card-body">
            <div class="d-flex align-items-center justify-content-between">
                <!-- Import Form and Export Button -->
                <div class="d-flex align-items-center">
                    
                    @can('products-create')
                    <form action="{{ route('admin.products.import') }}" method="POST" enctype="multipart/form-data" class="d-flex align-items-center me-2">
                        @csrf
                        <input type="file" name="file" class="form-control form-control-sm" required>
                        <button type="submit" class="btn btn-secondary btn-sm ms-2">{{ __('messages.import') }}</button>
                    </form>
                    @endcan
                    @can('products-view')
                    <a href="{{ route('admin.products.export.sample') }}" class="btn btn-light btn-sm ms-2">{{ __('messages.sample') }}</a>
                    @endcan
                </div>
                
                <!-- Search Form -->
                <div class="ms-auto d-flex align-items-center">
                    <form class="d-flex position-relative" method="GET" action="{{ route('admin.products.index') }}">
                        <div class="position-absolute top-50 translate-middle-y search-icon px-3">
                            <i class="bi bi-search"></i>
                        </div>
                        <input class="form-control form-control-sm ps-5" type="text" name="search" placeholder="{{ __('messages.search_products') }}" value="{{ request()->query('search') }}">
                    </form>
                </div>
            </div>

            <div class="table-responsive mt-3">
                <table id='example2' class="table align-middle table-hover">
                    <thead class="table-secondary">
                        <tr>
                            <th>{{ __('messages.id') }}</th>
                            <th>{{ __('messages.image') }}</th>
                            <th>{{ __('messages.product_name') }}</th>
                            <th>{{ __('messages.vendor') }}</th>
                            <th>{{ __('messages.status') }}</th>  
                            <th>{{ __('messages.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($products as $product)
                            <tr>
                                <td>#{{ $product->id }}</td>
                                <td>    
                                    @if ($product->image)
                                        <img src="{{ asset('storage/' . $product->image) }}" 
                                             class="img-fluid rounded shadow border p-2" 
                                             style="max-width: 50px;" 
                                             alt="{{ __('messages.product_image') }}">
                                    @else 
                                        <img src="{{ asset('assets/images/default-product.png') }}" 
                                             class="img-fluid rounded shadow border p-2" 
                                             style="max-width: 50px;" 
                                             alt="{{ __('messages.image') }}">
                                    @endif 
                                </td> 
                                <td>{{ $product->name }}</td>
                                <td>{{ $product->vendor?->vendor?->business_name ?? __('messages.na') }}</td>
                                <td>
                                    @if ($product->status == 'approved')
                                        <span class="badge bg-success">{{ __('messages.approved') }}</span>
                                    @elseif ($product->status == 'rejected')
                                        <span class="badge bg-danger">{{ __('messages.reject') }}</span>
                                     @else
                                     <span class="badge bg-info">{{ __('messages.pending') }}</span>

                                    @endif
                                </td>
                                <td>
                                    <div class="table-actions d-flex align-items-center gap-2 fs-6">
                             
                                        @can('products-edit')
                                        <a href="{{ route('admin.products.edit', $product->id) }}" class="text-primary"
                                            data-bs-toggle="tooltip" data-bs-placement="bottom" title="{{ __('messages.edit') }}">
                                            <i class="bi bi-pencil-fill"></i>
                                        </a>
                                        @endcan

                                   
                                        @can('products-delete')
                                        <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-link p-0 text-danger"
                                                data-bs-toggle="tooltip" data-bs-placement="bottom" title="{{ __('messages.delete') }}"
                                                onclick="return confirm('{{ __('messages.delete_confirmation') }}')">
                                                <i class="bi bi-trash-fill"></i>
                                            </button>
                                        </form>
                                        @endcan

                                

                                        
                                        @can('products-view')
                                        <a href="{{ route('admin.products.show', $product->id) }}" class="text-warning"
                                            data-bs-toggle="tooltip" data-bs-placement="bottom" title="{{ __('messages.view') }}">
                                            <i class="bi bi-eye-fill"></i>
                                        </a>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="mt-1">
                {{ $products->links() }}
            </div>
        </div>
    </div>
@endsection
