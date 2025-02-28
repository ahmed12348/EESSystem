@extends('admin.layouts.app')

@section('title', __('messages.view_vendor'))

@section('content')

@php
$data = [
    'messages.name' => $vendor->name,
    'messages.email' => $vendor->email,
    'messages.phone' => $vendor->phone,
    'messages.business_name' => $vendor->vendor?->business_name,
    'messages.business_type' => $vendor->vendor?->business_type,
    'messages.zone' => $vendor->vendor?->zone,
    'messages.address' => $vendor->vendor?->address,
    'messages.latitude' => $vendor->vendor?->latitude,
    'messages.longitude' => $vendor->vendor?->longitude,
    'messages.notes' => $vendor->vendor?->notes,
    'messages.status' => ucfirst($vendor->status)
];
@endphp

@include('components.show-template', [
    'title' => __('messages.view_vendor'),
    'data' => $data,
    'backRoute' => route('admin.vendors.index'),
    'id' => $vendor->id,
    'statusField' => 'status',
    'statusValue' => $vendor->status,
    'approveRoute' => 'admin.vendors.approve',
    'rejectRoute' => 'admin.vendors.reject'
])

@endsection
