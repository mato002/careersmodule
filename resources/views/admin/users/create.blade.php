@extends('layouts.admin')

@section('title', 'Add User')
@section('header-description', 'Create a new user account with appropriate access role.')

@section('header-actions')
    <a href="{{ route('admin.users.index') }}" class="inline-flex items-center gap-2 px-4 py-2 border border-slate-200 rounded-xl text-sm font-semibold text-slate-600 hover:bg-slate-50">
        ← Back to Users
    </a>
@endsection

@section('content')
    <div class="max-w-5xl mx-auto bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <form method="POST" action="{{ route('admin.users.store') }}">
            @include('admin.users._form', ['button' => 'Create User'])
        </form>
    </div>
@endsection

