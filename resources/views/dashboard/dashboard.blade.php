@extends('layouts.home')

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
    </div>

    <!-- Filter Tanggal -->
    <div class="row mb-4">
            <div class="col-md-12">
        <form method="GET" class="row g-3 align-items-end">
                    <div class="col-sm-4 col-md-3">
                        <label for="start" class="form-label text-xs text-gray-600">Dari</label>
                        <input type="date" name="start" id="start"
                            value="{{ request('start') }}"
                            class="form-control form-control-sm">
                </div>

                    <div class="col-sm-4 col-md-3">
                        <label for="end" class="form-label text-xs text-gray-600">Sampai</label>
                        <input type="date" name="end" id="end"
                            value="{{ request('end') }}"
                            class="form-control form-control-sm">
                </div>

                    <div class="col-sm-4 col-md-2">
                        <button type="submit" class="btn btn-primary btn-sm w-100">
                            Terapkan
                </button>
            </div>

            @if(request()->has('start') || request()->has('end'))
                        <div class="col-sm-4 col-md-3">
                            <a href="{{ route('dashboard') }}"
                                class="btn btn-outline-secondary btn-sm w-100">
                                Reset Filter
                        </a>
                    </div>
                @endif

                            </form>
                        </div>
                    </div>
                    

    <!-- Cards -->
    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase">Total Penjualan</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">Rp {{ number_format($total_penjualan, 0, ',', '.') }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chart-line fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase">Total Pembelian</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">Rp {{ number_format($total_pembelian, 0, ',', '.') }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-file-invoice-dollar fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase">Total Supplier</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($total_supplier) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-handshake fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase">Produk Tersedia</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($produk_tersedia) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-boxes fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- DUA GRAFIK SAJA -->
    <div class="row">
         <!-- Grafik 1: Penjualan vs Pembelian -->
         <div class="col-xl-6 col-lg-12 mb-4">
             <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        Penjualan vs Pembelian
                @if(request('start') && request('end'))
                            <small>({{ \Carbon\Carbon::parse(request('start'))->format('d M Y') }} s/d {{ \Carbon\Carbon::parse(request('end'))->format('d M Y') }})</small>
                        @else
                            <small>(Semua Data)</small>
                @endif
                    </h6>
                </div>
                 <div class="card-body">
                     <div class="chart-area" style="height: 300px;">
                     <canvas id="salesPurchaseChart"></canvas>
                     </div>
                 </div>
             </div>
         </div>

        <!-- Grafik 2: Produk Terjual -->
        <div class="col-xl-6 col-lg-12 mb-4">
            <div class="card shadow">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-warning">Produk Terjual per Bulan</h6>
                <form method="GET" class="d-inline">
                        @if(request('start'))
                            <input type="hidden" name="start" value="{{ request('start') }}">
                        @endif
                        @if(request('end'))
                            <input type="hidden" name="end" value="{{ request('end') }}">
                        @endif
                        <select name="tahun_produk" class="form-control form-control-sm d-inline w-auto" onchange="this.form.submit()">
                        @foreach($tahun_list as $thn)
                                <option value="{{ $thn }}" {{ $thn == $tahun_dipilih ? 'selected' : '' }}>
                                    {{ $thn }}
                                </option>
                        @endforeach
                    </select>
                </form>
            </div>
                <div class="card-body">
                    <div class="chart-bar" style="height: 300px;">
                    <canvas id="productSoldChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Grafik 3: Top Products & Monthly Trend -->
    <div class="row">
        <!-- Top Products Bar Chart -->
        <div class="col-xl-6 col-lg-12 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-success">
                        <i class="fas fa-trophy me-1"></i> Top 10 Produk Terlaris
                    </h6>
                </div>
                <div class="card-body">
                    <div class="chart-bar" style="height: 300px;">
                        <canvas id="topProductsChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Monthly Sales Trend -->
        <div class="col-xl-6 col-lg-12 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-info">
                        <i class="fas fa-chart-line me-1"></i> Tren Penjualan
                        @if(request('trend_period') == 'week')
                            <small>(Minggu Ini)</small>
                        @elseif(request('trend_period') == 'month')
                            <small>(Bulan Ini)</small>
                        @elseif(request('trend_period') == 'year')
                            <small>(Tahun Ini)</small>
                        @else
                            <small>(2 Bulan Terakhir)</small>
                        @endif
                    </h6>
                </div>
                <div class="card-body">
                    <!-- Filter untuk Tren Penjualan -->
                    <div class="row mb-3">
                                 <div class="col-12">
                            <form method="GET" class="d-flex flex-wrap align-items-center gap-3">
                                @foreach(request()->except(['trend_period', 'trend_start', 'trend_end']) as $key => $value)
                                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                                @endforeach
                                
                                <div class="btn-group btn-group-sm" role="group">
                                    <button type="submit" name="trend_period" value="week" class="btn btn-outline-primary {{ request('trend_period') == 'week' ? 'active' : '' }}">Minggu Ini</button>
                                    <button type="submit" name="trend_period" value="month" class="btn btn-outline-primary {{ request('trend_period') == 'month' ? 'active' : '' }}">Bulan Ini</button>
                                    <button type="submit" name="trend_period" value="year" class="btn btn-outline-primary {{ request('trend_period') == 'year' ? 'active' : '' }}">Tahun Ini</button>
                                </div>
                                
                                {{-- <div class="d-flex align-items-center gap-2">
                                    <input type="date" name="trend_start" class="form-control form-control-sm" style="width: 130px;" value="{{ request('trend_start') }}">
                                    <input type="date" name="trend_end" class="form-control form-control-sm" style="width: 130px;" value="{{ request('trend_end') }}">
                                </div>
                                
                                <button type="submit" class="btn btn-primary btn-sm">
                                    <i class="fas fa-filter"></i> Terapkan
                                </button> --}}
                                
                                @if(request('trend_period') || request('trend_start') || request('trend_end'))
                                    <a href="?{{ http_build_query(request()->except(['trend_period', 'trend_start', 'trend_end'])) }}" class="btn btn-outline-danger btn-sm" title="Hapus filter">
                                        <i class="fas fa-times"></i>
                                    </a>
                                @endif
                            </form>
                        </div>
                    </div>

                    <div class="chart-area" style="height: 300px;">
                        <canvas id="monthlyTrendChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx1 = document.getElementById('salesPurchaseChart').getContext('2d');
    new Chart(ctx1, {
        type: 'line',
        data: {  // ✅ Tambahkan "data:"
            labels: @json($label_harian),
            datasets: [
                {
                    label: 'Penjualan (Rp)',
                    data: @json($penjualan_harian),  // ✅ tambahkan "data:"
                    borderColor: '#4e73df',
                    backgroundColor: 'rgba(78, 115, 223, 0.05)',
                    borderWidth: 2,
                fill: true,
                    tension: 0.3
                },
                {
                    label: 'Pembelian (Rp)',
                    data: @json($pembelian_harian),  // ✅ tambahkan "data:"
                    borderColor: '#e74a3b',
                    backgroundColor: 'rgba(231, 74, 59, 0.05)',
                    borderWidth: 2,
                fill: true,
                    tension: 0.3
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: true, position: 'top' }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + value.toLocaleString('id-ID');
                        }
                    }
                }
            }
        }
    });

    // Grafik 2: Produk Terjual per Bulan
    const ctx2 = document.getElementById('productSoldChart').getContext('2d');
    new Chart(ctx2, {
        type: 'bar',
        data: {  // ✅ Tambahkan "data:"
            labels: @json($bulan_produk),
            datasets: [{
                label: 'Jumlah Produk Terjual',
                data: @json($data_produk),  // ✅ tambahkan "data:"
                backgroundColor: 'rgba(246, 194, 60, 0.7)',
                borderColor: '#f6c23e',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#1e293b',
                    titleFont: { size: 13, weight: '600' },
                    bodyFont: { size: 12 },
                    padding: 12,
                    cornerRadius: 8
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { precision: 0 } 
                    }
                },
            plugins: { legend: { display: false } }
        }
    });

    // Grafik 3: Top 10 Products
    const ctx3 = document.getElementById('topProductsChart').getContext('2d');
    new Chart(ctx3, {
        type: 'bar',
        data: {
            labels: @json($products),
            datasets: [{
                label: 'Jumlah Terjual',
                data: @json($quantities),
                backgroundColor: [
                    'rgba(78, 115, 223, 0.8)',
                    'rgba(78, 115, 223, 0.7)',
                    'rgba(78, 115, 223, 0.6)',
                    'rgba(78, 115, 223, 0.5)',
                    'rgba(78, 115, 223, 0.4)',
                    'rgba(78, 115, 223, 0.3)',
                    'rgba(78, 115, 223, 0.25)',
                    'rgba(78, 115, 223, 0.2)',
                    'rgba(78, 115, 223, 0.15)',
                    'rgba(78, 115, 223, 0.1)'
                ],
                borderColor: '#4e73df',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            indexAxis: 'y',
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#1e293b',
                    titleFont: { size: 13, weight: '600' },
                    bodyFont: { size: 12 },
                    padding: 12,
                    cornerRadius: 8,
                    callbacks: {
                        label: function(context) {
                            const idx = context.dataIndex;
                            const revenue = @json($revenues);
                            return 'Terjual: ' + context.raw + ' | Pendapatan: Rp ' + parseInt(revenue[idx]).toLocaleString('id-ID');
                        }
                    }
                }
            },
            scales: {
                x: { beginAtZero: true, ticks: { precision: 0 } },
                y: { ticks: { font: { size: 11 } } }
            }
        }
    });

    // Grafik 4: Monthly Sales Trend
    const ctx4 = document.getElementById('monthlyTrendChart').getContext('2d');
    new Chart(ctx4, {
        type: 'line',
        data: {
            labels: @json($labels),
            datasets: [{
                label: 'Total Penjualan (Rp)',
                data: @json($data),
                borderColor: '#36a2eb',
                backgroundColor: 'rgba(54, 162, 235, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#36a2eb',
                pointRadius: 6,
                pointHoverRadius: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#1e293b',
                    titleFont: { size: 13, weight: '600' },
                    bodyFont: { size: 12 },
                    padding: 12,
                    cornerRadius: 8,
                    callbacks: {
                        label: function(context) {
                            return 'Rp ' + context.raw.toLocaleString('id-ID');
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + value.toLocaleString('id-ID');
                        }
                    }
                }
            }
        }
    });
</script>
@endpush