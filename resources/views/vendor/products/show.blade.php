@extends('vendor.layouts.app')

@section('title', __('messages.view_product'))

@section('content')

@php
$data = [
    'messages.product_name' => $product->name,
    'messages.description' => $product->description,
    'messages.price' => $product->price . ' $',
    'messages.category' => $product->category ? $product->category->name : __('messages.no_category_assigned'),
    'messages.items' => $product->items,
    'messages.color' => $product->color ?? __('messages.na'),
    'messages.shape' => $product->shape ?? __('messages.na'),
    'messages.min_order_quantity' => $product->min_order_quantity ?? __('messages.na'),
    'messages.max_order_quantity' => $product->max_order_quantity ?? __('messages.na'),
    'messages.vendor' => $product->vendor?->vendor?->business_name ?? __('messages.na'),
    'messages.notes' => $product->notes ?? __('messages.na'),
];
@endphp

@include('components.show-product', [
    'title' => __('messages.view_product'),
    'data' => $data,
    'backRoute' => route('vendor.products.index'),
    'image' => $product->image ? asset('storage/' . $product->image) : asset('assets/images/default-product.png'),
    'imageAlt' => __('messages.product_image'),
])

@endsection
