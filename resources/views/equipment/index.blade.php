@extends('layouts.admin')

@section('title', ' - Equipment')
@section('page-title', 'Equipment Management')

@section('content')
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-0">Total Equipment</h6>
                        <h2 class="mt-2 mb-0">{{ $totalEquipment ?? \App\Models\Equipment::count() }}</h2>
                    </div>
                    <i class="bi bi-camera display-4 opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-0">Available Now</h6>
                        <h2 class="mt-2 mb-0">{{ $availableNow ?? \App\Models\Equipment::where('quantity_available', '>', 0)->count() }}</h2>
                    </div>
                    <i class="bi bi-check-circle display-4 opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-0">Categories</h6>
                        <h2 class="mt-2 mb-0">{{ \App\Models\Category::count() }}</h2>
                    </div>
                    <i class="bi bi-tags display-4 opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-0">Total Value</h6>
                        <h2 class="mt-2 mb-0">₱{{ number_format($totalValue ?? 0, 0) }}</h2>
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
            <i class="bi bi-camera me-2"></i> 
            <span>Equipment Inventory</span>
        </div>
        <div>
            <button class="btn btn-outline-secondary btn-sm me-2" type="button" data-bs-toggle="collapse" data-bs-target="#filterCollapse">
                <i class="bi bi-funnel"></i> Filter
            </button>
            <a href="{{ route('equipment.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-circle"></i> Add New Equipment
            </a>
        </div>
    </div>
    
    <!-- Filter Section -->
    <div class="collapse" id="filterCollapse">
        <div class="card-body border-bottom">
            <form method="GET" action="{{ route('equipment.index') }}" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Category</label>
                    <select name="category" class="form-select form-select-sm">
                        <option value="">All Categories</option>
                        @foreach(\App\Models\Category::all() as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Condition</label>
                    <select name="condition" class="form-select form-select-sm">
                        <option value="">All Conditions</option>
                        <option value="Excellent">Excellent</option>
                        <option value="Good">Good</option>
                        <option value="Fair">Fair</option>
                        <option value="Needs Repair">Needs Repair</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Availability</label>
                    <select name="availability" class="form-select form-select-sm">
                        <option value="">All</option>
                        <option value="in_stock">In Stock</option>
                        <option value="out_of_stock">Out of Stock</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Search</label>
                    <input type="text" name="search" class="form-control form-control-sm" placeholder="Search equipment...">
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary btn-sm">Apply Filters</button>
                    <a href="{{ route('equipment.index') }}" class="btn btn-secondary btn-sm">Clear</a>
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
                        <th>Equipment</th>
                        <th>Category</th>
                        <th>Brand/Model</th>
                        <th>Daily Rate</th>
                        <th>Stock</th>
                        <th>Condition</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($equipment as $item)
                    <tr>
                        <td>#{{ $item->id }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="bg-light rounded p-2 me-3">
                                    <i class="bi bi-camera fs-4"></i>
                                </div>
                                <div>
                                    <a href="{{ route('equipment.show', $item) }}" class="text-decoration-none fw-bold">
                                        {{ $item->name }}
                                    </a>
                                    <br>
                                    <small class="text-muted">Added: {{ $item->created_at->format('M d, Y') }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark">{{ $item->category->name ?? 'N/A' }}</span>
                        </td>
                        <td>
                            <div>{{ $item->brand }}</div>
                            <small class="text-muted">{{ $item->model ?? 'No model' }}</small>
                        </td>
                        <td>
                            <span class="fw-bold text-primary">₱{{ number_format($item->daily_rate, 2) }}</span>
                            <br>
                            <small class="text-muted">per day</small>
                        </td>
                        <td>
                            @if($item->quantity_available > 5)
                                <span class="badge bg-success">{{ $item->quantity_available }} units</span>
                            @elseif($item->quantity_available > 0)
                                <span class="badge bg-warning">{{ $item->quantity_available }} units</span>
                            @else
                                <span class="badge bg-danger">Out of stock</span>
                            @endif
                        </td>
                        <td>
                            @php
                                $conditionColors = [
                                    'Excellent' => 'success',
                                    'Good' => 'info',
                                    'Fair' => 'warning',
                                    'Needs Repair' => 'danger'
                                ];
                            @endphp
                            <span class="badge bg-{{ $conditionColors[$item->condition] ?? 'secondary' }}">
                                {{ $item->condition }}
                            </span>
                        </td>
                        <td>
                            @if($item->quantity_available > 0)
                                <span class="badge bg-success">Available</span>
                            @else
                                <span class="badge bg-danger">Unavailable</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('equipment.show', $item) }}" class="btn btn-sm btn-info" title="View">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('equipment.edit', $item) }}" class="btn btn-sm btn-warning" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <button type="button" class="btn btn-sm btn-primary" title="Create Reservation" 
                                        onclick="createReservation({{ $item->id }})">
                                    <i class="bi bi-calendar-plus"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-danger" title="Delete"
                                        onclick="confirmDelete({{ $item->id }})">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                            <form id="delete-form-{{ $item->id }}" action="{{ route('equipment.destroy', $item) }}" method="POST" class="d-none">
                                @csrf
                                @method('DELETE')
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-5">
                            <i class="bi bi-camera display-1 text-muted"></i>
                            <p class="text-muted mt-3">No equipment found</p>
                            <a href="{{ route('equipment.create') }}" class="btn btn-primary">
                                <i class="bi bi-plus-circle"></i> Add Your First Equipment
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if(method_exists($equipment, 'links'))
        <div class="d-flex justify-content-end mt-3">
            {{ $equipment->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Quick Reservation Modal -->
<div class="modal fade" id="reservationModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Quick Reservation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="quickReservationForm" method="POST" action="{{ route('reservations.store') }}">
                    @csrf
                    <input type="hidden" name="equipment_id" id="modal_equipment_id">
                    
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
                        <input type="number" name="quantity" class="form-control" value="1" min="1" required>
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
function confirmDelete(id) {
    if (confirm('Are you sure you want to delete this equipment?')) {
        document.getElementById('delete-form-' + id).submit();
    }
}

function createReservation(equipmentId) {
    document.getElementById('modal_equipment_id').value = equipmentId;
    new bootstrap.Modal(document.getElementById('reservationModal')).show();
}
</script>
@endpush
@endsection