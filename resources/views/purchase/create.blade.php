@extends('layouts.home')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Tambah Pembelian</h1>
        <a href="{{ route('purchase.purchase') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form method="POST" action="{{ route('pembelian.store') }}">
                @csrf

                <!-- Pilih Supplier -->
                <div class="form-group mb-4">
                    <label for="supplier_id" class="font-weight-bold">Supplier</label>
                    <select name="supplier_id" id="supplier_id" class="form-control" required onchange="this.form.submit()">
                        <option value="">-- Pilih Supplier --</option>
                        @foreach($allSuppliers as $supplier)
                            <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                {{ $supplier->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('supplier_id')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                @if(old('supplier_id'))
                    @php
                        $selectedSupplierId = old('supplier_id');
                        $existingProducts = \App\Models\Product::where('supplier_id', $selectedSupplierId)->get();
                    @endphp

                    <hr class="my-4">

                    <!-- Produk yang Sudah Ada -->
                    <h5 class="font-weight-bold text-primary mb-3">
                        <i class="fas fa-box-open"></i> Produk dari Supplier Ini
                    </h5>

                    @if($existingProducts->isNotEmpty())
                        <div class="row">
                            @foreach($existingProducts as $product)
                                <div class="col-lg-6 mb-3">
                                    <div class="card border-left-primary shadow-sm h-100 py-2">
                                        <div class="card-body d-flex justify-content-between align-items-center">
                                            <div>
                                                <div class="font-weight-bold text-gray-800">{{ $product->name }}</div>
                                                <small class="text-muted">
                                                    Harga: Rp {{ number_format($product->price, 0, ',', '.') }} | 
                                                    Stok: {{ $product->stock }}
                                                </small>
                                            </div>
                                            <div class="d-flex align-items-center">
                                                <div class="form-check mr-3">
                                                    <input type="checkbox" 
                                                           name="items[{{ $product->id }}][use]" 
                                                           value="1" 
                                                           id="use_{{ $product->id }}" 
                                                           class="form-check-input"
                                                           {{ old("items.{$product->id}.use") ? 'checked' : '' }}>
                                                </div>
                                                <input type="number" 
                                                       name="items[{{ $product->id }}][quantity]" 
                                                       placeholder="Qty" 
                                                       class="form-control w-25" 
                                                       min="1"
                                                       value="{{ old("items.{$product->id}.quantity") }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> Belum ada produk terdaftar untuk supplier ini.
                        </div>
                    @endif

                    <hr class="my-4">

                    <!-- Tambah Produk Baru -->
                    <h5 class="font-weight-bold text-success mb-3">
                        <i class="fas fa-plus-circle"></i> Tambah Produk Baru (Opsional)
                    </h5>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <input type="text" 
                                   name="new_product[name]" 
                                   class="form-control" 
                                   placeholder="Nama Produk" 
                                   value="{{ old('new_product.name') }}">
                        </div>
                        <div class="col-md-3">
                            <input type="number" 
                                   name="new_product[price]" 
                                   class="form-control" 
                                   placeholder="Harga (Rp)" 
                                   min="0" 
                                   value="{{ old('new_product.price') }}">
                        </div>
                        <div class="col-md-3">
                            <input type="number" 
                                   name="new_product[stock]" 
                                   class="form-control" 
                                   placeholder="Stok Awal" 
                                   min="0" 
                                   value="{{ old('new_product.stock') }}">
                        </div>
                        <div class="col-md-2">
                             <select name="new_product[unit_id]" class="form-control" required>
                                <option value="">-- Satuan --</option>
                                @foreach($units as $unit)
                                    <option value="{{ $unit->id }}" {{ old('new_product.unit_id') == $unit->id ? 'selected' : '' }}>
                                        {{ $unit->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 mt-3">
                            <textarea name="new_product[detail]" 
                                    class="form-control" 
                                    placeholder="Catatan / Detail Produk (wajib)"
                                    rows="2">{{ old('new_product.detail') }}</textarea>
                        </div>
                        <input type="hidden" name="new_product[supplier_id]" value="{{ $selectedSupplierId }}">
                     </div>

                    <!-- Tanggal Pembelian -->
                    <div class="form-group mt-4">
                        <label for="purchase_date" class="font-weight-bold">Tanggal Pembelian</label>
                        <input type="date" 
                               name="purchase_date" 
                               id="purchase_date" 
                               class="form-control" 
                               value="{{ old('purchase_date', date('Y-m-d')) }}" 
                               required>
                        @error('purchase_date')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <!-- Tombol Simpan -->
                    <div class="mt-4">
                        <button type="submit" class="btn btn-success btn-icon-split">
                            <span class="icon text-white-50">
                                <i class="fas fa-save"></i>
                            </span>
                            <span class="text">Simpan Pembelian</span>
                        </button>
                    </div>
                @endif
            </form>
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