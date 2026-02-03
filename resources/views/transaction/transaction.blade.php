@extends('layouts.home')

@section('content')
<div class="container-fluid">

    <!-- Notifikasi Flash -->
    @if(session('success') || session('error'))
        <div class="alert {{ session('success') ? 'alert-success' : 'alert-danger' }} 
                   position-fixed fade show"
             role="alert"
             style="top: 20px; left: 50%; transform: translateX(-50%); z-index: 9999; max-width: 90%;">
            <div class="d-flex justify-content-between align-items-center">
                <span>
                    @if(session('success'))
                        <i class="fas fa-check-circle"></i> {{ session('success') }}
                    @else
                        <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                    @endif
                </span>
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
        </div>
    @endif

    <!-- Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Daftar Transaksi Penjualan</h1>
        <a href="{{ route('kasir.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i> Buat Transaksi Baru
        </a>
    </div>
    <div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Filter Transaksi</h6>
    </div>
    <div class="card-body">
            <form method="GET" class="row g-3">
                {{-- <div class="col-md-4">
                    <label for="search" class="form-label">Cari (ID / Nama Produk)</label>
                    <input type="text" name="search" id="search" class="form-control" 
                        value="{{ request('search') }}" placeholder="Masukkan ID atau nama produk">
                </div> --}}
                <div class="col-md-3">
                    <label for="start_date" class="form-label">Dari Tanggal</label>
                    <input type="date" name="start_date" id="start_date" class="form-control" 
                        value="{{ request('start_date') }}">
                </div>
                <div class="col-md-3">
                    <label for="end_date" class="form-label">Sampai Tanggal</label>
                    <input type="date" name="end_date" id="end_date" class="form-control" 
                        value="{{ request('end_date') }}">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabel Transaksi -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Riwayat Transaksi</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Total</th>
                            <th>Dibayar</th>
                            <th>Kembalian</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transactions as $transaction)
                        <tr>
                            <td>{{ ($transactions->currentPage() - 1) * $transactions->perPage() + $loop->iteration }}</td>
                            <td>{{ $transaction->created_at->format('d-m-Y H:i') }}</td>
                            <td>Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($transaction->paid_amount, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($transaction->change_amount, 0, ',', '.') }}</td>
                            <td>
                                <a href="{{  route('transaction.receipt', $transaction->id) }}" class="btn btn-sm btn-info" target="_blank">
                                    <i class="fas fa-print"></i> Struk
                                </a>
                                <a href="#" class="btn btn-sm btn-warning" data-toggle="collapse" data-target="#details-{{ $transaction->id }}">
                                    <i class="fas fa-eye"></i> Detail
                                </a>
                                <form action="{{ route('kasir.delete',$transaction->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus transaksi #{{ $transaction->id }}?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="6" class="p-0">
                                <div id="details-{{ $transaction->id }}" class="collapse">
                                    <div class="bg-light p-3">
                                        <strong>Detail Produk:</strong>
                                        <table class="table table-sm mt-2">
                                            <thead>
                                                <tr>
                                                    <th>Produk</th>
                                                    <th>Qty</th>
                                                    <th>Harga Satuan</th>
                                                    <th>Subtotal</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($transaction->details as $detail)
                                                <tr>
                                                    <td>{{ $detail->product->name ?? 'Produk tidak ditemukan' }}</td>
                                                    <td>{{ $detail->quantity }}</td>
                                                    <td>Rp {{ number_format($detail->price, 0, ',', '.') }}</td>
                                                    <td>Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <div class="d-flex justify-content-end mt-3">
                        {{ $transactions->appends(request()->query())->links('public.pagination.sb-admin-2') }}
                    </div>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection