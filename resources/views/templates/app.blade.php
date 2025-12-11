<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>menit.com</title>
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">
    <!-- Bootstrap 5 -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet" />
    <!-- Custom CSS -->
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    {{-- ChartJS --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        /* Alert styling */
        .alert {
            font-weight: normal !important;
        }

        :root {
            /* Updated color scheme from dark to light with blue accent */
            --primary-color: #ffffff;
            --secondary-color: #f8f9fa;
            --accent-color: #0da2e7;
            --text-light: #1f2937;
            --text-muted: #6b7280;
            --border-color: #e5e7eb;
        }

        * {
            font-family: 'Roboto', sans-serif;
        }

        body {
            background-color: #f8f9fa;
        }

        /* Navbar styling - light background with blue accent */
        .navbar {
            background: var(--primary-color);
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.08);
            padding: 0.75rem 0;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1030;
        }

        /* Add padding to body to account for fixed navbar */
        body {
            padding-top: 70px;
        }

        .navbar-brand {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--accent-color) !important;
            letter-spacing: 1px;
            transition: all 0.3s ease;
        }

        .navbar-brand:hover {
            color: #0da2e7 !important;
            transform: scale(1.05);
        }

        .navbar-brand img {
            height: 30px;
        }

        /* Navigation links styling - dark text without hover effect */
        .navbar-nav .nav-link {
            color: var(--text-light) !important;
            font-weight: 500;
            margin: 0 0.5rem;
            padding: 0.5rem 1rem !important;
            border-radius: 5px;
            position: relative;
        }

        /* Active link styling */
        .navbar-nav .nav-link.active {
            color: var(--accent-color) !important;
            font-weight: 600;
        }

        /* Search bar styling - light background */
        .search-container {
            position: relative;
            margin: 0 1rem;
        }

        .search-form {
            margin: 0;
        }

        .search-input {
            background-color: var(--secondary-color);
            border: 1px solid var(--border-color);
            color: var(--text-light);
            padding: 0.5rem 2.5rem 0.5rem 1rem;
            border-radius: 25px;
            width: 250px;
            font-size: 0.9rem;
        }

        .search-input::placeholder {
            color: var(--text-muted);
        }

        .search-input:focus {
            outline: none;
            border-color: var(--accent-color);
        }

        .search-btn {
            background: none;
            border: none;
            padding: 0;
            cursor: pointer;
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
        }

        .search-icon {
            color: var(--text-muted);
            transition: color 0.3s ease;
        }

        .search-input:focus~.search-btn .search-icon {
            color: var(--accent-color);
        }

        .search-btn:hover .search-icon {
            color: var(--accent-color);
        }

        /* Dropdown menu styling - light background */
        .dropdown-menu {
            background-color: #ffffff;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            animation: slideDown 0.3s ease;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .dropdown-item {
            color: var(--text-light);
            padding: 0.75rem 1.5rem;
            border-left: 3px solid transparent;
        }

        .dropdown-item:active {
            background-color: rgba(30, 64, 175, 0.08);
            color: var(--accent-color);
        }

        /* Avatar styling - blue border */
        .avatar-container {
            position: relative;
        }

        .avatar-img {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            border: 2px solid var(--accent-color);
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .avatar-img:hover {
            transform: scale(1.1);
            box-shadow: 0 0 15px rgba(30, 64, 175, 0.3);
        }

        /* Hamburger menu styling */
        .navbar-toggler {
            border: none;
            color: var(--text-light);
            font-size: 1.25rem;
            transition: all 0.3s ease;
        }

        .navbar-toggler:focus {
            box-shadow: none;
            outline: 2px solid var(--accent-color);
            outline-offset: 2px;
        }

        .navbar-toggler:hover {
            color: var(--accent-color);
        }

        /* Responsive adjustments */
        @media (max-width: 991px) {
            .search-input {
                width: 100%;
                margin: 0.5rem 0;
            }

            .navbar-nav {
                margin-top: 1rem;
            }

            .navbar-nav .nav-link {
                padding: 0.75rem 0 !important;
                border-radius: 0;
            }

            .navbar-nav .nav-link::after {
                display: none;
            }

            .navbar-nav .nav-link:hover::after {
                display: none;
            }

            .d-flex {
                flex-direction: column;
            }

            .search-container {
                margin: 0.5rem 0;
            }
        }

        /* Right elements container */
        .navbar-right-elements {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        @media (max-width: 991px) {
            .navbar-right-elements {
                flex-direction: column;
                align-items: flex-start;
                width: 100%;
                margin-top: 1rem;
                padding-top: 1rem;
                border-top: 1px solid var(--border-color);
            }

            .search-container {
                width: 100%;
            }
        }

        /* Custom Alert Buttons from Admin Index */
        .btn-alert-success {
            background-color: #d1e7dd;
            color: #0f5132;
            border: 1px solid #badbcc;
            text-decoration: none;
            padding: 0.375rem 0.75rem;
            display: inline-flex;
            align-items: center;
            border-radius: 0.25rem;
            transition: all 0.3s ease;
        }

        .btn-alert-success:hover {
            background-color: #c2e0d8;
            color: #0f5132;
        }

        .btn-alert-warning {
            background-color: #fff3cd;
            color: #664d03;
            border: 1px solid #ffecb5;
            text-decoration: none;
            padding: 0.375rem 0.75rem;
            display: inline-flex;
            align-items: center;
            border-radius: 0.25rem;
            transition: all 0.3s ease;
        }

        .btn-alert-warning:hover {
            background-color: #ffe69c;
            color: #664d03;
        }

        .btn-alert-primary {
            background-color: #cfe2ff;
            color: #084298;
            border: 1px solid #b6d4fe;
            text-decoration: none;
            padding: 0.375rem 0.75rem;
            display: inline-flex;
            align-items: center;
            border-radius: 0.25rem;
            transition: all 0.3s ease;
        }

        .btn-alert-primary:hover {
            background-color: #bacff7;
            color: #084298;
        }

        .btn-alert-danger {
            background-color: #f8d7da;
            color: #842029;
            border: 1px solid #f5c2c7;
            text-decoration: none;
            padding: 0.375rem 0.75rem;
            display: inline-flex;
            align-items: center;
            border-radius: 0.25rem;
            transition: all 0.3s ease;
        }

        .btn-alert-danger:hover {
            background-color: #f5c2c7;
            color: #842029;
        }

        .btn-sm.btn-alert-primary,
        .btn-sm.btn-alert-danger {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg">
        <!-- Container wrapper -->
        <div class="container-fluid">
            <!-- Navbar brand -->
            @if (!Auth::check() || (Auth::check() && Auth::user()->role !== 'admin'))
                <a class="navbar-brand" href="{{ route('home') }}">
                    <i class="fas fa-newspaper"></i> menit.com
                </a>
            @endif

            <!-- Toggle button -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <i class="fas fa-bars"></i>
            </button>

            <!-- Collapsible wrapper -->
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <!-- Left links -->
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    @auth
                        @if (Auth::user()->role === 'admin')
                            <li class="nav-item">
                                <a class="nav-link {{ request()->is('admin/dashboard*') ? 'active' : '' }}"
                                    href="{{ route('admin.dashboard') }}" style="border-bottom: 2px solid transparent;">
                                    <i class="fas fa-tachometer-alt me-1"></i> Dashboard
                                </a>
                            </li>
                            <!-- Admin Navigation - Kategori Dropdown -->
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle {{ request()->is('admin/categories*') || request()->is('admin/subcategories*') ? 'active' : '' }}"
                                    href="#" id="kategoriDropdown" role="button" data-bs-toggle="dropdown"
                                    aria-expanded="false">
                                    <i class="fas fa-database me-1"></i> Data Master
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="kategoriDropdown">
                                    <li>
                                        <a class="dropdown-item {{ request()->is('admin/categories*') ? 'active' : '' }}"
                                            href="{{ route('admin.categories.index') }}">
                                            <i class="fas fa-layer-group me-2"></i> Kelola Kategori
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item {{ request()->is('admin/subcategories*') ? 'active' : '' }}"
                                            href="{{ route('admin.subcategories.index') }}">
                                            <i class="fas fa-sitemap me-2"></i> Kelola Sub Kategori
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->is('admin/posts*') ? 'active' : '' }}"
                                    href="{{ route('admin.posts.index') }}">
                                    <i class="fas fa-newspaper me-1"></i> Kelola Artikel
                                </a>
                            </li>
                            <!-- Admin Navigation - Menu Terpisah -->
                            <li class="nav-item">
                                <a class="nav-link {{ request()->is('admin/comments*') ? 'active' : '' }}"
                                    href="{{ route('admin.comments.index') }}">
                                    <i class="fas fa-comments me-1"></i> Kelola Comment
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->is('admin/tags*') ? 'active' : '' }}"
                                    href="{{ route('admin.tags.index') }}">
                                    <i class="fas fa-tags me-1"></i> Kelola Tag
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->is('admin/users*') ? 'active' : '' }}"
                                    href="{{ route('admin.users.index') }}">
                                    <i class="fas fa-users me-1"></i> Kelola User
                                </a>
                            </li>
                        @else
                            <!-- Regular User Navigation -->
                            <li class="nav-item">
                                <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="{{ route('home') }}">
                                    <i class="fas fa-home me-1"></i> Beranda
                                </a>
                            </li>
                            @foreach (\App\Models\Categorie::orderBy('name')->get() as $navCategory)
                                <li class="nav-item">
                                    <a class="nav-link {{ isset($category) && $category->id === $navCategory->id ? 'active' : '' }}"
                                        href="{{ route('category.posts', $navCategory->slug) }}">
                                        {{ $navCategory->name }}
                                    </a>
                                </li>
                            @endforeach
                        @endif
                    @else
                        <!-- Guest Navigation -->
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="{{ route('home') }}">
                                <i class="fas fa-home me-1"></i> Beranda
                            </a>
                        </li>
                        @foreach (\App\Models\Categorie::orderBy('name')->get() as $navCategory)
                            <li class="nav-item">
                                <a class="nav-link {{ isset($category) && $category->id === $navCategory->id ? 'active' : '' }}"
                                    href="{{ route('category.posts', $navCategory->slug) }}">
                                    {{ $navCategory->name }}
                                </a>
                            </li>
                        @endforeach
                    @endauth
                </ul>

                <!-- Right elements -->
                <div class="navbar-right-elements">
                    <!-- Search Form - Hide for admin -->
                    @if (!Auth::check() || (Auth::check() && Auth::user()->role !== 'admin'))
                        <form class="search-form" method="GET" action="{{ route('posts.search') }}">
                            @csrf
                            <div class="search-container">
                                <input type="text" name="search_post" class="search-input"
                                    placeholder="Cari berita..." aria-label="Search" aria-describedby="search-addon"
                                    value="{{ request('search_post') }}" />
                                <button type="submit" class="search-btn">
                                    <i class="fas fa-search search-icon"></i>
                                </button>
                            </div>
                        </form>
                    @endif

                    <!-- Avatar Dropdown - Hide for admin -->
                    @if (!Auth::check() || (Auth::check() && Auth::user()->role !== 'admin'))
                        <div class="dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#"
                                id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                @if (Auth::check())
                                    <span class="badge bg-primary rounded-circle"
                                        style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; font-size: 0.9rem; font-weight: 700;"
                                        title="{{ Auth::user()->name }}">
                                        {{ Auth::user()->getInitials() }}
                                    </span>
                                @else
                                    <span class="rounded-circle d-flex align-items-center justify-content-center"
                                        style="width: 40px; height: 40px; background-color: #e9ecef; color: #6c757d;">
                                        <i class="fas fa-user"></i>
                                    </span>
                                @endif
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                @if (Auth::check())
                                    <!-- User Menu -->
                                    <li><a class="dropdown-item" href="{{ route('user.posts.create') }}"><i
                                                class="fas fa-plus-circle me-2"></i>Tambah Artikel</a></li>
                                    <li><a class="dropdown-item" href="{{ route('user.posts.drafts') }}"><i
                                                class="fas fa-file-alt me-2"></i>Draft Artikel</a></li>
                                    <li><a class="dropdown-item" href="{{ route('user.posts.my-articles') }}"><i
                                                class="fas fa-file me-2"></i>Artikel Saya</a></li>
                                    <li><a class="dropdown-item" href="{{ route('user.posts.my-comments') }}"><i
                                                class="fas fa-comment me-2"></i>Komentar Saya</a></li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('logout') }}">
                                            <i class="fas fa-sign-out-alt me-2"></i>Logout
                                        </a>
                                    </li>
                                @else
                                    <li><a class="dropdown-item" href="{{ route('login') }}"><i
                                                class="fas fa-sign-in-alt me-2"></i>Login</a></li>
                                    <li><a class="dropdown-item" href="{{ route('signup') }}"><i
                                                class="fas fa-user-plus me-2"></i>Daftar</a></li>
                                @endif
                            </ul>
                        </div>
                    @else
                        <!-- Admin Logout Button -->
                        <a href="{{ route('logout') }}" class="nav-link">
                            <i class="fas fa-sign-out-alt me-1"></i> Logout
                        </a>
                    @endif
                </div>
                <!-- Right elements -->
            </div>
            <!-- Collapsible wrapper -->
        </div>
        <!-- Container wrapper -->
    </nav>
    @yield('content')

    @stack('script')

    <!-- Bootstrap JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>

</html>
