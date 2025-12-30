@extends('layouts.admin')

@section('title', 'Edit Product')

@section('header-description', 'Update content, upload new images, and fine-tune product visibility.')

@section('header-actions')
    <a href="{{ route('admin.products.index') }}" class="inline-flex items-center gap-2 px-4 py-2 border border-slate-200 rounded-xl text-sm font-semibold text-slate-600 hover:bg-slate-50">
        ← Back to Products
    </a>
@endsection

@section('content')
    <div class="max-w-5xl mx-auto space-y-6">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <form method="POST" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data">
                @method('PUT')
                @include('admin.products._form', ['button' => 'Save Changes'])
            </form>
        </div>

        @if ($product->images->isNotEmpty())
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Existing Images</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach ($product->images as $image)
                        <div class="border border-gray-100 rounded-xl overflow-hidden bg-gray-50">
                            <div class="relative">
                                <img src="{{ asset('storage/'.$image->path) }}" alt="" class="h-48 w-full object-cover">
                                @if ($image->is_primary)
                                    <span class="absolute top-2 left-2 px-2 py-1 bg-teal-600 text-white text-xs font-semibold rounded-full">
                                        Primary
                                    </span>
                                @endif
                            </div>
                            <div class="p-4 space-y-3">
                                <div>
                                    <label class="block text-xs font-semibold text-gray-600 mb-1">Display Order</label>
                                    <input type="number" min="1" name="order[{{ $image->id }}]" value="{{ old('order.'.$image->id, $image->display_order) }}" form="image-order-form"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-600 focus:border-transparent text-sm">
                                </div>
                                <div class="flex flex-wrap gap-2">
                                    @if (! $image->is_primary)
                                        <form method="POST" action="{{ route('admin.products.images.primary', [$product, $image]) }}">
                                            @csrf
                                            <button type="submit" class="px-3 py-2 text-xs font-semibold text-teal-800 bg-teal-50 border border-teal-100 rounded-lg hover:bg-teal-100">
                                                Make Primary
                                            </button>
                                        </form>
                                    @endif
                                    <form method="POST" action="{{ route('admin.products.images.destroy', [$product, $image]) }}" class="delete-image-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-3 py-2 text-xs font-semibold text-red-700 bg-red-50 border border-red-100 rounded-lg hover:bg-red-100">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <form id="image-order-form" method="POST" action="{{ route('admin.products.images.reorder', $product) }}">
                    @csrf
                </form>
                <div class="text-right mt-4">
                    <button type="submit" form="image-order-form" class="inline-flex items-center gap-2 px-4 py-2 bg-teal-700 text-white rounded-lg text-sm font-semibold hover:bg-teal-800">
                        Save Display Order
                    </button>
                </div>
            </div>
        @endif
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle image delete forms with SweetAlert
        document.querySelectorAll('.delete-image-form').forEach(function(form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const formElement = this;
                
                Swal.fire({
                    title: 'Delete Image?',
                    text: 'Are you sure you want to delete this image? This action cannot be undone.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc2626',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Show loading state
                        Swal.fire({
                            title: 'Deleting...',
                            text: 'Please wait while we delete the image.',
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            showConfirmButton: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                        
                        // Submit the form
                        formElement.submit();
                    }
                });
            });
        });
    });
</script>
@endpush

