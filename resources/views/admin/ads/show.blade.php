@extends('admin.layouts.app')

@section('title', __('messages.view_ad'))

@section('content')

@php
$data = [
    'messages.ad_title' => $ad->title,
    'messages.description' => $ad->description,
    'messages.vendor' => $ad->vendor?->name ?? __('messages.na'),
    'messages.status' => ucfirst($ad->status),
    'messages.created_at' => $ad->created_at->format('Y-m-d H:i A'),
];
@endphp

@include('components.admin-show-ad', [
    'title' => __('messages.view_ad'),
    'data' => $data,
    'backRoute' => route('admin.ads.index'),
    'image' => $ad->image ? asset('storage/' . $ad->image) : asset('assets/images/default-product.png'),
    'imageAlt' => __('messages.image'),
    'id' => $ad->id,
    'statusField' => 'status',
    'statusValue' => $ad->status,
    'approveRoute' => 'admin.ads.approve',
    'rejectRoute' => 'admin.ads.reject'
])

@endsection
