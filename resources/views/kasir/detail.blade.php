<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Detail Transaksi #{{ $transaction->id }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .main-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 25px 45px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 2rem;
            margin: 2rem auto;
            max-width: 800px;
            animation: slideUp 0.6s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .page-title {
            text-align: center;
            color: #4a5568;
            font-weight: 700;
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
            background: linear-gradient(135deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .transaction-info {
            background: linear-gradient(145deg, #f8fafc, #edf2f7);
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            border: 2px solid #e2e8f0;
        }

        .info-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.5rem 0;
            border-bottom: 1px solid #e2e8f0;
        }

        .info-item:last-child {
            border-bottom: none;
        }

        .info-label {
            font-weight: 600;
            color: #4a5568;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .info-value {
            font-weight: 500;
            color: #2d3748;
        }

        .items-table {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            margin-bottom: 2rem;
        }

        .table {
            margin-bottom: 0;
        }

        .table thead th {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            font-weight: 600;
            border: none;
            padding: 1rem;
        }

        .table tbody td {
            padding: 1rem;
            border-color: #e2e8f0;
            vertical-align: middle;
        }

        .table tbody tr:hover {
            background-color: #f8fafc;
        }

        .total-section {
            background: linear-gradient(135deg, #48bb78, #38a169);
            color: white;
            padding: 1.5rem;
            border-radius: 15px;
            text-align: center;
            margin: 1.5rem 0;
        }

        .total-amount {
            font-size: 2.2rem;
            font-weight: bold;
            margin: 0.5rem 0;
        }

        .btn {
            border-radius: 10px;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
            border: none;
            position: relative;
            overflow: hidden;
        }

        .btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s ease;
        }

        .btn:hover::before {
            left: 100%;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea, #764ba2);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 35px rgba(102, 126, 234, 0.4);
        }

        .btn-success {
            background: linear-gradient(135deg, #48bb78, #38a169);
            box-shadow: 0 8px 25px rgba(72, 187, 120, 0.3);
            color: white;
        }

        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 35px rgba(72, 187, 120, 0.4);
        }

        .btn-secondary {
            background: linear-gradient(135deg, #718096, #4a5568);
            box-shadow: 0 8px 25px rgba(113, 128, 150, 0.3);
            color: white;
        }

        .btn-secondary:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 35px rgba(113, 128, 150, 0.4);
        }

        .action-buttons {
            text-align: center;
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 2px solid #e2e8f0;
        }

        .alert {
            border-radius: 10px;
            border: none;
            padding: 1rem 1.5rem;
            margin-bottom: 1.5rem;
        }

        .product-name {
            font-weight: 600;
            color: #2d3748;
        }

        .price-cell {
            text-align: right;
            font-weight: 500;
        }

        .quantity-cell {
            text-align: center;
            font-weight: 600;
            color: #667eea;
        }

        .subtotal-cell {
            text-align: right;
            font-weight: 600;
            color: #48bb78;
        }

        @media (max-width: 768px) {
            .main-container {
                margin: 1rem;
                padding: 1.5rem;
            }

            .page-title {
                font-size: 2rem;
            }

            .table-responsive {
                font-size: 0.9rem;
            }
        }
    </style>
</head>

<body>
    <div class="main-container">
        <!-- Alert untuk pesan sukses -->
        @if (session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        <h1 class="page-title">
            <i class="fas fa-receipt"></i> Detail Transaksi
        </h1>

        <!-- Informasi Transaksi -->
        <div class="transaction-info">
            <div class="info-item">
                <span class="info-label">
                    <i class="fas fa-hashtag"></i> ID Transaksi
                </span>
                <span class="info-value">#{{ $transaction->id }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">
                    <i class="fas fa-clock"></i> Tanggal
                </span>
                <span class="info-value">{{ optional($transaction->created_at)->format('d/m/Y H:i:s') }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">
                    <i class="fas fa-shopping-cart"></i> Total Item
                </span>
                <span class="info-value">{{ $transaction->details->sum('quantity') }} item</span>
            </div>
        </div>

        <!-- Tabel Detail Produk -->
        <div class="items-table">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th><i class="fas fa-hashtag"></i> No</th>
                            <th><i class="fas fa-box"></i> Nama Produk</th>
                            <th><i class="fas fa-ruler"></i> Unit</th>
                            <th><i class="fas fa-tag"></i> Harga per Unit</th>
                            <th><i class="fas fa-sort-numeric-up"></i> Jumlah</th>
                            <th><i class="fas fa-calculator"></i> Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($transaction->details as $index => $detail)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $detail->product->name }}</td>
                                <td>{{ $detail->unit->name ?? '-' }}</td>
                                <td>Rp
                                    {{ number_format($detail->unit->price_per_unit ?? $detail->product->price, 0, ',', '.') }}
                                </td>
                                <td>{{ $detail->quantity }}</td>
                                <td>Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Total Transaksi -->
        <div class="total-section">
            <h4><i class="fas fa-calculator"></i> Total Transaksi</h4>
            <div class="total-amount">Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</div>
            <small>{{ $transaction->details->count() }} produk, {{ $transaction->details->sum('quantity') }}
                item</small>
        </div>

        <!-- Tombol Aksi -->
        <div class="action-buttons">
            <div class="d-flex gap-3 justify-content-center flex-wrap">
                <a href="" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali ke Daftar
                </a>
                <a href="{{ route('kasir.index') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Transaksi Baru
                </a>
                <a href="{{ route('transactions.receipt', $transaction->id) }}" class="btn btn-success"
                    target="_blank">
                    <i class="fas fa-print"></i> Cetak Struk
                </a>
            </div>
        </div>
    </div>

    <script>
        // Auto hide success alert after 5 seconds
        setTimeout(function() {
            const alert = document.querySelector('.alert-success');
            if (alert) {
                alert.style.transition = 'opacity 0.5s ease';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            }
        }, 5000);
    </script>
</body>

</html>
