<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ERAMEDIA - Dashboard POS</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* Navbar */
        .navbar {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 1rem 2rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        }

        .navbar-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-size: 1.8rem;
            font-weight: bold;
            background: linear-gradient(45deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .navbar-right {
            display: flex;
            align-items: center;
            gap: 1.5rem;
            position: relative;
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            background: rgba(102, 126, 234, 0.1);
            border-radius: 25px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .user-profile:hover {
            background: rgba(102, 126, 234, 0.2);
            transform: translateY(-2px);
        }

        .user-avatar {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            background: linear-gradient(45deg, #667eea, #764ba2);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
        }

        /* Dropdown Menu */
        .dropdown-menu {
            position: absolute;
            top: 100%;
            right: 0;
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            min-width: 200px;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.3s ease;
            z-index: 1001;
            margin-top: 0.5rem;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .dropdown-menu.show {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .dropdown-item {
            display: flex;
            align-items: center;
            padding: 12px 16px;
            color: #333;
            text-decoration: none;
            transition: all 0.2s ease;
            gap: 12px;
            border-radius: 8px;
            margin: 4px;
        }

        .dropdown-item:hover {
            background: linear-gradient(90deg, rgba(102, 126, 234, 0.1), transparent);
            color: #667eea;
            transform: translateX(5px);
        }

        .dropdown-item i {
            width: 16px;
            color: #667eea;
        }

        .dropdown-divider {
            height: 1px;
            background-color: #e9ecef;
            margin: 8px 12px;
        }

        .chevron {
            transition: transform 0.3s ease;
            margin-left: 0.5rem;
            color: #667eea;
        }

        .chevron.rotate {
            transform: rotate(180deg);
        }

        /* Sidebar */
        .sidebar {
            position: fixed;
            left: 0;
            top: 80px;
            width: 280px;
            height: calc(100vh - 80px - 60px);
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: 4px 0 20px rgba(0, 0, 0, 0.1);
            padding: 2rem 0;
            z-index: 999;
            border-right: 1px solid rgba(255, 255, 255, 0.2);
        }

        .sidebar-menu {
            list-style: none;
        }

        .sidebar-menu li {
            margin: 0.5rem 0;
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            padding: 1rem 2rem;
            text-decoration: none;
            color: #555;
            transition: all 0.3s ease;
            position: relative;
        }

        .sidebar-menu a:hover,
        .sidebar-menu a.active {
            background: linear-gradient(90deg, rgba(102, 126, 234, 0.1), transparent);
            color: #667eea;
            transform: translateX(10px);
        }

        .sidebar-menu a::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 4px;
            height: 0;
            background: linear-gradient(45deg, #667eea, #764ba2);
            border-radius: 2px;
            transition: height 0.3s ease;
        }

        .sidebar-menu a:hover::before,
        .sidebar-menu a.active::before {
            height: 100%;
        }

        .sidebar-menu i {
            width: 20px;
            margin-right: 1rem;
            font-size: 1.1rem;
        }

        /* Main Content */
        .main-content {
            margin-left: 280px;
            margin-top: 80px;
            margin-bottom: 60px;
            padding: 2rem;
            min-height: calc(100vh - 140px);
        }

        .dashboard-header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 2rem;
            border-radius: 20px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            margin-bottom: 2rem;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .dashboard-title {
            font-size: 2rem;
            margin-bottom: 0.5rem;
            background: linear-gradient(45deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .dashboard-subtitle {
            color: #666;
            font-size: 1.1rem;
        }

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 1.5rem;
            border-radius: 15px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #667eea, #764ba2);
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
        }

        .stat-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .stat-title {
            color: #666;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .stat-icon {
            width: 45px;
            height: 45px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.2rem;
        }

        .stat-value {
            font-size: 2rem;
            font-weight: bold;
            color: #333;
            margin-bottom: 0.5rem;
        }

        .stat-change {
            font-size: 0.8rem;
            color: #28a745;
        }

        /* Footer */
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 1rem 2rem;
            box-shadow: 0 -4px 20px rgba(0, 0, 0, 0.1);
            border-top: 1px solid rgba(255, 255, 255, 0.2);
            z-index: 1000;
        }

        .footer-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.9rem;
            color: #666;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }

            .main-content {
                margin-left: 0;
            }
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="navbar-content">
            <div class="logo">
                <i class="fas fa-store"></i> ERAMEDIA
            </div>
            <div class="navbar-right">
                <div class="user-profile" id="userProfile">
                    <div class="user-avatar">A</div>
                    <span>{{ Auth::user()->role->name }}</span>
                    <i class="fas fa-chevron-down chevron" id="chevronIcon"></i>
                </div>
                <div class="dropdown-menu" id="dropdownMenu">
                    <a href="#" class="dropdown-item">
                        <i class="fas fa-user"></i>
                        Profile
                    </a>
                    <a href="#" class="dropdown-item">
                        <i class="fas fa-cog"></i>
                        Settings
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt"></i>
                        Logout
                    </a>

                    <form id="logout-form" action="{{ route('auth.logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <!-- Sidebar -->
    <aside class="sidebar">
        <ul class="sidebar-menu">
            @if (auth()->user()->role_id == 1)
                {{-- Menu untuk ADMIN --}}
                <li><a href="/dashboard" class="{{ request()->is('dashboard') ? 'active' : '' }}">
                        <i class="fas fa-tachometer-alt"></i> Dashboard
                    </a></li>
                <li><a href="/kasir" class="{{ request()->is('kasir') ? 'active' : '' }}">
                        <i class="fas fa-cash-register"></i> Kasir
                    </a></li>
                <li><a href="/products" class="{{ request()->is('products') ? 'active' : '' }}">
                        <i class="fas fa-box"></i> Produk
                    </a></li>
                <li><a href="/users" class="{{ request()->is('users') ? 'active' : '' }}">
                        <i class="fas fa-users"></i> Pengguna
                    </a></li>
                <li><a href="/units" class="{{ request()->is('units') ? 'active' : '' }}">
                        <i class="fas fa-receipt"></i> Unit
                    </a></li>
                <li><a href="/laporan" class="{{ request()->is('laporan') ? 'active' : '' }}">
                        <i class="fas fa-chart-bar"></i> Laporan Transaksi
                    </a></li>
            @elseif(auth()->user()->role_id == 2)
                {{-- Menu untuk KARYAWAN --}}
                <li><a href="/kasir" class="{{ request()->is('kasir') ? 'active' : '' }}">
                        <i class="fas fa-cash-register"></i> Kasir
                    </a></li>
                <li><a href="/laporan" class="{{ request()->is('laporan') ? 'active' : '' }}">
                        <i class="fas fa-chart-bar"></i> Laporan Transaksi
                    </a></li>
            @endif
        </ul>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <div class="dashboard-header">
            <h1 class="dashboard-title">Selamat Datang di ERAMEDIA POS</h1>
            <p class="dashboard-subtitle">Kelola toko Anda dengan mudah dan efisien</p>
        </div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="stats-grid grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Penjualan Hari Ini -->
                <div class="stat-card bg-white p-6 rounded-xl shadow-md">
                    <div class="stat-header flex justify-between items-center mb-4">
                        <span class="stat-title font-semibold text-gray-700">Penjualan Hari Ini</span>
                        <div class="stat-icon w-12 h-12 rounded-lg flex items-center justify-center text-white"
                            style="background: linear-gradient(45deg, #28a745, #20c997);">
                            <i class="fas fa-dollar-sign"></i>
                        </div>
                    </div>
                    <div class="stat-value text-2xl font-bold text-gray-900">
                        Rp {{ number_format($penjualanHariIni, 0, ',', '.') }}
                    </div>
                    <div class="stat-change text-green-600 text-sm mt-1">
                        <i class="fas fa-arrow-up"></i> +12.5% dari kemarin
                    </div>
                </div>

                <!-- Transaksi -->
                <div class="stat-card bg-white p-6 rounded-xl shadow-md">
                    <div class="stat-header flex justify-between items-center mb-4">
                        <span class="stat-title font-semibold text-gray-700">Transaksi</span>
                        <div class="stat-icon w-12 h-12 rounded-lg flex items-center justify-center text-white"
                            style="background: linear-gradient(45deg, #007bff, #6610f2);">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                    </div>
                    <div class="stat-value text-2xl font-bold text-gray-900">
                        {{ $transaksiHariIni }}
                    </div>
                    <div class="stat-change text-green-600 text-sm mt-1">
                        <i class="fas fa-arrow-up"></i> +8.3% dari kemarin
                    </div>
                </div>

                <!-- Produk Terjual -->
                <div class="stat-card bg-white p-6 rounded-xl shadow-md">
                    <div class="stat-header flex justify-between items-center mb-4">
                        <span class="stat-title font-semibold text-gray-700">Produk Terjual</span>
                        <div class="stat-icon w-12 h-12 rounded-lg flex items-center justify-center text-white"
                            style="background: linear-gradient(45deg, #fd7e14, #e83e8c);">
                            <i class="fas fa-box-open"></i>
                        </div>
                    </div>
                    <div class="stat-value text-2xl font-bold text-gray-900">
                        {{ $produkTerjual ?? 0 }}
                    </div>
                    <div class="stat-change text-green-600 text-sm mt-1">
                        <i class="fas fa-arrow-up"></i> +15.2% dari kemarin
                    </div>
                </div>

                <!-- Total Pelanggan -->
                <div class="stat-card bg-white p-6 rounded-xl shadow-md">
                    <div class="stat-header flex justify-between items-center mb-4">
                        <span class="stat-title font-semibold text-gray-700">Total Pelanggan</span>
                        <div class="stat-icon w-12 h-12 rounded-lg flex items-center justify-center text-white"
                            style="background: linear-gradient(45deg, #6f42c1, #e83e8c);">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                    <div class="stat-value text-2xl font-bold text-gray-900">
                        {{ $totalPelanggan }}
                    </div>
                    <div class="stat-change text-green-600 text-sm mt-1">
                        <i class="fas fa-arrow-up"></i> +5.2% dari bulan lalu
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-content">
            <div>© 2024 ERAMEDIA POS System. All rights reserved.</div>
            <div>
                <span>Version 2.1.0</span> |
                <span>Online</span>
            </div>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const userProfile = document.getElementById('userProfile');
            const dropdownMenu = document.getElementById('dropdownMenu');
            const chevronIcon = document.getElementById('chevronIcon');

            // Toggle dropdown saat diklik
            userProfile.addEventListener('click', function(e) {
                e.stopPropagation();
                dropdownMenu.classList.toggle('show');
                chevronIcon.classList.toggle('rotate');
            });

            // Tutup dropdown saat klik di luar
            document.addEventListener('click', function(e) {
                if (!userProfile.contains(e.target)) {
                    dropdownMenu.classList.remove('show');
                    chevronIcon.classList.remove('rotate');
                }
            });

            // Prevent dropdown dari menutup saat diklik item dropdown
            dropdownMenu.addEventListener('click', function(e) {
                e.stopPropagation();
            });
        });
    </script>
</body>

</html>
