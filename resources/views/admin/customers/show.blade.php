@extends('admin.layouts.app')

@section('title', __('messages.view_customer'))

@section('content')

@php
$data = [
    'messages.name' => $customer->name,
    // 'messages.email' => $customer->email,
    'messages.phone' => $customer->phone,
    'messages.business_name' => $customer->vendor?->business_name,
    'messages.business_type' => $customer->vendor?->business_type,
    'messages.zone' => $customer->vendor?->zone,
    'messages.address' => $customer->vendor?->address,
    'messages.latitude' => $customer->vendor?->latitude,
    'messages.longitude' => $customer->vendor?->longitude,

    'messages.status' => ucfirst($customer->status),
    'messages.notes' => $customer->vendor?->notes,
];
@endphp

@include('components.show-template', [
    'title' => __('messages.view_customer'),
    'data' => $data,
    'backRoute' => route('admin.customers.index'),
    'id' => $customer->id,
    'statusField' => 'status',
    'statusValue' => $customer->status,
    'approveRoute' => 'admin.vendors.approve',
    'rejectRoute' => 'admin.vendors.reject'
])

@endsection
