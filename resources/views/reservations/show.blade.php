@extends('layouts.admin')

@section('title', ' - Reservation #' . $reservation->id)
@section('page-title', 'Reservation Details')

@section('content')
<div class="row">
    <div class="col-md-8">
        <!-- Main Reservation Card -->
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <i class="bi bi-receipt me-2"></i>
                    <span>Reservation #{{ $reservation->id }}</span>
                </div>
                <div>
                    @php
                        $statusClasses = [
                            'pending' => 'warning',
                            'confirmed' => 'success',
                            'completed' => 'info',
                            'cancelled' => 'danger'
                        ];
                    @endphp
                    <span class="badge bg-{{ $statusClasses[$reservation->status] }} fs-6">
                        {{ ucfirst($reservation->status) }}
                    </span>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h5 class="mb-3">Customer Information</h5>
                        <table class="table table-borderless">
                            <tr>
                                <th style="width: 100px;">Name:</th>
                                <td class="fw-bold">{{ $reservation->customer_name }}</td>
                            </tr>
                            <tr>
                                <th>Email:</th>
                                <td>{{ $reservation->customer_email }}</td>
                            </tr>
                            <tr>
                                <th>Phone:</th>
                                <td>{{ $reservation->customer_phone }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h5 class="mb-3">Rental Summary</h5>
                        <table class="table table-borderless">
                            <tr>
                                <th style="width: 120px;">Equipment:</th>
                                <td>
                                    <a href="{{ route('equipment.show', $reservation->equipment) }}" class="text-decoration-none">
                                        {{ $reservation->equipment->name }}
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <th>Brand/Model:</th>
                                <td>{{ $reservation->equipment->brand }} {{ $reservation->equipment->model }}</td>
                            </tr>
                            <tr>
                                <th>Daily Rate:</th>
                                <td>₱{{ number_format($reservation->equipment->daily_rate, 2) }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
                
                <hr>
                
                <div class="row mt-3">
                    <div class="col-md-4">
                        <div class="bg-light p-3 rounded text-center">
                            <small class="text-muted">Start Date</small>
                            <h5 class="mt-2">{{ $reservation->start_date->format('F d, Y') }}</h5>
                            <small>{{ $reservation->start_date->format('l') }}</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="bg-light p-3 rounded text-center">
                            <small class="text-muted">End Date</small>
                            <h5 class="mt-2">{{ $reservation->end_date->format('F d, Y') }}</h5>
                            <small>{{ $reservation->end_date->format('l') }}</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="bg-primary text-white p-3 rounded text-center">
                            <small>Total Price</small>
                            <h3 class="mt-2">₱{{ number_format($reservation->total_price, 2) }}</h3>
                            <small>{{ $reservation->days }} days × {{ $reservation->quantity }} unit(s)</small>
                        </div>
                    </div>
                </div>
                
                @if($reservation->notes)
                <div class="mt-4">
                    <h5>Additional Notes</h5>
                    <div class="bg-light p-3 rounded">
                        {{ $reservation->notes }}
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <!-- Status Timeline Card -->
        <div class="card mb-4">
            <div class="card-header">
                <i class="bi bi-clock-history me-2"></i>
                <span>Status Timeline</span>
            </div>
            <div class="card-body">
                <div class="timeline">
                    <div class="d-flex mb-3">
                        <div class="me-3">
                            <span class="badge bg-success rounded-circle p-2">✓</span>
                        </div>
                        <div>
                            <p class="mb-0 fw-bold">Reservation Created</p>
                            <small class="text-muted">{{ $reservation->created_at->format('F d, Y h:i A') }}</small>
                        </div>
                    </div>
                    
                    @if($reservation->status != 'pending')
                    <div class="d-flex mb-3">
                        <div class="me-3">
                            <span class="badge bg-{{ $statusClasses[$reservation->status] }} rounded-circle p-2">
                                @if($reservation->status == 'confirmed') ✓
                                @elseif($reservation->status == 'completed') ★
                                @else ✗
                                @endif
                            </span>
                        </div>
                        <div>
                            <p class="mb-0 fw-bold">Status Changed to {{ ucfirst($reservation->status) }}</p>
                            <small class="text-muted">{{ $reservation->updated_at->format('F d, Y h:i A') }}</small>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Actions Card -->
        <div class="card">
            <div class="card-header">
                <i class="bi bi-gear me-2"></i>
                <span>Actions</span>
            </div>
            <div class="card-body">
                @if($reservation->status === 'pending')
                    <div class="d-grid gap-2">
                        <form action="{{ route('reservations.confirm', $reservation) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success w-100 mb-2" onclick="return confirm('Confirm this reservation?')">
                                <i class="bi bi-check-lg"></i> Confirm Reservation
                            </button>
                        </form>
                        
                        <a href="{{ route('reservations.edit', $reservation) }}" class="btn btn-warning w-100 mb-2">
                            <i class="bi bi-pencil"></i> Edit Details
                        </a>
                        
                        <form action="{{ route('reservations.cancel', $reservation) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-danger w-100" onclick="return confirm('Cancel this reservation?')">
                                <i class="bi bi-x-lg"></i> Cancel Reservation
                            </button>
                        </form>
                    </div>
                @elseif($reservation->status === 'confirmed')
                    <div class="d-grid gap-2">
                        <form action="{{ route('reservations.complete', $reservation) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-primary w-100 mb-2" onclick="return confirm('Mark this reservation as completed?')">
                                <i class="bi bi-check-all"></i> Mark as Completed
                            </button>
                        </form>
                        
                        <form action="{{ route('reservations.cancel', $reservation) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-danger w-100" onclick="return confirm('Cancel this reservation?')">
                                <i class="bi bi-x-lg"></i> Cancel Reservation
                            </button>
                        </form>
                    </div>
                @endif
                
                <hr>
                
                <div class="d-grid gap-2">
                    <a href="{{ route('reservations.index') }}" class="btn btn-secondary w-100">
                        <i class="bi bi-arrow-left"></i> Back to List
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Quick Info Card -->
        <div class="card mt-4">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <i class="bi bi-info-circle fs-4 me-3"></i>
                    <div>
                        <small class="text-muted">Reservation created by</small>
                        <p class="mb-0 fw-bold">Administrator</p>
                    </div>
                </div>
                <div class="d-flex align-items-center">
                    <i class="bi bi-arrow-repeat fs-4 me-3"></i>
                    <div>
                        <small class="text-muted">Last updated</small>
                        <p class="mb-0 fw-bold">{{ $reservation->updated_at->diffForHumans() }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection