@extends('layouts.admin')

@section('title', ' - Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="row">
    <!-- Stats Cards -->
    <div class="col-md-3">
        <div class="stats-card position-relative">
            <h2>{{ $totalEquipment ?? 0 }}</h2>
            <p>Total Equipment</p>
            <i class="bi bi-camera"></i>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-card position-relative" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
            <h2>{{ $availableEquipment ?? 0 }}</h2>
            <p>Available Now</p>
            <i class="bi bi-check-circle"></i>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-card position-relative" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
            <h2>{{ $activeReservations ?? 0 }}</h2>
            <p>Active Reservations</p>
            <i class="bi bi-calendar-check"></i>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-card position-relative" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
            <h2>₱{{ number_format($monthlyRevenue ?? 0, 2) }}</h2>
            <p>Monthly Revenue</p>
            <i class="bi bi-cash-stack"></i>
        </div>
    </div>
</div>

<div class="row">
    <!-- Recent Reservations -->
    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>Recent Reservations</span>
                <a href="{{ route('reservations.index') }}" class="btn btn-sm btn-primary">View All</a>
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Customer</th>
                            <th>Equipment</th>
                            <th>Dates</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentReservations ?? [] as $reservation)
                        <tr>
                            <td>{{ $reservation->customer_name }}</td>
                            <td>{{ $reservation->equipment->name ?? 'N/A' }}</td>
                            <td>{{ $reservation->start_date->format('M d') }} - {{ $reservation->end_date->format('M d') }}</td>
                            <td>
                                <span class="badge badge-{{ $reservation->status }}">
                                    {{ ucfirst($reservation->status) }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center">No recent reservations</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Popular Equipment -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                Popular Equipment
            </div>
            <div class="card-body">
                @forelse($popularEquipment ?? [] as $item)
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <strong>{{ $item->name }}</strong>
                        <br>
                        <small class="text-muted">{{ $item->category->name ?? 'N/A' }}</small>
                    </div>
                    <span class="badge bg-primary">{{ $item->reservations_count ?? 0 }} rentals</span>
                </div>
                @empty
                <p class="text-muted">No equipment data available</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection