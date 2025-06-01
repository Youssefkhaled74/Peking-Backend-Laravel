<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <!-- Custom CSS -->
    <style>
        :root {
            --primary-red: #dc3545;
            --dark-red: #c82333;
            --light-gray: #f8f9fa;
            --medium-gray: #e9ecef;
            --dark-gray: #333;
        }
        
        body {
            background-color: #f5f5f5;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .navbar {
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            background-color: white !important;
        }
        
        .bg-red {
            background-color: var(--primary-red);
        }
        
        .text-white {
            color: #ffffff !important;
        }
        
        .btn-red {
            background-color: var(--primary-red);
            border-color: var(--primary-red);
            color: #ffffff;
            padding: 0.5rem 1.5rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .btn-red:hover, .btn-red:focus {
            background-color: var(--dark-red);
            border-color: var(--dark-red);
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(220, 53, 69, 0.3);
        }
        
        .btn-outline-red {
            border-color: var(--primary-red);
            color: var(--primary-red);
        }
        
        .btn-outline-red:hover {
            background-color: var(--primary-red);
            color: white;
        }
        
        .card {
            border-radius: 0.5rem;
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: none;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        
        .card-header {
            border-bottom: none;
            padding: 1.25rem 1.5rem;
        }
        
        .card-body {
            padding: 2rem;
        }
        
        .nav-link.active {
            color: var(--primary-red) !important;
            font-weight: 600;
        }
        
        .nav-link:hover {
            color: var(--dark-red) !important;
        }
        
        .nav-back-btn {
            color: var(--primary-red);
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .nav-back-btn:hover {
            color: var(--dark-red);
            transform: translateX(-3px);
        }
        
        .dashboard-card {
            background-color: white;
            text-align: center;
            height: 100%;
        }
        
        .dashboard-card .card-body {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
        
        .dashboard-card i {
            font-size: 2.5rem;
            color: var(--primary-red);
            margin-bottom: 1rem;
        }
        
        .dashboard-card h5 {
            color: var(--dark-gray);
            font-weight: 600;
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    @include('layouts.partials.navbar')

    <!-- Main Content -->
    <div class="container py-4">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-red text-white">
                <h2 class="mb-0"><i class="bi bi-house-door me-2"></i>Dashboard</h2>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-3">
                        <a href="{{ route('items.branch-index') }}" class="text-decoration-none">
                            <div class="card dashboard-card">
                                <div class="card-body">
                                    <i class="bi bi-box-seam"></i>
                                    <h5 class="card-title">Manage Items</h5>
                                    <p class="card-text text-muted">View and edit item relations and branches.</p>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('branches.index') }}" class="text-decoration-none">
                            <div class="card dashboard-card">
                                <div class="card-body">
                                    <i class="bi bi-geo-alt"></i>
                                    <h5 class="card-title">Manage Branches</h5>
                                    <p class="card-text text-muted">View and update branch details.</p>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('brands') }}" class="text-decoration-none">
                            <div class="card dashboard-card">
                                <div class="card-body">
                                    <i class="bi bi-bookmark-star"></i>
                                    <h5 class="card-title">Manage Brands</h5>
                                    <p class="card-text text-muted">Create, edit, or delete brands.</p>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('offers.create') }}" class="text-decoration-none">
                            <div class="card dashboard-card">
                                <div class="card-body">
                                    <i class="bi bi-tag"></i>
                                    <h5 class="card-title">Manage Offers</h5>
                                    <p class="card-text text-muted">Create new offers for items.</p>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('dashboard.order_ratings') }}" class="text-decoration-none">
                            <div class="card dashboard-card">
                                <div class="card-body">
                                    <i class="bi bi-star-fill"></i>
                                    <h5 class="card-title">Order Ratings</h5>
                                    <p class="card-text text-muted">View customer ratings for orders.</p>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('dashboard.userMoreData') }}" class="text-decoration-none">
                            <div class="card dashboard-card">
                                <div class="card-body">
                                    <i class="bi bi-people"></i>
                                    <h5 class="card-title">User More Data</h5>
                                    <p class="card-text text-muted">View user WhatsApp numbers and referral codes.</p>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('dashboard.orders.preparation_time') }}" class="text-decoration-none">
                            <div class="card dashboard-card">
                                <div class="card-body">
                                    <i class="bi bi-clock-fill"></i>
                                    <h5 class="card-title">Manage Preparation Time</h5>
                                    <p class="card-text text-muted">Update preparation times for orders.</p>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('areas.select-branch') }}" class="text-decoration-none">
                            <div class="card dashboard-card">
                                <div class="card-body">
                                    <i class="bi bi-map"></i>
                                    <h5 class="card-title">Manage Areas</h5>
                                    <p class="card-text text-muted">View and manage areas for branches.</p>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('chef_management.index') }}" class="text-decoration-none">
                            <div class="card dashboard-card">
                                <div class="card-body">
                                    <i class="bi bi-person"></i>
                                    <h5 class="card-title">Manage Chefs</h5>
                                    <p class="card-text text-muted">Add, view, or delete chefs and assign branches.</p>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('coupon') }}" class="text-decoration-none">
                            <div class="card dashboard-card">
                                <div class="card-body">
                                    <i class="bi bi-tags"></i>
                                    <h5 class="card-title">Coupon Dashboard</h5>
                                    <p class="card-text text-muted">Manage coupons and user promotions.</p>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>