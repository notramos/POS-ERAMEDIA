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
        }

        .search-box {
            display: flex;
            align-items: center;
            background: rgba(102, 126, 234, 0.1);
            padding: 0.5rem 1rem;
            border-radius: 25px;
            border: 1px solid rgba(102, 126, 234, 0.2);
            transition: all 0.3s ease;
        }

        .search-box:focus-within {
            box-shadow: 0 0 20px rgba(102, 126, 234, 0.3);
            transform: translateY(-2px);
        }

        .search-box input {
            border: none;
            outline: none;
            background: transparent;
            padding: 0.3rem;
            width: 200px;
            color: #333;
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

        /* Recent Activity */
        .activity-section {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 1.5rem;
            border-radius: 15px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .section-title {
            font-size: 1.3rem;
            margin-bottom: 1rem;
            color: #333;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .activity-list {
            list-style: none;
        }

        .activity-item {
            display: flex;
            align-items: center;
            padding: 1rem 0;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }

        .activity-item:hover {
            background: rgba(102, 126, 234, 0.05);
            padding-left: 1rem;
            border-radius: 8px;
        }

        .activity-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
            color: white;
        }

        .activity-content {
            flex: 1;
        }

        .activity-title {
            font-weight: 600;
            color: #333;
            margin-bottom: 0.2rem;
        }

        .activity-time {
            font-size: 0.8rem;
            color: #666;
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

            .navbar-right .search-box {
                display: none;
            }
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .stat-card,
        .activity-section {
            animation: fadeInUp 0.6s ease forwards;
        }

        .stat-card:nth-child(2) {
            animation-delay: 0.1s;
        }

        .stat-card:nth-child(3) {
            animation-delay: 0.2s;
        }

        .stat-card:nth-child(4) {
            animation-delay: 0.3s;
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
                <div class="search-box">
                    <i class="fas fa-search" style="color: #667eea; margin-right: 0.5rem;"></i>
                    <input type="text" placeholder="Cari produk, transaksi...">
                </div>
                <div class="user-profile">
                    <div class="user-avatar">A</div>
                    <span>Admin</span>
                    <i class="fas fa-chevron-down" style="margin-left: 0.5rem; color: #667eea;"></i>
                </div>
            </div>
        </div>
    </nav>

    <!-- Sidebar -->
    <aside class="sidebar">
        <ul class="sidebar-menu">
            <li><a href="/dashboard" class="active"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
            <li><a href="/kasir"><i class="fas fa-cash-register"></i> Kasir</a></li>
            <li><a href="/products"><i class="fas fa-box"></i> Produk</a></li>
            <li><a href="/customers"><i class="fas fa-users"></i> Pelanggan</a></li>
            <li><a href="/transactions"><i class="fas fa-receipt"></i> Transaksi</a></li>
            <li><a href="/laporan"><i class="fas fa-chart-bar"></i> Laporan</a></li>
            <li><a href="/inventory"><i class="fas fa-warehouse"></i> Inventory</a></li>
            <li><a href="/units"><i class="fas fa-tags"></i> Unit</a></li>
        </ul>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <div class="dashboard-header">
            <h1 class="dashboard-title">Selamat Datang di ERAMEDIA POS</h1>
            <p class="dashboard-subtitle">Kelola toko Anda dengan mudah dan efisien</p>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-header">
                    <span class="stat-title">Penjualan Hari Ini</span>
                    <div class="stat-icon" style="background: linear-gradient(45deg, #28a745, #20c997);">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                </div>
                <div class="stat-value">Rp 2,450,000</div>
                <div class="stat-change">
                    <i class="fas fa-arrow-up"></i> +12.5% dari kemarin
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <span class="stat-title">Transaksi</span>
                    <div class="stat-icon" style="background: linear-gradient(45deg, #007bff, #6610f2);">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                </div>
                <div class="stat-value">127</div>
                <div class="stat-change">
                    <i class="fas fa-arrow-up"></i> +8.3% dari kemarin
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <span class="stat-title">Produk Terjual</span>
                    <div class="stat-icon" style="background: linear-gradient(45deg, #fd7e14, #e83e8c);">
                        <i class="fas fa-box-open"></i>
                    </div>
                </div>
                <div class="stat-value">456</div>
                <div class="stat-change">
                    <i class="fas fa-arrow-up"></i> +15.2% dari kemarin
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <span class="stat-title">Stok Menipis</span>
                    <div class="stat-icon" style="background: linear-gradient(45deg, #dc3545, #fd7e14);">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                </div>
                <div class="stat-value">23</div>
                <div class="stat-change" style="color: #dc3545;">
                    <i class="fas fa-arrow-down"></i> Perlu restok
                </div>
            </div>
        </div>

        <div class="activity-section">
            <h2 class="section-title">
                <i class="fas fa-clock"></i>
                Aktivitas Terbaru
            </h2>
            <ul class="activity-list">
                <li class="activity-item">
                    <div class="activity-icon" style="background: linear-gradient(45deg, #28a745, #20c997);">
                        <i class="fas fa-plus"></i>
                    </div>
                    <div class="activity-content">
                        <div class="activity-title">Produk baru ditambahkan: Laptop ASUS ROG</div>
                        <div class="activity-time">2 menit yang lalu</div>
                    </div>
                </li>
                <li class="activity-item">
                    <div class="activity-icon" style="background: linear-gradient(45deg, #007bff, #6610f2);">
                        <i class="fas fa-shopping-bag"></i>
                    </div>
                    <div class="activity-content">
                        <div class="activity-title">Transaksi #INV-2024-001 berhasil - Rp 350,000</div>
                        <div class="activity-time">5 menit yang lalu</div>
                    </div>
                </li>
                <li class="activity-item">
                    <div class="activity-icon" style="background: linear-gradient(45deg, #fd7e14, #e83e8c);">
                        <i class="fas fa-user-plus"></i>
                    </div>
                    <div class="activity-content">
                        <div class="activity-title">Pelanggan baru terdaftar: Ahmad Wijaya</div>
                        <div class="activity-time">15 menit yang lalu</div>
                    </div>
                </li>
                <li class="activity-item">
                    <div class="activity-icon" style="background: linear-gradient(45deg, #dc3545, #fd7e14);">
                        <i class="fas fa-exclamation"></i>
                    </div>
                    <div class="activity-content">
                        <div class="activity-title">Stok Mouse Wireless hampir habis (5 tersisa)</div>
                        <div class="activity-time">30 menit yang lalu</div>
                    </div>
                </li>
            </ul>
        </div>
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-content">
            <div>© 2024 ERAMEDIA POS System. All rights reserved.</div>
            <div>
                <span>Version 2.1.0</span> |
                <span>Online</span> |
                <span>Server: Jakarta</span>
            </div>
        </div>
    </footer>

    <script>
        // Add some interactive functionality
        document.addEventListener('DOMContentLoaded', function() {
            // Sidebar menu interaction - only for visual feedback, allow navigation
            const menuItems = document.querySelectorAll('.sidebar-menu a');
            menuItems.forEach(item => {
                item.addEventListener('click', function(e) {
                    // Remove preventDefault to allow navigation
                    // Add loading effect

                    // You can add additional logic here before navigation
                    // For example: save state, show loading, etc.
                });
            });

            // Search functionality
            const searchInput = document.querySelector('.search-box input');
            searchInput.addEventListener('focus', function() {
                this.placeholder = 'Ketik untuk mencari...';
            });

            searchInput.addEventListener('blur', function() {
                this.placeholder = 'Cari produk, transaksi...';
            });

            // User profile dropdown simulation
            const userProfile = document.querySelector('.user-profile');
            userProfile.addEventListener('click', function() {
                this.style.transform = 'translateY(-2px) scale(0.95)';
                setTimeout(() => {
                    this.style.transform = 'translateY(-2px)';
                }, 150);
            });

            // Animate numbers on load
            const statValues = document.querySelectorAll('.stat-value');
            statValues.forEach(stat => {
                const finalValue = stat.textContent;
                if (finalValue.includes('Rp')) {
                    animateNumber(stat, 0, 2450000, 'currency');
                } else {
                    const numValue = parseInt(finalValue.replace(/[^0-9]/g, ''));
                    animateNumber(stat, 0, numValue, 'number');
                }
            });

            function animateNumber(element, start, end, type) {
                const duration = 2000;
                const increment = end / (duration / 16);
                let current = start;

                const timer = setInterval(() => {
                    current += increment;
                    if (current >= end) {
                        current = end;
                        clearInterval(timer);
                    }

                    if (type === 'currency') {
                        element.textContent = 'Rp ' + Math.floor(current).toLocaleString('id-ID');
                    } else {
                        element.textContent = Math.floor(current).toString();
                    }
                }, 16);
            }

            // Add hover effects to activity items
            const activityItems = document.querySelectorAll('.activity-item');
            activityItems.forEach(item => {
                item.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateX(5px)';
                });

                item.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateX(0)';
                });
            });
        });
    </script>
</body>

</html>
