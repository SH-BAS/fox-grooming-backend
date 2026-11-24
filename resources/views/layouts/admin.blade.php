<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Dashboard') - Fox Grooming</title>

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

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    @stack('styles')

    <script>
        (function () {
            const browserTimezone = Intl.DateTimeFormat().resolvedOptions().timeZone;
            const expiryDate = new Date();
            expiryDate.setTime(expiryDate.getTime() + (7 * 24 * 60 * 60 * 1000));
            document.cookie = `timezone=${browserTimezone}; path=/; expires=${expiryDate.toUTCString()}; SameSite=Lax`;
        })();
    </script>
</head>

<body>
    <div class="admin-wrapper">
        <!-- Sidebar -->
        <aside class="admin-sidebar">
            <div class="sidebar-logo">
                <a href="{{ route('admin.dashboard') }}">
                    <img src="{{ asset('assets/images/logo.png') }}" alt="Fox Grooming">
                </a>
            </div>

            <nav class="sidebar-nav">
                <!-- check role -->
                @php
                    $role = Auth::guard('admin')->user()->role;
                @endphp
                @if ($role->slug == 'super_admin')
                    <div class="nav-item">
                        <a href="{{ route('admin.dashboard') }}"
                            class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            <i class="fas fa-home"></i>
                            <span>Dashboard</span>
                        </a>
                    </div>

                    <div class="nav-item has-submenu {{ request()->routeIs('admin.bookings.*') ? 'active' : '' }}">
                        <a href="javascript:void(0);" class="nav-link">
                            <i class="fas fa-calendar-check"></i>
                            <span>Booking</span>
                        </a>
                        <ul class="submenu">
                            <li>
                                <a href="{{ route('admin.bookings.index') }}"
                                    class="submenu-link {{ request()->routeIs('admin.bookings.*') && !request()->routeIs('admin.bookings.schedule') ? 'active' : '' }}">
                                    <i class="fas fa-list"></i>
                                    <span>All Bookings</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.bookings.schedule') }}"
                                    class="submenu-link {{ request()->routeIs('admin.bookings.schedule') ? 'active' : '' }}">
                                    <i class="fas fa-calendar-alt"></i>
                                    <span>Schedule</span>
                                </a>
                            </li>
                        </ul>
                    </div>

                    <div
                        class="nav-item has-submenu {{ request()->routeIs('admin.categories.*') || request()->routeIs('admin.category-types.*') || request()->routeIs('admin.sizes.*') || request()->routeIs('admin.services.*') ? 'active' : '' }}">
                        <a href="javascript:void(0);" class="nav-link">
                            <!-- categories management icon-->
                            <i class="fas fa-tags"></i>
                            <span>Categories</span>
                        </a>
                        <ul class="submenu">
                            <li>
                                <a href="{{ route('admin.categories.index') }}"
                                    class="submenu-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                                    <i class="fas fa-tags"></i>
                                    <span>List</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.category-types.index') }}"
                                    class="submenu-link {{ request()->routeIs('admin.category-types.*') ? 'active' : '' }}">
                                    <i class="fas fa-dog"></i>
                                    <span>Breeds</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.sizes.index') }}"
                                    class="submenu-link {{ request()->routeIs('admin.sizes.*') ? 'active' : '' }}">
                                    <i class="fas fa-ruler-combined"></i>
                                    <span>Sizes</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.services.index') }}"
                                    class="submenu-link {{ request()->routeIs('admin.services.*') ? 'active' : '' }}">
                                    <i class="fas fa-cut"></i>
                                    <span>Services</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <!-- submenu -->
                    <!-- <div class="nav-item has-submenu {{ request()->routeIs('admin.users.*') || request()->routeIs('admin.pets.*') ? 'active' : '' }}">
                        <a href="javascript:void(0);" class="nav-link">
                            <i class="fas fa-users"></i>
                            <span>Customers</span>
                        </a>
                        <ul class="submenu">
                            <li>
                                <a href="{{ route('admin.users.index') }}"
                                    class="submenu-link {{ request()->routeIs('admin.users.*') || request()->routeIs('admin.pets.*') ? 'active' : '' }}">
                                    <i class="fas fa-users"></i>
                                    <span>Users</span>
                                </a>
                            </li>
                        </ul>
                    </div> -->

                    <div class="nav-item">
                        <a href="{{ route('admin.users.index') }}"
                            class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                            <i class="fas fa-users"></i>
                            <span>Users</span>
                        </a>
                    </div>
                    <div class="nav-item">
                        <a href="{{ route('admin.staff.index') }}"
                            class="nav-link {{ request()->routeIs('admin.staff.*') ? 'active' : '' }}">
                            <i class="fas fa-user-tie"></i>
                            <span>Staff</span>
                        </a>
                    </div>
                    <div class="nav-item">
                        <a href="{{ route('admin.franchises.index') }}"
                            class="nav-link {{ request()->routeIs('admin.franchises.*') ? 'active' : '' }}">
                            <i class="fas fa-store"></i>
                            <span>Franchise</span>
                        </a>
                    </div>
                    <div class="nav-item">
                        <a href="{{ route('admin.zones.index') }}"
                            class="nav-link {{ request()->routeIs('admin.zones.*') ? 'active' : '' }}">
                            <i class="fas fa-layer-group"></i>
                            <span>Zones</span>
                        </a>
                    </div>
                    <div class="nav-item">
                        <a href="{{ route('admin.reviews.index') }}"
                            class="nav-link {{ request()->routeIs('admin.reviews.*') ? 'active' : '' }}">
                            <i class="fas fa-star"></i>
                            <span>Reviews</span>
                        </a>
                    </div>
                    <div class="nav-item">
                        <a href="{{ route('admin.contacts.index') }}"
                            class="nav-link {{ request()->routeIs('admin.contacts.*') ? 'active' : '' }}">
                            <i class="fas fa-envelope"></i>
                            <span>Contacts</span>
                        </a>
                    </div>

                    <div class="nav-item">
                        <a href="{{ route('admin.newsletters.index') }}"
                            class="nav-link {{ request()->routeIs('admin.newsletters.*') ? 'active' : '' }}">
                            <i class="fas fa-paper-plane"></i>
                            <span>Newsletters</span>
                        </a>
                    </div>

                    <div class="nav-item">
                        <a href="{{ route('admin.broadcast-notifications.index') }}"
                            class="nav-link {{ request()->routeIs('admin.broadcast-notifications.*') ? 'active' : '' }}">
                            <i class="fas fa-bullhorn"></i>
                            <span>Broadcast Notifications</span>
                        </a>
                    </div>

                    <div
                        class="nav-item has-submenu {{ request()->routeIs('admin.banners.*') || request()->routeIs('admin.galleries.*') ? 'active' : '' }}">
                        <a href="javascript:void(0);" class="nav-link">
                            <i class="fas fa-photo-video"></i>
                            <span>Content</span>
                        </a>
                        <ul class="submenu">
                            <li>
                                <a href="{{ route('admin.banners.index') }}"
                                    class="submenu-link {{ request()->routeIs('admin.banners.*') ? 'active' : '' }}">
                                    <i class="fas fa-image"></i>
                                    <span>Banners</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.galleries.index') }}"
                                    class="submenu-link {{ request()->routeIs('admin.galleries.*') ? 'active' : '' }}">
                                    <i class="fas fa-images"></i>
                                    <span>Gallery</span>
                                </a>
                            </li>
                        </ul>
                    </div>


                    <div class="nav-item has-submenu {{ request()->routeIs('admin.cms-pages.*') ? 'active' : '' }}">
                        <a href="javascript:void(0);" class="nav-link">
                            <i class="fas fa-file-alt"></i>
                            <span>CMS Pages</span>
                        </a>
                        <ul class="submenu">
                            <li><a href="{{ route('admin.cms-pages.index') }}"
                                    class="submenu-link {{ request()->routeIs('admin.cms-pages.index') ? 'active' : '' }}">All
                                    Pages</a></li>
                            <li><a href="{{ route('admin.cms-pages.edit', 'terms_condition') }}"
                                    class="submenu-link {{ request()->is('*/cms-pages/terms_condition*') ? 'active' : '' }}">Terms
                                    & Conditions</a></li>
                            <li><a href="{{ route('admin.cms-pages.edit', 'privacy_policy') }}"
                                    class="submenu-link {{ request()->is('*/cms-pages/privacy_policy*') ? 'active' : '' }}">Privacy
                                    Policy</a></li>
                            <li><a href="{{ route('admin.cms-pages.edit', 'about_us') }}"
                                    class="submenu-link {{ request()->is('*/cms-pages/about_us*') ? 'active' : '' }}">About
                                    Us</a></li>
                            <li><a href="{{ route('admin.cms-pages.edit', 'e_signature') }}"
                                    class="submenu-link {{ request()->is('*/cms-pages/e_signature*') ? 'active' : '' }}">Consent Form</a></li>
                        </ul>
                    </div>

                    <div class="nav-item">
                        <a href="{{ route('admin.reports.index') }}"
                            class="nav-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
                            <i class="fas fa-chart-bar"></i>
                            <span>Reports</span>
                        </a>
                    </div>
                    
                    <div class="nav-item has-submenu {{ request()->routeIs(['admin.admins.*', 'admin.roles.*', 'admin.weekly-schedules.*', 'admin.tax.*']) ? 'active' : '' }}">
                        <a href="javascript:void(0);" class="nav-link">
                            <i class="fas fa-cog"></i>
                            <span>Business Settings</span>
                        </a>
                        <ul class="submenu">
                            <li>
                                <a href="{{ route('admin.admins.index') }}"
                                    class="submenu-link {{ request()->routeIs('admin.admins.*') ? 'active' : '' }}">
                                    <i class="fas fa-user-shield"></i>
                                    <span>Admins</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.roles.index') }}"
                                    class="submenu-link {{ request()->routeIs('admin.roles.*') ? 'active' : '' }}">
                                    <i class="fas fa-user-tag"></i>
                                    <span>Roles</span>
                                </a>
                            </li>

                            <li>
                                <a href="{{ route('admin.weekly-schedules.index') }}"
                                    class="submenu-link {{ request()->routeIs('admin.weekly-schedules.*') ? 'active' : '' }}">
                                    <i class="fas fa-clock"></i>
                                    <span>Business Hours</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.tax.index') }}"
                                    class="submenu-link {{ request()->routeIs('admin.tax.*') ? 'active' : '' }}">
                                    <i class="fas fa-percent"></i>
                                    <span>Tax & Fees</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                @else
                    <div class="nav-item">
                        <a href="{{ route('admin.dashboard') }}"
                            class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            <i class="fas fa-home"></i>
                            <span>Dashboard</span>
                        </a>
                    </div>

                    <div class="nav-item has-submenu {{ request()->routeIs('admin.bookings.*') ? 'active' : '' }}">
                        <a href="javascript:void(0);" class="nav-link">
                            <i class="fas fa-calendar-check"></i>
                            <span>Booking</span>
                        </a>
                        <ul class="submenu">
                            <li>
                                <a href="{{ route('admin.bookings.index') }}"
                                    class="submenu-link {{ request()->routeIs('admin.bookings.*') && !request()->routeIs('admin.bookings.schedule') ? 'active' : '' }}">
                                    <i class="fas fa-list"></i>
                                    <span>All Bookings</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.bookings.schedule') }}"
                                    class="submenu-link {{ request()->routeIs('admin.bookings.schedule') ? 'active' : '' }}">
                                    <i class="fas fa-calendar-alt"></i>
                                    <span>Schedule</span>
                                </a>
                            </li>
                        </ul>
                    </div>

                @endif

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
                    <a href="{{ route('admin.notifications.index') }}" class="header-bell"
                        title="Notifications" aria-label="Notifications">
                        <i class="fas fa-bell"></i>
                        @if(($unreadAdminNotifications ?? 0) > 0)
                            <span class="header-bell-count">
                                {{ $unreadAdminNotifications > 99 ? '99+' : $unreadAdminNotifications }}
                            </span>
                        @endif
                    </a>

                    <div class="dropdown">
                        <div class="header-user" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false"
                            style="cursor: pointer;">
                            <div class="user-avatar">
                                {{ strtoupper(substr(auth()->guard('admin')->user()->name ?? 'A', 0, 1)) }}
                            </div>
                            <div class="user-info">
                                <div class="user-name">{{ auth()->guard('admin')->user()->name ?? 'Admin' }}</div>
                                <div class="user-role">
                                    {{ auth()->guard('admin')->user()->role->name ?? 'Administrator' }}
                                </div>
                            </div>
                            <i class="fas fa-chevron-down ms-2"></i>
                        </div>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li><a class="dropdown-item" href="{{ route('admin.profile.edit') }}"><i
                                        class="fas fa-user-edit me-2"></i>Edit Profile</a></li>
                            <li><a class="dropdown-item" href="{{ route('admin.profile.password') }}"><i
                                        class="fas fa-key me-2"></i>Change Password</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <form action="{{ route('admin.logout') }}" method="POST">
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

    <!-- Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <!-- Pusher JS -->
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>

    <script>
        $(document).ready(function () {

            // Configure Toastr Settings
            toastr.options = {
                "closeButton": true,
                "progressBar": true,
                "positionClass": "toast-top-right",
                "timeOut": "5000",
            };

            // Enable Pusher logging
            Pusher.logToConsole = true;

            // Initialize Global Pusher Connection for Admin
            window.globalPusher = new Pusher("{{ env('PUSHER_APP_KEY', '4f918ad3ae17f81ec65b') }}", {
                cluster: "{{ env('PUSHER_APP_CLUSTER', 'ap2') }}",
                authEndpoint: "{{ url('broadcasting/auth') }}",
                auth: {
                    headers: {
                        'X-CSRF-Token': "{{ csrf_token() }}"
                    }
                }
            });

            // Subscribe to the global admin notifications channel
            var adminNotificationsChannel = window.globalPusher.subscribe('private-admin.notifications');

            // Catch any event for debugging
            window.globalPusher.bind_global(function (eventName, data) {
                console.log('DEBUG PUSHER EVENT [', eventName, ']', data);
            });

            // Listen for newly broadcasted chat messages
            adminNotificationsChannel.bind('message.sent', function (data) {

                // Increment the chat badge on the bookings index page
                var bookingId = data.chat.booking_id;
                var badge = $('.chat-' + bookingId);

                if (badge.length) {
                    // Badge exists – increment the count
                    var current = parseInt(badge.text()) || 0;
                    badge.text(current + 1);
                } else {
                    // No badge yet (chats_count was 0) – create one
                    var chatBtn = $('a[href*="/' + bookingId + '"].chat-btn');
                    if (chatBtn.length) {
                        chatBtn.append('<span class="chat-badge chat-' + bookingId + '">1</span>');
                    }
                }
                console.log('New chat message received:', data);
                // Toastr notification
                if (data.chat.is_admin != true) {
                    toastr.info('You have received a new chat message', 'New Message', {
                        onclick: function () {
                            window.location.href = "{{ route('admin.bookings.chat', ':id') }}".replace(':id', bookingId);
                        }
                    });
                }
            });
        });
    </script>

    <script>
        $(document).ready(function () {

            $('.has-submenu > .nav-link').on('click', function (e) {
                e.preventDefault();

                let parent = $(this).closest('.has-submenu');
                let submenu = parent.children('.submenu');

                // close others smoothly
                $('.has-submenu').not(parent)
                    .removeClass('active')
                    .children('.submenu')
                    .stop(true, true)
                    .slideUp(300, 'swing');

                // toggle current
                if (submenu.is(':visible')) {
                    parent.removeClass('active');
                    submenu.stop(true, true).slideUp(300, 'swing');
                } else {
                    parent.addClass('active');
                    submenu.stop(true, true).slideDown(300, 'swing');
                }
            });

            $('.has-submenu.active > .submenu').show();
        });
    </script>

    <script>
        $('.phone').on('input', function (e) {
            let value = this.value.replace(/\D/g, '').substring(0, 10);

            let formatted = '';

            if (value.length > 0) {
                formatted = '(' + value.substring(0, 3);
            }
            if (value.length >= 4) {
                formatted += ') ' + value.substring(3, 6);
            }
            if (value.length >= 7) {
                formatted += '-' + value.substring(6, 10);
            }

            this.value = formatted;
        });
    </script>

    @stack('scripts')
</body>

</html>