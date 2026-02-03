@extends('layouts.home') {{-- Pastikan layout ini sesuai template SB Admin 2 --}}

@section('content')
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Daftar Pembelian</h1>
        <a href="{{ route('pembelian.create') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i> Tambah Pembelian
        </a>
    </div>

    <div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Filter pembelian</h6>
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

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Supplier</th>
                            <th>Tanggal</th>
                            <th>Total</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($purchases as $purchase)
                        <tr>
                            <td>{{ $purchase->id }}</td>
                            <td>{{ $purchase->supplier->name ?? '–' }}</td>
                            <td>{{ \Carbon\Carbon::parse($purchase->purchase_date)->format('d-m-Y') }}</td>
                            <td>Rp {{ number_format($purchase->total_amount, 0, ',', '.') }}</td>
                            <td>
                                <a href="#" class="btn btn-sm btn-warning" 
                                   data-toggle="collapse" 
                                   data-target="#items-{{ $purchase->id }}">
                                    <i class="fas fa-eye"></i> Detail
                                </a>
                                 <form action="{{ route('pembelian.destroy', $purchase->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger"
                                            onclick="return confirm('Yakin hapus pembelian ID {{ $purchase->id }} dari {{ $purchase->supplier->name ?? 'supplier ini' }}?')">
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="5" class="p-0">
                                <div id="items-{{ $purchase->id }}" class="collapse">
                                    <div class="bg-light p-3">
                                        <strong>Item yang Dibeli:</strong>
                                        <table class="table table-sm mt-2 mb-0">
                                            <thead>
                                                <tr>
                                                    <th>Produk</th>
                                                    <th>Qty</th>
                                                    <th>Subtotal</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($purchase->items as $item)
                                                <tr>
                                                    <td>{{ $item->product->name ?? '–' }}</td>
                                                    <td>{{ $item->quantity }}</td>
                                                    <td>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
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
                            <td colspan="5" class="text-center">Belum ada data pembelian.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection
@push('scripts')
    <script>
function createToast(type, message) {
    const existing = document.getElementById('flash-toast');
    if (existing) existing.remove();

    const toast = document.createElement('div');
    toast.id = 'flash-toast';
    toast.className = `alert alert-${type === 'success' ? 'success' : 'danger'} position-fixed fade show`;
    toast.role = 'alert';
    Object.assign(toast.style, {
        top: '20px',
        left: '50%',
        transform: 'translateX(-50%)',
        zIndex: '9999',
        maxWidth: '90%',
        width: 'auto',
        padding: '0.75rem 1.25rem',
        borderRadius: '0.375rem',
        boxShadow: '0 4px 12px rgba(0,0,0,0.15)'
    });

    const icon = type === 'success' 
        ? '<i class="fas fa-check-circle"></i>' 
        : '<i class="fas fa-exclamation-circle"></i>';
    
    toast.innerHTML = `
        <div class="d-flex justify-content-between align-items-center">
            <span>${icon} ${message}</span>
            <button type="button" class="close ml-2" style="font-size:1.2rem; opacity:1;" onclick="this.parentElement.parentElement.remove()">
                <span>&times;</span>
            </button>
        </div>
    `;

    document.body.appendChild(toast);

    // Auto-hide setelah 5 detik
    setTimeout(() => {
        toast.classList.remove('show');
        toast.classList.add('fade');
        setTimeout(() => {
            if (toast.parentNode) toast.parentNode.removeChild(toast);
        }, 300);
    }, 5000);
}

// 🔥 INI YANG KRITIS: JALANKAN SETELAH DOM SIAP
document.addEventListener('DOMContentLoaded', function () {
    @if(session()->has('success'))
        createToast('success', @json(session('success')));
    @elseif(session()->has('error'))
        createToast('error', @json(session('error')));
    @endif
});
</script>
        
@endpush