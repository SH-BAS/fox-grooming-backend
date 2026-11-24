<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Staff Dashboard') - Fox Grooming</title>

    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.png') }}">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Admin Theme CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/admin-theme.css') }}">

    @stack('styles')
</head>

<body>
    <div class="admin-wrapper">
        <!-- Sidebar -->
        <aside class="admin-sidebar">
            <div class="sidebar-logo">
                <a href="{{ route('staff.dashboard') }}">
                    <img src="{{ asset('assets/images/logo.png') }}" alt="Fox Grooming">
                </a>
            </div>

            <nav class="sidebar-nav">
                <div class="nav-section-title">Main</div>

                <div class="nav-item">
                    <a href="{{ route('staff.dashboard') }}"
                        class="nav-link {{ request()->routeIs('staff.dashboard') ? 'active' : '' }}">
                        <i class="fas fa-home"></i>
                        <span>Dashboard</span>
                    </a>
                </div>

                <div class="nav-section-title">My Work</div>

                <div class="nav-item">
                    <a href="{{ route('staff.bookings.index') }}"
                        class="nav-link {{ request()->routeIs('staff.bookings.*') && !request()->routeIs('staff.bookings.schedule') ? 'active' : '' }}">
                        <i class="fas fa-calendar-check"></i>
                        <span>My Bookings</span>
                    </a>
                </div>

                <div class="nav-item">
                    <a href="{{ route('staff.bookings.schedule') }}"
                        class="nav-link {{ request()->routeIs('staff.bookings.schedule') ? 'active' : '' }}">
                        <i class="fas fa-calendar-alt"></i>
                        <span>Schedule</span>
                    </a>
                </div>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="admin-main">
            <!-- Header -->
            <header class="admin-header">
                <div class="header-left">
                    <h1 class="header-title">@yield('page-title', 'Dashboard')</h1>
                </div>

                <div class="header-right">
                    <div class="dropdown">
                        <div class="header-user" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false"
                            style="cursor: pointer;">
                            <div class="user-avatar overflow-hidden">
                                <img src="{{ auth()->guard('staff')->user()->image }}" alt="User"
                                    style="width: 100%; height: 100%; object-fit: cover;">
                            </div>
                            <div class="user-info">
                                <div class="user-name">{{ auth()->guard('staff')->user()->name ?? 'Staff' }}</div>
                                <div class="user-role">
                                    {{ auth()->guard('staff')->user()->role->name ?? 'Staff Member' }}</div>
                            </div>
                            <i class="fas fa-chevron-down ms-2"></i>
                        </div>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li><a class="dropdown-item" href="{{ route('staff.profile.edit') }}"><i
                                        class="fas fa-user-edit me-2"></i>Edit Profile</a></li>
                            <li><a class="dropdown-item" href="{{ route('staff.profile.password') }}"><i
                                        class="fas fa-key me-2"></i>Change Password</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <form action="{{ route('staff.logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger"><i
                                            class="fas fa-sign-out-alt me-2"></i>Logout</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </header>

            <!-- Content Body -->
            <div class="admin-content">
                @if(session('success'))
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i>
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle"></i>
                        {{ session('error') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle"></i>
                        <ul style="margin: 10px 0 0 20px;">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('assets/js/sweetalert-delete.js') }}"></script>

    <script>
        $(document).ready(function () {
            // Sidebar Submenu Toggle
            $('.has-submenu > .nav-link').on('click', function (e) {
                e.preventDefault();
                var $parent = $(this).parent();
                $parent.toggleClass('active');
                $parent.find('.submenu').slideToggle(300);
            });

            // Ensure active submenu is open on load
            $('.has-submenu.active .submenu').show();
        });
    </script>

    @stack('scripts')
</body>

</html>