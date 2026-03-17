@extends('layouts.admin')

@section('title', ' - Categories')
@section('page-title', 'Category Management')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-tags me-2"></i> Equipment Categories</span>
        <a href="{{ route('categories.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-circle"></i> Add New Category
        </a>
    </div>
    <div class="card-body">
        <div class="row">
            @forelse($categories as $category)
            <div class="col-md-4 mb-3">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title">{{ $category->name }}</h5>
                        <p class="card-text">{{ Str::limit($category->description, 100) ?: 'No description' }}</p>
                        <p class="text-muted">
                            <small>{{ $category->equipment->count() }} equipment items</small>
                        </p>
                    </div>
                    <div class="card-footer bg-transparent">
                        <a href="{{ route('categories.show', $category) }}" class="btn btn-sm btn-info">
                            <i class="bi bi-eye"></i>
                        </a>
                        <a href="{{ route('categories.edit', $category) }}" class="btn btn-sm btn-warning">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form action="{{ route('categories.destroy', $category) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" 
                                    onclick="return confirm('Are you sure? This will affect all equipment in this category.')">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center py-5">
                <i class="bi bi-tags display-1 text-muted"></i>
                <p class="text-muted mt-3">No categories found</p>
                <a href="{{ route('categories.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Create Your First Category
                </a>
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection