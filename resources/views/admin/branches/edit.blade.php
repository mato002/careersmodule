@extends('layouts.admin')

@section('title', 'Edit Branch')
@section('header-description', 'Update branch details or visibility.')

@section('content')
    <div class="bg-white rounded-2xl shadow-sm border border-teal-50 p-6">
        <form action="{{ route('admin.branches.update', $branch) }}" method="POST" class="space-y-6">
            @method('PUT')
            @include('admin.branches._form', ['submitLabel' => 'Save Changes'])
        </form>
    </div>
@endsection

