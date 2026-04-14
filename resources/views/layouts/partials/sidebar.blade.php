<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('dashboard') }}">
        <div class="sidebar-brand-icon">
            <i class="fas fa-store"></i>
        </div>
        <div class="sidebar-brand-text mx-3">POS ERAMEDIA</div>
    </a>
    <hr class="sidebar-divider my-0">
    <li class="nav-item ">
        <a class="nav-link" href="{{ route('dashboard') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>
    <hr class="sidebar-divider">
    <div class="sidebar-heading">Kasir</div>

    @auth
        {{-- Kasir visible to both owner and karyawan --}}
        @if(in_array(optional(auth()->user()->role)->name, ['admin', 'karyawan']))
            <li class="nav-item">
                <a class="nav-link" href="{{ route('kasir.index') }}">
                    <i class="fas fa-fw fa-receipt"></i>
                    <span>Kasir</span>
                </a>
            </li>
        @endif

       

        {{-- Master data: owner only --}}
        @if(optional(auth()->user()->role)->name === 'admin')
         <hr class="sidebar-divider">
            <div class="sidebar-heading">Master Data</div>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('products.index') }}">
                    <i class="fas fa-fw fa-box"></i>
                    <span>Produk</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('users.index') }}">
                    <i class="fas fa-fw fa-users"></i>
                    <span>Pengguna</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('supplier.supplier')}}">
                    <i class="fas fa-fw fa-truck"></i>
                    <span>Supplier</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('unit.index') }}">
                    <i class="fas fa-fw fa-cog"></i>
                    <span>Kategori</span>
                </a>
            </li>
        @endif

        <hr class="sidebar-divider">
        <div class="sidebar-heading">Laporan</div>

        {{-- Penjualan visible to both owner and karyawan --}}
        @if(in_array(optional(auth()->user()->role)->name, ['admin', 'karyawan']))
            <li class="nav-item">
                <a class="nav-link" href="{{ route('transactions.list') }}">
                    <i class="fas fa-fw fa-receipt"></i>
                    <span>Penjualan</span>
                </a>
            </li>
        @endif

        {{-- Pembelian: owner only --}}
        @if(optional(auth()->user()->role)->name === 'admin')
            <li class="nav-item">
                <a class="nav-link" href="{{ route('purchase.purchase') }}">
                    <i class="fas fa-fw fa-shopping-cart"></i>
                    <span>Pembelian</span>
                </a>
            </li>
        @endif
    @endauth
    <hr class="sidebar-divider d-none d-md-block">
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>
</ul>