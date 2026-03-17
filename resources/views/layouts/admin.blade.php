<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Multimedia Equipment Rental @yield('title')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <style>
        body {
            background: #f4f6f9;
        }
        .wrapper {
            display: flex;
            width: 100%;
        }
        .sidebar {
            width: 250px;
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #fff;
            transition: all 0.3s;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        }
        .sidebar .sidebar-header {
            padding: 20px;
            background: rgba(0,0,0,0.1);
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        .sidebar .sidebar-header h3 {
            margin: 0;
            font-size: 1.5rem;
            font-weight: 300;
        }
        .sidebar .sidebar-header p {
            margin: 5px 0 0;
            font-size: 0.8rem;
            opacity: 0.8;
        }
        .sidebar ul.components {
            padding: 20px 0;
        }
        .sidebar ul li {
            padding: 10px 20px;
            list-style: none;
        }
        .sidebar ul li a {
            color: #fff;
            text-decoration: none;
            display: block;
            padding: 10px;
            border-radius: 5px;
            transition: all 0.3s;
        }
        .sidebar ul li a:hover {
            background: rgba(255,255,255,0.1);
            transform: translateX(5px);
        }
        .sidebar ul li a i {
            margin-right: 10px;
            width: 20px;
        }
        .sidebar ul li.active a {
            background: rgba(255,255,255,0.2);
            border-left: 3px solid #fff;
        }
        .content {
            width: calc(100% - 250px);
            padding: 20px;
        }
        .navbar-top {
            background: #fff;
            padding: 15px 20px;
            margin-bottom: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }
        .page-title {
            margin: 0 0 20px 0;
            color: #333;
            font-size: 1.8rem;
            font-weight: 300;
        }
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            margin-bottom: 20px;
        }
        .card-header {
            background: #fff;
            border-bottom: 1px solid #eee;
            padding: 15px 20px;
            font-weight: 600;
        }
        .stats-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #fff;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
        }
        .stats-card i {
            font-size: 3rem;
            opacity: 0.3;
            position: absolute;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
        }
        .table thead th {
            border-top: none;
            border-bottom: 2px solid #dee2e6;
            font-weight: 600;
            color: #495057;
        }
        .btn-action {
            padding: 5px 10px;
            margin: 0 2px;
        }
        .badge-pending { background: #ffc107; color: #000; }
        .badge-confirmed { background: #28a745; color: #fff; }
        .badge-completed { background: #17a2b8; color: #fff; }
        .badge-cancelled { background: #dc3545; color: #fff; }
    </style>
</head>
<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <nav class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <h3>Multimedia Rental</h3>
                <p>Administration Panel</p>
            </div>

            <ul class="components">
                <li class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <a href="{{ route('dashboard') }}">
                        <i class="bi bi-speedometer2"></i> Dashboard
                    </a>
                </li>
                <li class="{{ request()->routeIs('equipment.*') ? 'active' : '' }}">
                    <a href="{{ route('equipment.index') }}">
                        <i class="bi bi-camera"></i> Equipment
                    </a>
                </li>
                <li class="{{ request()->routeIs('categories.*') ? 'active' : '' }}">
                    <a href="{{ route('categories.index') }}">
                        <i class="bi bi-tags"></i> Categories
                    </a>
                </li>
                <li class="{{ request()->routeIs('reservations.*') ? 'active' : '' }}">
                    <a href="{{ route('reservations.index') }}">
                        <i class="bi bi-calendar-check"></i> Reservations
                    </a>
                </li>
            </ul>
        </nav>

        <!-- Page Content -->
        <div class="content">
            <!-- Top Navigation -->
            <div class="navbar-top d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-0">@yield('page-title', 'Dashboard')</h5>
                </div>
                <div class="d-flex align-items-center">
                    <span class="me-3">
                        <i class="bi bi-bell"></i>
                    </span>
                    <span class="me-3">
                        <i class="bi bi-envelope"></i>
                    </span>
                    <div class="dropdown">
                        <button class="btn btn-light dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle"></i> Admin
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="#"><i class="bi bi-person"></i> Profile</a></li>
                            <li><a class="dropdown-item" href="#"><i class="bi bi-gear"></i> Settings</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="#"><i class="bi bi-box-arrow-right"></i> Logout</a></li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Flash Messages -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle me-2"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Main Content -->
            @yield('content')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    @stack('scripts')
</body>
</html>