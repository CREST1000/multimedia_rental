@extends('layouts.admin')

@section('title', ' - New Reservation')
@section('page-title', 'Create New Reservation')

@section('content')
<div class="card">
    <div class="card-header">
        <i class="bi bi-plus-circle me-2"></i>
        <span>Reservation Details</span>
    </div>
    <div class="card-body">
        <form action="{{ route('reservations.store') }}" method="POST" id="reservationForm">
            @csrf
            
            <!-- Equipment Selection -->
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h6 class="mb-0"><i class="bi bi-camera me-2"></i>Select Equipment</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <label class="form-label">Equipment <span class="text-danger">*</span></label>
                            <select class="form-select @error('equipment_id') is-invalid @enderror" 
                                    id="equipment_id" name="equipment_id" required>
                                <option value="">-- Choose Equipment --</option>
                                @foreach($equipment as $item)
                                    <option value="{{ $item->id }}" 
                                            data-rate="{{ $item->daily_rate }}"
                                            data-available="{{ $item->quantity_available }}"
                                            {{ old('equipment_id') == $item->id ? 'selected' : '' }}>
                                        {{ $item->name }} ({{ $item->brand }}) - ₱{{ number_format($item->daily_rate, 2) }}/day
                                    </option>
                                @endforeach
                            </select>
                            @error('equipment_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Available Stock</label>
                            <div class="form-control bg-light" id="availableStockDisplay">Select equipment</div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Customer Information -->
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h6 class="mb-0"><i class="bi bi-person me-2"></i>Customer Information</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Full Name <span class="text-danger">*</span></label>
                            <input type="text" name="customer_name" class="form-control @error('customer_name') is-invalid @enderror" 
                                   value="{{ old('customer_name') }}" placeholder="John Doe" required>
                            @error('customer_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Email Address <span class="text-danger">*</span></label>
                            <input type="email" name="customer_email" class="form-control @error('customer_email') is-invalid @enderror" 
                                   value="{{ old('customer_email') }}" placeholder="john@example.com" required>
                            @error('customer_email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Phone Number <span class="text-danger">*</span></label>
                            <input type="text" name="customer_phone" class="form-control @error('customer_phone') is-invalid @enderror" 
                                   value="{{ old('customer_phone') }}" placeholder="09123456789" required>
                            @error('customer_phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Rental Details -->
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h6 class="mb-0"><i class="bi bi-calendar me-2"></i>Rental Details</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Start Date <span class="text-danger">*</span></label>
                            <input type="date" name="start_date" class="form-control @error('start_date') is-invalid @enderror" 
                                   value="{{ old('start_date') }}" min="{{ date('Y-m-d') }}" required>
                            @error('start_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label class="form-label">End Date <span class="text-danger">*</span></label>
                            <input type="date" name="end_date" class="form-control @error('end_date') is-invalid @enderror" 
                                   value="{{ old('end_date') }}" min="{{ date('Y-m-d') }}" required>
                            @error('end_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Quantity <span class="text-danger">*</span></label>
                            <input type="number" name="quantity" class="form-control @error('quantity') is-invalid @enderror" 
                                   id="quantity" value="{{ old('quantity', 1) }}" min="1" required>
                            @error('quantity')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted" id="quantityHelp"></small>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Daily Rate</label>
                            <div class="input-group">
                                <span class="input-group-text">₱</span>
                                <input type="text" class="form-control bg-light" id="dailyRate" readonly>
                            </div>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Total Price</label>
                            <div class="input-group">
                                <span class="input-group-text">₱</span>
                                <input type="text" class="form-control bg-primary text-white fw-bold" 
                                       id="totalPrice" value="0.00" readonly>
                            </div>
                            <small class="text-muted" id="priceBreakdown"></small>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Additional Notes</label>
                        <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" 
                                  rows="3" placeholder="Special requests, instructions, etc.">{{ old('notes') }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            
            <!-- Form Actions -->
            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('reservations.index') }}" class="btn btn-secondary">
                    <i class="bi bi-x-circle"></i> Cancel
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-circle"></i> Create Reservation
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const equipmentSelect = document.getElementById('equipment_id');
    const startDate = document.querySelector('input[name="start_date"]');
    const endDate = document.querySelector('input[name="end_date"]');
    const quantity = document.getElementById('quantity');
    const dailyRateDisplay = document.getElementById('dailyRate');
    const totalPriceDisplay = document.getElementById('totalPrice');
    const priceBreakdown = document.getElementById('priceBreakdown');
    const availableStockDisplay = document.getElementById('availableStockDisplay');
    const quantityHelp = document.getElementById('quantityHelp');
    
    function calculateTotal() {
        const selected = equipmentSelect.options[equipmentSelect.selectedIndex];
        if (!selected.value) {
            dailyRateDisplay.value = '';
            totalPriceDisplay.value = '0.00';
            priceBreakdown.textContent = '';
            return;
        }
        
        const rate = parseFloat(selected.dataset.rate);
        const available = parseInt(selected.dataset.available);
        const qty = parseInt(quantity.value) || 0;
        const start = new Date(startDate.value);
        const end = new Date(endDate.value);
        
        // Update daily rate display
        dailyRateDisplay.value = rate.toFixed(2);
        
        // Update available stock
        availableStockDisplay.textContent = available + ' units available';
        availableStockDisplay.className = available > 0 ? 'form-control bg-light text-success' : 'form-control bg-light text-danger';
        
        // Validate quantity
        if (qty > available) {
            quantity.setCustomValidity('Only ' + available + ' units available');
            quantityHelp.innerHTML = '<span class="text-danger">⚠️ Only ' + available + ' units available</span>';
        } else {
            quantity.setCustomValidity('');
            quantityHelp.innerHTML = '<span class="text-success">✓ Available</span>';
        }
        
        // Calculate total if dates are valid
        if (startDate.value && endDate.value && start <= end && qty > 0) {
            const diffTime = Math.abs(end - start);
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
            const total = rate * qty * diffDays;
            
            totalPriceDisplay.value = total.toFixed(2);
            priceBreakdown.innerHTML = `${diffDays} days × ${qty} unit(s) × ₱${rate.toFixed(2)}`;
        } else {
            totalPriceDisplay.value = '0.00';
            priceBreakdown.innerHTML = '';
        }
    }
    
    equipmentSelect.addEventListener('change', calculateTotal);
    startDate.addEventListener('change', calculateTotal);
    endDate.addEventListener('change', calculateTotal);
    quantity.addEventListener('input', calculateTotal);
    
    // Set minimum end date when start date changes
    startDate.addEventListener('change', function() {
        endDate.min = this.value;
        if (endDate.value && new Date(endDate.value) < new Date(this.value)) {
            endDate.value = this.value;
        }
        calculateTotal();
    });
});
</script>
@endpush
@endsection