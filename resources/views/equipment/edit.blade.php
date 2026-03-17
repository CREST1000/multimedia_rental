@extends('layouts.app')

@section('title', 'Edit Equipment')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4>Edit Equipment</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('equipment.update', $equipment) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Same form fields as create.blade.php, but with values filled -->
                    <div class="mb-3">
                        <label for="name" class="form-label">Equipment Name</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               id="name" name="name" value="{{ old('name', $equipment->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="category_id" class="form-label">Category</label>
                        <select class="form-control @error('category_id') is-invalid @enderror" 
                                id="category_id" name="category_id" required>
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" 
                                    {{ old('category_id', $equipment->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="brand" class="form-label">Brand</label>
                        <input type="text" class="form-control @error('brand') is-invalid @enderror" 
                               id="brand" name="brand" value="{{ old('brand', $equipment->brand) }}" required>
                        @error('brand')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="model" class="form-label">Model</label>
                            <input type="text" class="form-control" id="model" name="model" 
                                   value="{{ old('model', $equipment->model) }}">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="daily_rate" class="form-label">Daily Rate (₱)</label>
                            <input type="number" step="0.01" class="form-control @error('daily_rate') is-invalid @enderror" 
                                   id="daily_rate" name="daily_rate" value="{{ old('daily_rate', $equipment->daily_rate) }}" required>
                            @error('daily_rate')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="quantity_available" class="form-label">Quantity Available</label>
                            <input type="number" class="form-control @error('quantity_available') is-invalid @enderror" 
                                   id="quantity_available" name="quantity_available" 
                                   value="{{ old('quantity_available', $equipment->quantity_available) }}" required>
                            @error('quantity_available')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="condition" class="form-label">Condition</label>
                            <select class="form-control @error('condition') is-invalid @enderror" 
                                    id="condition" name="condition" required>
                                <option value="Excellent" {{ $equipment->condition == 'Excellent' ? 'selected' : '' }}>Excellent</option>
                                <option value="Good" {{ $equipment->condition == 'Good' ? 'selected' : '' }}>Good</option>
                                <option value="Fair" {{ $equipment->condition == 'Fair' ? 'selected' : '' }}>Fair</option>
                                <option value="Needs Repair" {{ $equipment->condition == 'Needs Repair' ? 'selected' : '' }}>Needs Repair</option>
                            </select>
                            @error('condition')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="3" required>{{ old('description', $equipment->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">Update Equipment</button>
                        <a href="{{ route('equipment.index') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection