@extends('layouts.admin')

@section('title', 'Add Team Member')
@section('header-description', 'Introduce a new leader or branch champion to the website.')

@section('header-actions')
    <a href="{{ route('admin.team-members.index') }}" class="inline-flex items-center gap-2 px-4 py-2 border border-slate-200 rounded-xl text-sm font-semibold text-slate-600 hover:bg-slate-50">
        ← Back to Team
    </a>
@endsection

@section('content')
    <div class="max-w-5xl mx-auto bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <form method="POST" action="{{ route('admin.team-members.store') }}" enctype="multipart/form-data">
            @include('admin.team-members._form', ['button' => 'Create Member'])
        </form>
    </div>
@endsection







