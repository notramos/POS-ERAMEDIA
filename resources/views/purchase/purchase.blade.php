@extends('layouts.home')

@section('content')
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Daftar Pembelian</h1>
        <a href="{{ route('pembelian.create') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i> Tambah Pembelian
        </a>
    </div>

    <!-- Filter Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filter Pembelian</h6>
        </div>
        <div class="card-body">
            <form method="GET" class="row g-3">
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
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                </div>
                @if(request()->has('start_date') || request()->has('end_date'))
                <div class="col-md-2 d-flex align-items-end">
                    <a href="{{ route('purchase.purchase') }}" class="btn btn-secondary w-100">
                        <i class="fas fa-times"></i> Reset
                    </a>
                </div>
                @endif
            </form>
        </div>
    </div>

    <!-- Data Table -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                    <thead class="bg-light">
                        <tr>
                            <th>ID</th>
                            <th>Supplier</th>
                            <th>Tanggal</th>
                            <th class="text-right">Total</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($purchases as $purchase)
                        <tr>
                            <td>{{ $purchase->id }}</td>
                            <td>{{ $purchase->supplier->name ?? '–' }}</td>
                            <td>{{ \Carbon\Carbon::parse($purchase->purchase_date)->format('d-m-Y') }}</td>
                            <td class="text-right">Rp {{ number_format($purchase->total_amount, 0, ',', '.') }}</td>
                            <td class="text-center">
                                <a href="#" class="btn btn-sm btn-info" 
                                   data-toggle="collapse" 
                                   data-target="#items-{{ $purchase->id }}">
                                    <i class="fas fa-eye"></i> Detail
                                </a>
                                <form action="{{ route('pembelian.destroy', $purchase->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger"
                                            onclick="return confirm('Yakin hapus pembelian ID {{ $purchase->id }}?')">
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="5" class="p-0 border-0">
                                <div id="items-{{ $purchase->id }}" class="collapse">
                                    <div class="bg-light p-3 border">
                                        <strong>Barang yang Dibeli:</strong>
                                        <table class="table table-sm mt-2 mb-0">
                                            <thead>
                                                <tr>
                                                    <th>Nama Barang</th>
                                                    <th class="text-center">Qty</th>
                                                    <th class="text-right">Subtotal</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($purchase->items as $item)
                                                <tr>
                                                    <td>{{ $item->supplier->name ?? '–' }}</td>
                                                    <td class="text-center">{{ $item->quantity }}</td>
                                                    <td class="text-right">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">
                                <i class="fas fa-inbox fa-2x mb-2"></i><br>
                                Belum ada data pembelian.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>

                <!-- Pagination -->
                @if($purchases->hasPages())
                <div class="d-flex justify-content-end mt-3">
                    {{ $purchases->appends(request()->query())->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>

</div>
@endsection

