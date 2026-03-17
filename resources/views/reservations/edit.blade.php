@extends('layouts.app')

@section('title', 'Edit Reservation')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4>Edit Reservation #{{ $reservation->id }}</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('reservations.update', $reservation) }}" method="POST" id="reservationForm">
                    @csrf
                    @method('PUT')

                    <h5 class="mb-3">Equipment Information</h5>
                    <div class="mb-3">
                        <label class="form-label">Equipment</label>
                        <input type="text" class="form-control" value="{{ $reservation->equipment->name }} ({{ $reservation->equipment->brand }})" readonly disabled>
                        <small class="text-muted">Equipment cannot be changed after reservation is created.</small>
                    </div>

                    <h5 class="mb-3">Customer Information</h5>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="customer_name" class="form-label">Full Name</label>
                            <input type="text" class="form-control @error('customer_name') is-invalid @enderror" 
                                   id="customer_name" name="customer_name" value="{{ old('customer_name', $reservation->customer_name) }}" required>
                            @error('customer_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="customer_email" class="form-label">Email Address</label>
                            <input type="email" class="form-control @error('customer_email') is-invalid @enderror" 
                                   id="customer_email" name="customer_email" value="{{ old('customer_email', $reservation->customer_email) }}" required>
                            @error('customer_email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="customer_phone" class="form-label">Phone Number</label>
                        <input type="text" class="form-control @error('customer_phone') is-invalid @enderror" 
                               id="customer_phone" name="customer_phone" value="{{ old('customer_phone', $reservation->customer_phone) }}" required>
                        @error('customer_phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <h5 class="mb-3">Rental Details</h5>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="start_date" class="form-label">Start Date</label>
                            <input type="date" class="form-control @error('start_date') is-invalid @enderror" 
                                   id="start_date" name="start_date" value="{{ old('start_date', $reservation->start_date->format('Y-m-d')) }}" required>
                            @error('start_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="end_date" class="form-label">End Date</label>
                            <input type="date" class="form-control @error('end_date') is-invalid @enderror" 
                                   id="end_date" name="end_date" value="{{ old('end_date', $reservation->end_date->format('Y-m-d')) }}" required>
                            @error('end_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Quantity</label>
                        <input type="text" class="form-control" value="{{ $reservation->quantity }}" readonly disabled>
                        <small class="text-muted">Quantity cannot be changed. Create a new reservation if you need different quantity.</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Total Price</label>
                        <div class="form-control bg-light" id="totalPrice">{{ $reservation->formatted_total_price }}</div>
                    </div>

                    <div class="mb-3">
                        <label for="notes" class="form-label">Additional Notes</label>
                        <textarea class="form-control @error('notes') is-invalid @enderror" 
                                  id="notes" name="notes" rows="3">{{ old('notes', $reservation->notes) }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">Update Reservation</button>
                        <a href="{{ route('reservations.show', $reservation) }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const startDate = document.getElementById('start_date');
    const endDate = document.getElementById('end_date');
    const totalPriceDiv = document.getElementById('totalPrice');
    const dailyRate = {{ $reservation->equipment->daily_rate }};
    const quantity = {{ $reservation->quantity }};

    function calculateTotal() {
        const start = new Date(startDate.value);
        const end = new Date(endDate.value);

        if (startDate.value && endDate.value && start <= end) {
            const diffTime = Math.abs(end - start);
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
            const total = dailyRate * quantity * diffDays;
            totalPriceDiv.textContent = '$' + total.toFixed(2);
        }
    }

    startDate.addEventListener('change', calculateTotal);
    endDate.addEventListener('change', calculateTotal);
});
</script>
@endpush