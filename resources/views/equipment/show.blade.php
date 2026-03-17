@extends('layouts.admin')

@section('title', ' - ' . $equipment->name)
@section('page-title', 'Equipment Details')

@section('content')
<div class="row">
    <div class="col-md-8">
        <!-- Main Equipment Card -->
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <i class="bi bi-info-circle me-2"></i>
                    <span>Equipment Information</span>
                </div>
                <div class="btn-group">
                    <a href="{{ route('equipment.edit', $equipment) }}" class="btn btn-warning btn-sm">
                        <i class="bi bi-pencil"></i> Edit
                    </a>
                    <button type="button" class="btn btn-primary btn-sm" onclick="quickReservation()">
                        <i class="bi bi-calendar-plus"></i> Create Reservation
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h4 class="mb-3">{{ $equipment->name }}</h4>
                        
                        <table class="table table-borderless">
                            <tr>
                                <th style="width: 120px;">Category:</th>
                                <td>
                                    <span class="badge bg-light text-dark">{{ $equipment->category->name }}</span>
                                </td>
                            </tr>
                            <tr>
                                <th>Brand:</th>
                                <td>{{ $equipment->brand }}</td>
                            </tr>
                            <tr>
                                <th>Model:</th>
                                <td>{{ $equipment->model ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Daily Rate:</th>
                                <td class="fw-bold text-primary fs-5">₱{{ number_format($equipment->daily_rate, 2) }}/day</td>
                            </tr>
                            <tr>
                                <th>Condition:</th>
                                <td>
                                    @php
                                        $conditionColors = [
                                            'Excellent' => 'success',
                                            'Good' => 'info',
                                            'Fair' => 'warning',
                                            'Needs Repair' => 'danger'
                                        ];
                                    @endphp
                                    <span class="badge bg-{{ $conditionColors[$equipment->condition] ?? 'secondary' }} fs-6">
                                        {{ $equipment->condition }}
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="bg-light p-4 rounded">
                            <h5 class="mb-3">Stock Information</h5>
                            <div class="text-center mb-3">
                                <div class="display-1 fw-bold {{ $equipment->quantity_available > 0 ? 'text-success' : 'text-danger' }}">
                                    {{ $equipment->quantity_available }}
                                </div>
                                <p class="text-muted">Units Available</p>
                            </div>
                            
                            @if($equipment->quantity_available > 0)
                                <div class="progress mb-3" style="height: 10px;">
                                    @php
                                        $totalReserved = $equipment->reservations()
                                            ->whereIn('status', ['pending', 'confirmed'])
                                            ->where('end_date', '>=', now())
                                            ->sum('quantity');
                                        $percentage = ($totalReserved / $equipment->quantity_available) * 100;
                                    @endphp
                                    <div class="progress-bar bg-warning" style="width: {{ $percentage }}%"></div>
                                </div>
                                <p class="small text-muted text-center">
                                    {{ $totalReserved }} units currently reserved
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
                
                <hr>
                
                <div class="mt-3">
                    <h5>Description</h5>
                    <p class="text-muted">{{ $equipment->description }}</p>
                </div>
                
                <div class="mt-3">
                    <h5>Additional Information</h5>
                    <div class="row">
                        <div class="col-md-4">
                            <small class="text-muted d-block">Date Added</small>
                            <strong>{{ $equipment->created_at->format('F d, Y') }}</strong>
                        </div>
                        <div class="col-md-4">
                            <small class="text-muted d-block">Last Updated</small>
                            <strong>{{ $equipment->updated_at->format('F d, Y') }}</strong>
                        </div>
                        <div class="col-md-4">
                            <small class="text-muted d-block">Total Rentals</small>
                            <strong>{{ $equipment->reservations->count() }} times</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Recent Reservations Card -->
        <div class="card">
            <div class="card-header">
                <i class="bi bi-calendar-check me-2"></i>
                <span>Recent Reservations for this Equipment</span>
            </div>
            <div class="card-body">
                @if($equipment->reservations->isEmpty())
                    <p class="text-muted text-center py-3">No reservations yet for this equipment.</p>
                @else
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Customer</th>
                                    <th>Dates</th>
                                    <th>Quantity</th>
                                    <th>Status</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($equipment->reservations->take(5) as $reservation)
                                <tr>
                                    <td>{{ $reservation->customer_name }}</td>
                                    <td>{{ $reservation->start_date->format('M d') }} - {{ $reservation->end_date->format('M d') }}</td>
                                    <td>{{ $reservation->quantity }}</td>
                                    <td>
                                        <span class="badge bg-{{ $reservation->status == 'pending' ? 'warning' : ($reservation->status == 'confirmed' ? 'success' : 'secondary') }}">
                                            {{ ucfirst($reservation->status) }}
                                        </span>
                                    </td>
                                    <td>₱{{ number_format($reservation->total_price, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if($equipment->reservations->count() > 5)
                        <div class="text-end">
                            <a href="{{ route('reservations.index', ['equipment' => $equipment->id]) }}" class="btn btn-link">
                                View All Reservations
                            </a>
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <!-- Availability Calendar Card -->
        <div class="card mb-4">
            <div class="card-header">
                <i class="bi bi-calendar-week me-2"></i>
                <span>Availability This Week</span>
            </div>
            <div class="card-body">
                @php
                    $today = now();
                    $dates = [];
                    for($i = 0; $i < 7; $i++) {
                        $date = $today->copy()->addDays($i);
                        $reservedCount = $equipment->reservations()
                            ->whereIn('status', ['pending', 'confirmed'])
                            ->where('start_date', '<=', $date)
                            ->where('end_date', '>=', $date)
                            ->sum('quantity');
                        $dates[] = [
                            'date' => $date,
                            'available' => $equipment->quantity_available - $reservedCount,
                            'reserved' => $reservedCount
                        ];
                    }
                @endphp
                
                @foreach($dates as $dateInfo)
                <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom">
                    <div>
                        <strong>{{ $dateInfo['date']->format('D, M d') }}</strong>
                    </div>
                    <div>
                        @if($dateInfo['available'] > 0)
                            <span class="badge bg-success">{{ $dateInfo['available'] }} available</span>
                        @else
                            <span class="badge bg-danger">Fully booked</span>
                        @endif
                        @if($dateInfo['reserved'] > 0)
                            <span class="badge bg-warning">{{ $dateInfo['reserved'] }} reserved</span>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        
        <!-- Quick Stats Card -->
        <div class="card">
            <div class="card-header">
                <i class="bi bi-graph-up me-2"></i>
                <span>Quick Stats</span>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="text-muted">Total Revenue Generated</label>
                    <h4 class="text-success">₱{{ number_format($equipment->reservations->where('status', 'completed')->sum('total_price'), 2) }}</h4>
                </div>
                <div class="mb-3">
                    <label class="text-muted">Average Rental Days</label>
                    <h4>
                        @php
                            $avgDays = $equipment->reservations->avg(function($res) {
                                return $res->start_date->diffInDays($res->end_date) + 1;
                            });
                        @endphp
                        {{ number_format($avgDays ?? 0, 1) }} days
                    </h4>
                </div>
                <div>
                    <label class="text-muted">Most Frequent Customer</label>
                    <h4>
                        @php
                            $frequentCustomer = $equipment->reservations
                                ->groupBy('customer_email')
                                ->map->count()
                                ->sortDesc()
                                ->keys()
                                ->first();
                        @endphp
                        {{ $frequentCustomer ?: 'N/A' }}
                    </h4>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Reservation Modal -->
<div class="modal fade" id="quickReservationModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Reserve {{ $equipment->name }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="quickReservationForm" method="POST" action="{{ route('reservations.store') }}">
                    @csrf
                    <input type="hidden" name="equipment_id" value="{{ $equipment->id }}">
                    
                    <div class="mb-3">
                        <label class="form-label">Customer Name</label>
                        <input type="text" name="customer_name" class="form-control" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Customer Email</label>
                        <input type="email" name="customer_email" class="form-control" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Customer Phone</label>
                        <input type="text" name="customer_phone" class="form-control" required>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Start Date</label>
                            <input type="date" name="start_date" class="form-control" min="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">End Date</label>
                            <input type="date" name="end_date" class="form-control" min="{{ date('Y-m-d') }}" required>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Quantity</label>
                        <input type="number" name="quantity" class="form-control" value="1" min="1" max="{{ $equipment->quantity_available }}" required>
                        <small class="text-muted">Max available: {{ $equipment->quantity_available }}</small>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Daily Rate</label>
                        <div class="form-control bg-light">₱{{ number_format($equipment->daily_rate, 2) }}</div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" form="quickReservationForm" class="btn btn-primary">Create Reservation</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function quickReservation() {
    new bootstrap.Modal(document.getElementById('quickReservationModal')).show();
}
</script>
@endpush
@endsection