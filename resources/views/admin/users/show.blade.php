@extends('admin.layouts.app')

@section('title', __('messages.view_user'))

@section('content')

@php
$data = [
    'messages.name' => $user->name,
    'messages.email' => $user->email,
    'messages.phone' => $user->phone,
    'messages.type' => ucfirst($user->type),
    'messages.status' => ucfirst($user->status)
];
@endphp

@include('components.show-template', [
    'title' => __('messages.view_user'),
    'data' => $data,
    'backRoute' => route('admin.users.index'),
    'id' => $user->id,
    'statusField' => 'status',
    'statusValue' => $user->status,
    'approveRoute' => 'admin.users.approve',
    'rejectRoute' => 'admin.users.reject'
])

@endsection
