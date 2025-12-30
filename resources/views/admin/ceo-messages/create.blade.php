@extends('layouts.admin')

@section('title', 'Add CEO Message')
@section('header-description', 'Create a new CEO/Director message.')

@section('header-actions')
    <a href="{{ route('admin.ceo-messages.index') }}" class="inline-flex items-center gap-2 px-4 py-2 border border-slate-200 rounded-xl text-sm font-semibold text-slate-600 hover:bg-slate-50">
        ← Back to Messages
    </a>
@endsection

@section('content')
    <div class="max-w-5xl mx-auto bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <form method="POST" action="{{ route('admin.ceo-messages.store') }}" enctype="multipart/form-data">
            @include('admin.ceo-messages._form', ['button' => 'Create Message'])
        </form>
    </div>
@endsection




