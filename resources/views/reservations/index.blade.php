@extends('layouts.admin')

@section('title', ' - Reservations')
@section('page-title', 'Reservation Management')

@section('content')
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-0">Total Reservations</h6>
                        <h2 class="mt-2 mb-0">{{ $totalReservations ?? \App\Models\Reservation::count() }}</h2>
                    </div>
                    <i class="bi bi-calendar-check display-4 opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-0">Pending</h6>
                        <h2 class="mt-2 mb-0">{{ $pendingCount ?? \App\Models\Reservation::where('status', 'pending')->count() }}</h2>
                    </div>
                    <i class="bi bi-hourglass-split display-4 opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-0">Confirmed</h6>
                        <h2 class="mt-2 mb-0">{{ $confirmedCount ?? \App\Models\Reservation::where('status', 'confirmed')->count() }}</h2>
                    </div>
                    <i class="bi bi-check-circle display-4 opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-0">Revenue</h6>
                        <h2 class="mt-2 mb-0">₱{{ number_format($totalRevenue ?? \App\Models\Reservation::where('status', 'completed')->sum('total_price'), 2) }}</h2>
                    </div>
                    <i class="bi bi-cash-stack display-4 opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div>
            <i class="bi bi-calendar-check me-2"></i>
            <span>Reservations List</span>
        </div>
        <div>
            <button class="btn btn-outline-secondary btn-sm me-2" type="button" data-bs-toggle="collapse" data-bs-target="#filterCollapse">
                <i class="bi bi-funnel"></i> Filter
            </button>
            <a href="{{ route('reservations.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-circle"></i> New Reservation
            </a>
        </div>
    </div>
    
    <!-- Filter Section -->
    <div class="collapse" id="filterCollapse">
        <div class="card-body border-bottom">
            <form method="GET" action="{{ route('reservations.index') }}" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select form-select-sm">
                        <option value="">All Status</option>
                        <option value="pending">Pending</option>
                        <option value="confirmed">Confirmed</option>
                        <option value="completed">Completed</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Date Range</label>
                    <select name="date_range" class="form-select form-select-sm">
                        <option value="">All Time</option>
                        <option value="today">Today</option>
                        <option value="week">This Week</option>
                        <option value="month">This Month</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Equipment</label>
                    <select name="equipment" class="form-select form-select-sm">
                        <option value="">All Equipment</option>
                        @foreach(\App\Models\Equipment::all() as $equipment)
                            <option value="{{ $equipment->id }}">{{ $equipment->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Search</label>
                    <input type="text" name="search" class="form-control form-control-sm" placeholder="Customer name/email...">
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary btn-sm">Apply Filters</button>
                    <a href="{{ route('reservations.index') }}" class="btn btn-secondary btn-sm">Clear</a>
                </div>
            </form>
        </div>
    </div>
    
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Customer</th>
                        <th>Equipment</th>
                        <th>Rental Period</th>
                        <th>Duration</th>
                        <th>Qty</th>
                        <th>Total Price</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reservations as $reservation)
                    <tr>
                        <td>#{{ $reservation->id }}</td>
                        <td>
                            <div class="fw-bold">{{ $reservation->customer_name }}</div>
                            <small class="text-muted">{{ $reservation->customer_email }}</small>
                            <br>
                            <small class="text-muted">{{ $reservation->customer_phone }}</small>
                        </td>
                        <td>
                            <a href="{{ route('equipment.show', $reservation->equipment) }}" class="text-decoration-none">
                                {{ $reservation->equipment->name }}
                            </a>
                            <br>
                            <small class="text-muted">{{ $reservation->equipment->brand }}</small>
                        </td>
                        <td>
                            <div>{{ $reservation->start_date->format('M d, Y') }}</div>
                            <div>to</div>
                            <div>{{ $reservation->end_date->format('M d, Y') }}</div>
                        </td>
                        <td>
                            <span class="badge bg-info">
                                {{ $reservation->days }} days
                            </span>
                        </td>
                        <td>{{ $reservation->quantity }}</td>
                        <td>
                            <span class="fw-bold text-primary">₱{{ number_format($reservation->total_price, 2) }}</span>
                        </td>
                        <td>
                            @php
                                $statusClasses = [
                                    'pending' => 'bg-warning',
                                    'confirmed' => 'bg-success',
                                    'completed' => 'bg-info',
                                    'cancelled' => 'bg-danger'
                                ];
                            @endphp
                            <span class="badge {{ $statusClasses[$reservation->status] }} fs-6">
                                {{ ucfirst($reservation->status) }}
                            </span>
                        </td>
                        <td>
                            <div class="btn-group-vertical btn-group-sm" role="group">
                                <a href="{{ route('reservations.show', $reservation) }}" class="btn btn-info" title="View">
                                    <i class="bi bi-eye"></i>
                                </a>
                                
                                @if($reservation->status === 'pending')
                                    <a href="{{ route('reservations.edit', $reservation) }}" class="btn btn-warning" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <button type="button" class="btn btn-success" title="Confirm"
                                            onclick="updateStatus({{ $reservation->id }}, 'confirm')">
                                        <i class="bi bi-check-lg"></i>
                                    </button>
                                @endif
                                
                                @if(in_array($reservation->status, ['pending', 'confirmed']))
                                    <button type="button" class="btn btn-danger" title="Cancel"
                                            onclick="updateStatus({{ $reservation->id }}, 'cancel')">
                                        <i class="bi bi-x-lg"></i>
                                    </button>
                                @endif
                                
                                @if($reservation->status === 'confirmed')
                                    <button type="button" class="btn btn-primary" title="Complete"
                                            onclick="updateStatus({{ $reservation->id }}, 'complete')">
                                        <i class="bi bi-check-all"></i>
                                    </button>
                                @endif
                            </div>
                            
                            <form id="status-form-{{ $reservation->id }}-confirm" 
                                  action="{{ route('reservations.confirm', $reservation) }}" method="POST" class="d-none">
                                @csrf
                            </form>
                            <form id="status-form-{{ $reservation->id }}-complete" 
                                  action="{{ route('reservations.complete', $reservation) }}" method="POST" class="d-none">
                                @csrf
                            </form>
                            <form id="status-form-{{ $reservation->id }}-cancel" 
                                  action="{{ route('reservations.cancel', $reservation) }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-5">
                            <i class="bi bi-calendar-x display-1 text-muted"></i>
                            <p class="text-muted mt-3">No reservations found</p>
                            <a href="{{ route('reservations.create') }}" class="btn btn-primary">
                                <i class="bi bi-plus-circle"></i> Create Your First Reservation
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if(method_exists($reservations, 'links'))
        <div class="d-flex justify-content-end mt-3">
            {{ $reservations->links() }}
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
function updateStatus(id, action) {
    let message = '';
    switch(action) {
        case 'confirm':
            message = 'Confirm this reservation?';
            break;
        case 'complete':
            message = 'Mark this reservation as completed?';
            break;
        case 'cancel':
            message = 'Cancel this reservation?';
            break;
    }
    
    if (confirm(message)) {
        document.getElementById(`status-form-${id}-${action}`).submit();
    }
}
</script>
@endpush
@endsection