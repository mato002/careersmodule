@extends('layouts.admin')

@section('title', 'Edit CEO Message')
@section('header-description', 'Update the CEO/Director message information.')

@section('header-actions')
    <a href="{{ route('admin.ceo-messages.index') }}" class="inline-flex items-center gap-2 px-4 py-2 border border-slate-200 rounded-xl text-sm font-semibold text-slate-600 hover:bg-slate-50">
        ← Back to Messages
    </a>
@endsection

@section('content')
    <div class="max-w-5xl mx-auto bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <form method="POST" action="{{ route('admin.ceo-messages.update', $ceoMessage) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            @include('admin.ceo-messages._form', ['button' => 'Save Changes'])
        </form>
    </div>
@endsection




