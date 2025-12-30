@extends('layouts.admin')

@section('title', 'Edit Job Post')

@section('header-description', 'Update job posting details.')

@section('header-actions')
    <a href="{{ route('admin.jobs.index') }}" class="inline-flex items-center gap-2 px-4 py-2 border border-slate-200 rounded-xl text-sm font-semibold text-slate-600 hover:bg-slate-50">
        ← Back to Jobs
    </a>
@endsection

@section('content')
    <div class="max-w-5xl mx-auto bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <form method="POST" action="{{ route('admin.jobs.update', $job) }}">
            @csrf
            @method('PUT')
            @include('admin.jobs._form', ['button' => 'Update Job Post'])
        </form>
    </div>
@endsection


