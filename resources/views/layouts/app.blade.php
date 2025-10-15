<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Encore Inventory</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
        }
        
        /* Sidebar */
        #sidebar {
            min-width: 250px;
            max-width: 250px;
            min-height: 100vh;
            background: #343a40;
            color: #fff;
            transition: all 0.3s;
            position: relative;
        }
        
        #sidebar.collapsed {
            min-width: 70px;
            max-width: 70px;
        }
        
        #sidebar.collapsed .sidebar-header span {
            display: none;
        }
        
        #sidebar.collapsed ul li a {
            text-align: center;
            padding: 15px 10px;
        }
        
        #sidebar.collapsed ul li a i {
            margin-right: 0;
            font-size: 1.2rem;
        }
        
        #sidebar.collapsed ul li a span {
            display: none;
        }
        
        #sidebar .sidebar-header {
            padding: 12px 15px;
            background: #212529;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        #sidebar.collapsed .sidebar-header {
            padding: 12px 8px;
        }
        
        #sidebar .sidebar-header h3 {
            margin: 0;
            font-size: 1.2rem;
            font-weight: 600;
            line-height: 1;
        }
        
        #sidebar .sidebar-header span {
            font-size: 0.75rem;
            opacity: 0.8;
        }

        #sidebar .sidebar-header .btn-close {
            padding: 4px;
            font-size: 0.8rem;
        }
        
        #sidebar ul.components {
            padding: 20px 0;
        }
        
        #sidebar ul li a {
            padding: 15px 20px;
            display: block;
            color: #fff;
            text-decoration: none;
            transition: all 0.3s;
        }
        
        #sidebar ul li a:hover {
            background: #495057;
        }
        
        #sidebar ul li a i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }
        
        #sidebar ul li a span {
            transition: opacity 0.3s;
            white-space: nowrap;
        }
        
        #sidebar.collapsed ul li a span {
            opacity: 0;
            width: 0;
            overflow: hidden;
        }
        
        #sidebar ul li.active > a {
            background: #0d6efd;
        }
        
        /* Content */
        #content {
            width: 100%;
            min-height: 100vh;
            transition: all 0.3s;
        }
        
        /* Header */
        .navbar {
            padding: 10px;
            background: #fff;
            border: none;
            border-radius: 0;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        
        .navbar-btn {
            box-shadow: none;
            outline: none !important;
            border: none;
        }
        
        @media (max-width: 768px) {
            .navbar .container-fluid {
                padding: 8px;
            }
            
            .navbar .btn {
                padding: 4px 8px;
                font-size: 14px;
            }
            
            .navbar .dropdown-toggle {
                padding: 4px 8px;
                font-size: 14px;
            }
            
            .navbar .rounded-circle {
                width: 28px;
                height: 28px;
            }
            
            .navbar form {
                flex: 1;
                max-width: none;
            }
            
            .navbar .input-group {
                width: 100%;
            }
            
            .navbar .input-group .form-control {
                height: 32px;
                font-size: 14px;
            }
            
            .navbar .input-group .btn {
                height: 32px;
                display: flex;
                align-items: center;
                justify-content: center;
            }
        }
        
        /* Card styling */
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        
        .card-header {
            background-color: #fff;
            border-bottom: 1px solid #eee;
            font-weight: 600;
            padding: 15px 20px;
        }
        
        /* Table styling */
        .table-container {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        
        /* Responsive sidebar */
        @media (max-width: 768px) {
            #sidebar {
                margin-left: -250px;
                position: fixed;
                z-index: 1050;
                height: 100%;
                top: 0;
                left: 0;
                box-shadow: 3px 0 10px rgba(0, 0, 0, 0.2);
            }
            
            #sidebar.active {
                margin-left: 0;
            }
            
            #content {
                margin-left: 0;
                width: 100%;
            }

            .navbar {
                position: fixed;
                width: 100%;
                top: 0;
                z-index: 1040;
            }

            .container-fluid.py-4 {
                padding-top: 80px !important;
            }
            
            #sidebarCollapse {
                display: block;
                padding: 6px 12px;
                margin-right: 15px;
            }

            .sidebar-overlay {
                display: none;
                position: fixed;
                width: 100vw;
                height: 100vh;
                background: rgba(0, 0, 0, 0.5);
                z-index: 1049;
                opacity: 0;
                transition: opacity 0.3s ease;
            }

            .sidebar-overlay.active {
                display: block;
                opacity: 1;
            }
        }
        
        /* Notification badge */
        .notification-badge {
            position: relative;
        }
        
        .badge-counter {
            position: absolute;
            top: -5px;
            right: -5px;
            font-size: 0.6rem;
        }
        
        @media (max-width: 768px) {
            .dropdown-menu {
                position: fixed !important;
                top: auto !important;
                right: 10px !important;
                left: 10px !important;
                transform: none !important;
                max-height: calc(100vh - 200px);
                overflow-y: auto;
            }
            
            .dropdown-item {
                padding: 0.5rem 1rem;
            }
            
            .d-sm-inline-block {
                display: none !important;
            }
            
            .ms-auto {
                margin-left: 0 !important;
            }
            
            .navbar .container-fluid > .ms-auto {
                flex: 1;
                justify-content: flex-end;
                gap: 8px;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar Overlay -->
    <div class="sidebar-overlay"></div>

    <div class="d-flex">
        <!-- Sidebar -->
        <nav id="sidebar">
            <div class="sidebar-header d-flex align-items-center justify-content-between">
                <div>
                    <h3>ENCORE</h3>
                    <span class="ms-2">CUSTOM</span>
                </div>
                <button type="button" class="btn-close d-block d-md-none text-white" aria-label="Close"></button>
            </div>
            
            <ul class="list-unstyled components">
                <li class="{{ request()->is('/') ? 'active' : '' }}">
                    <a href="/" data-bs-toggle="tooltip" data-bs-placement="right" title="Dashboard">
                        <i class="fas fa-tachometer-alt"></i> 
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="{{ request()->is('orders*') ? 'active' : '' }}">
                    <a href="/orders" data-bs-toggle="tooltip" data-bs-placement="right" title="Orders">
                        <i class="fas fa-shopping-cart"></i> 
                        <span>Orders</span>
                    </a>
                </li>
                <li class="{{ request()->is('inventory*') ? 'active' : '' }}">
                    <a href="/inventory" data-bs-toggle="tooltip" data-bs-placement="right" title="Inventory">
                        <i class="fas fa-boxes"></i> 
                        <span>Inventory</span>
                    </a>
                </li>
                <li class="{{ request()->is('payments*') ? 'active' : '' }}">
                    <a href="/payments" data-bs-toggle="tooltip" data-bs-placement="right" title="Payments">
                        <i class="fas fa-credit-card"></i> 
                        <span>Payments</span>
                    </a>
                </li>
                <li class="{{ request()->is('customers*') ? 'active' : '' }}">
                    <a href="/customers" data-bs-toggle="tooltip" data-bs-placement="right" title="Customers">
                        <i class="fas fa-users"></i> 
                        <span>Customers</span>
                    </a>
                </li>
                <li class="{{ request()->is('reports*') ? 'active' : '' }}">
                    <a href="/reports" data-bs-toggle="tooltip" data-bs-placement="right" title="Reports">
                        <i class="fas fa-chart-bar"></i> 
                        <span>Reports</span>
                    </a>
                </li>
                <li class="{{ request()->is('settings*') ? 'active' : '' }}">
                    <a href="/settings" data-bs-toggle="tooltip" data-bs-placement="right" title="Settings">
                        <i class="fas fa-cog"></i> 
                        <span>Settings</span>
                    </a>
                </li>
            </ul>
        </nav>
        
        <!-- Page Content -->
        <div id="content">
            <!-- Top Navbar -->
            <nav class="navbar navbar-expand-lg navbar-light">
                <div class="container-fluid">
                    <button type="button" id="sidebarCollapse" class="btn btn-dark">
                        <i class="fas fa-bars"></i>
                    </button>
                    
                    <div class="ms-auto d-flex align-items-center gap-2">
                        <!-- Search -->
                        <form class="d-flex me-2 flex-grow-1">
                            <div class="input-group">
                                <input type="text" class="form-control form-control-sm" placeholder="Search..." aria-label="Search">
                                <button class="btn btn-outline-secondary btn-sm" type="button">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </form>
                        
                        <!-- Notifications -->
                        <div class="dropdown me-3">
                            <a class="btn btn-light notification-badge" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-bell"></i>
                                <span class="badge bg-danger badge-counter">3</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuLink">
                                <li><h6 class="dropdown-header">Notifications</h6></li>
                                <li><a class="dropdown-item" href="#">New order received</a></li>
                                <li><a class="dropdown-item" href="#">Low stock alert</a></li>
                                <li><a class="dropdown-item" href="#">Payment confirmed</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-center" href="#">Show all notifications</a></li>
                            </ul>
                        </div>
                        
                        <!-- User -->
                        <div class="dropdown">
                            <a class="btn btn-light dropdown-toggle" href="#" role="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                <img src="https://via.placeholder.com/30" class="rounded-circle me-2" alt="User">
                                <span class="d-none d-lg-inline">User Name</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i> Profile</a></li>
                                <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i> Settings</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="#"><i class="fas fa-sign-out-alt me-2"></i> Logout</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </nav>
            
            <!-- Main Content -->
            <div class="container-fluid py-4">
                <div class="px-2 px-sm-3">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>
    
    <style>
        @media (max-width: 768px) {
            .container-fluid {
                padding-left: 12px;
                padding-right: 12px;
            }
            
            .card {
                margin-bottom: 15px;
                border-radius: 8px;
            }
            
            .h2 {
                font-size: 1.25rem;
                margin-bottom: 0.5rem;
            }
            
            .header-actions {
                display: flex;
                gap: 8px;
                flex-wrap: wrap;
            }
            
            .header-actions > * {
                flex: 1;
                min-width: 120px;
            }
            
            .filters-section {
                display: grid;
                grid-template-columns: 1fr;
                gap: 10px;
            }
            
            @media (min-width: 576px) {
                .filters-section {
                    grid-template-columns: 1fr 1fr;
                }
            }
            
            .table-responsive {
                margin: 0 -12px;
                padding: 0;
                border-radius: 0;
            }
            
            .table-responsive .table {
                margin: 0;
            }
            
            .table th, .table td {
                padding: 12px 8px;
                font-size: 14px;
                white-space: nowrap;
            }
            
            .btn {
                padding: 6px 12px;
                font-size: 14px;
                height: 36px;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                white-space: nowrap;
            }
            
            .btn i {
                font-size: 16px;
            }
            
            .input-group {
                width: 100%;
                margin-bottom: 12px;
            }
            
            .form-control {
                height: 36px;
                font-size: 14px;
            }
            
            .form-select {
                height: 36px;
                font-size: 14px;
                padding-right: 28px;
            }
            
            .d-flex {
                flex-wrap: wrap;
                gap: 10px;
            }
            
            .nav-tabs {
                overflow-x: auto;
                flex-wrap: nowrap;
                -webkit-overflow-scrolling: touch;
            }
            
            .nav-tabs .nav-link {
                white-space: nowrap;
                padding: 0.5rem 1rem;
            }
        }
    </style>
    
    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    
    <script>
        $(document).ready(function() {
            // Toggle sidebar
            $('#sidebarCollapse').on('click', function() {
                if ($(window).width() <= 768) {
                    $('#sidebar').addClass('active');
                    $('.sidebar-overlay').addClass('active');
                } else {
                    $('#sidebar').toggleClass('collapsed');
                    
                    // Toggle tooltips based on sidebar state
                    if ($('#sidebar').hasClass('collapsed')) {
                        $('[data-bs-toggle="tooltip"]').tooltip('enable');
                    } else {
                        $('[data-bs-toggle="tooltip"]').tooltip('disable');
                    }
                }
            });
            
            // Initialize tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
            
            // Disable tooltips initially (sidebar not collapsed)
            tooltipList.forEach(function(tooltip) {
                tooltip.disable();
            });
            
            // Initialize DataTables
            $('.datatable').DataTable({
                responsive: true
            });
            
            // Close sidebar on mobile
            $('.sidebar-overlay, .btn-close').on('click', function() {
                $('#sidebar').removeClass('active');
                $('.sidebar-overlay').removeClass('active');
            });

            // Handle window resize
            $(window).on('resize', function() {
                if ($(window).width() > 768) {
                    $('#sidebar').removeClass('active');
                    $('.sidebar-overlay').removeClass('active');
                }
            });
        });
    </script>
    
    @yield('scripts')
</body>
</html>