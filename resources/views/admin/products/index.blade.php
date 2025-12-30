@extends('layouts.admin')

@section('title', 'Manage Products')

@section('header-description', 'Manage products and the content displayed on the public site.')

@section('header-actions')
    <button class="inline-flex items-center gap-1.5 sm:gap-2 px-3 sm:px-4 py-1.5 sm:py-2 border border-slate-200 rounded-lg sm:rounded-xl text-xs sm:text-sm font-semibold text-slate-600 hover:bg-slate-50 whitespace-nowrap"
        onclick="window.location.reload()">
        <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4.5 12h15m-7.5 7.5v-15"/></svg>
        <span class="hidden sm:inline">Refresh</span>
    </button>
    <a href="{{ route('admin.products.create') }}" class="inline-flex items-center gap-1.5 sm:gap-2 px-3 sm:px-4 lg:px-5 py-1.5 sm:py-2 rounded-lg sm:rounded-xl text-xs sm:text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 whitespace-nowrap">
        <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 4v16m8-8H4"/></svg>
        <span class="hidden sm:inline">New Product</span>
        <span class="sm:hidden">New</span>
    </a>
@endsection

@section('content')
    @php use Illuminate\Support\Str; @endphp

    <!-- Total Products Banner -->
    @if(request()->hasAny(['search', 'is_active', 'category']))
        <div class="mb-6 bg-gradient-to-r from-teal-600 to-teal-700 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between flex-wrap gap-4">
                <div>
                    <h2 class="text-2xl sm:text-3xl font-bold mb-1">
                        {{ $filteredProductsCount }} Product{{ $filteredProductsCount !== 1 ? 's' : '' }} Found
                    </h2>
                    <p class="text-teal-100 text-sm">Filtered from {{ $totalProductsCount }} total products</p>
                </div>
                <a href="{{ route('admin.products.index') }}" class="px-4 py-2 bg-white/20 hover:bg-white/30 rounded-lg text-sm font-semibold transition">
                    Clear Filters
                </a>
            </div>
        </div>
    @endif

    <!-- Filters -->
    <form method="GET" action="{{ route('admin.products.index') }}" class="mb-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by title, category, or description..." 
                           class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                    <select name="category" class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
                        <option value="all">All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category }}" {{ request('category') === $category ? 'selected' : '' }}>{{ $category }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select name="is_active" class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
                        <option value="all">All Statuses</option>
                        <option value="1" {{ request('is_active') === '1' ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>Hidden</option>
                    </select>
                </div>
            </div>
            <div class="flex items-center gap-2 mt-4">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-semibold text-sm">
                    Apply Filters
                </button>
                @if(request()->hasAny(['search', 'is_active', 'category']))
                    <a href="{{ route('admin.products.index') }}" class="px-4 py-2 border border-gray-200 text-gray-600 rounded-lg hover:bg-gray-50 transition-colors font-semibold text-sm">
                        Clear
                    </a>
                @endif
            </div>
        </div>
    </form>

    <div class="bg-white border border-gray-100 rounded-xl sm:rounded-2xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
        <table class="w-full text-left text-sm min-w-[640px]">
            <thead class="bg-gray-50 text-gray-500 uppercase tracking-wide text-xs">
                <tr>
                    <th class="px-3 sm:px-6 py-3">Title</th>
                    <th class="px-3 sm:px-6 py-3 hidden sm:table-cell">Category</th>
                    <th class="px-3 sm:px-6 py-3">Status</th>
                    <th class="px-3 sm:px-6 py-3 hidden md:table-cell">Images</th>
                    <th class="px-3 sm:px-6 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($products as $product)
                    <tr>
                        <td class="px-3 sm:px-6 py-4">
                            <p class="font-semibold text-gray-900 text-sm sm:text-base">{{ $product->title }}</p>
                            <p class="text-xs text-gray-400">Updated {{ $product->updated_at->diffForHumans() }}</p>
                        </td>
                        <td class="px-3 sm:px-6 py-4 text-gray-600 text-xs sm:text-sm hidden sm:table-cell">{{ $product->category ?? '—' }}</td>
                        <td class="px-3 sm:px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $product->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-200 text-gray-700' }}">
                                {{ $product->is_active ? 'Active' : 'Hidden' }}
                            </span>
                        </td>
                        <td class="px-3 sm:px-6 py-4 text-gray-600 text-xs sm:text-sm hidden md:table-cell">{{ $product->images_count }}</td>
                        <td class="px-3 sm:px-6 py-4 text-right space-x-2 sm:space-x-3">
                            <a href="{{ route('admin.products.show', $product) }}" class="text-blue-600 font-semibold text-xs sm:text-sm">View</a>
                            <a href="{{ route('admin.products.edit', $product) }}" class="text-teal-700 font-semibold text-xs sm:text-sm">Edit</a>
                            <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="inline-block delete-form" data-id="{{ $product->id }}" data-name="{{ $product->title }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 font-semibold text-xs sm:text-sm">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-6 text-center text-gray-500">No products yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        </div>
    </div>

    <div class="mt-4 sm:mt-6">
        {{ $products->links() }}
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle delete forms with SweetAlert
        document.querySelectorAll('.delete-form').forEach(function(form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const formElement = this;
                const productName = formElement.getAttribute('data-name') || 'this product';
                
                Swal.fire({
                    title: 'Are you sure?',
                    text: `You are about to delete "${productName}". This action cannot be undone!`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc2626',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Deleting...',
                            text: 'Please wait while we delete the product.',
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            showConfirmButton: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                        
                        formElement.submit();
                    }
                });
            });
        });
    });
</script>
@endpush

