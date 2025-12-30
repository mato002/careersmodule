@extends('layouts.admin')

@section('title', 'Add Branch')
@section('header-description', 'Create a new branch card.')

@section('content')
    <div class="bg-white rounded-2xl shadow-sm border border-teal-50 p-6">
        <form action="{{ route('admin.branches.store') }}" method="POST" class="space-y-6">
            @include('admin.branches._form', ['submitLabel' => 'Create Branch'])
        </form>
    </div>
@endsection

