@extends('layouts.admin')

@section('title', 'Edit Post')
@section('header-description', 'Update the post information.')

@section('header-actions')
    <a href="{{ route('admin.posts.index') }}" class="inline-flex items-center gap-2 px-4 py-2 border border-slate-200 rounded-xl text-sm font-semibold text-slate-600 hover:bg-slate-50">
        ← Back to Posts
    </a>
@endsection

@section('content')
    <div class="max-w-5xl mx-auto bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <form method="POST" action="{{ route('admin.posts.update', $post) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            @include('admin.posts._form', ['button' => 'Save Changes'])
        </form>
    </div>
@endsection




