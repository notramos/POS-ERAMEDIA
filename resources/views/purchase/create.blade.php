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
                    <div class="d-flex gap-2">
                        <select name="supplier_id" id="supplier_id" class="form-control" required>
                            <option value="">-- Pilih Supplier --</option>
                            @foreach($allSuppliers as $supplier)
                                <option value="{{ $supplier->id }}" {{ $selectedSupplierId == $supplier->id ? 'selected' : '' }}>
                                    {{ $supplier->name }}
                                </option>
                            @endforeach
                        </select>
                        <button type="submit" name="load_products" value="1" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-sync"></i> Muat Barang
                        </button>
                    </div>
                    @error('supplier_id')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                @if($selectedSupplierId)
                    <hr class="my-4">

                    <!-- Barang dari Supplier -->
                    <h5 class="font-weight-bold text-primary mb-3">
                        <i class="fas fa-box-open"></i> Barang dari {{ $allSuppliers->firstWhere('id', $selectedSupplierId)?->name }}
                    </h5>

                    @if($supplierItems->isNotEmpty())
                        <div class="row">
                            @foreach($supplierItems as $item)
                                <div class="col-lg-6 mb-3">
                                    <div class="card border-left-primary shadow-sm h-100 py-2">
                                        <div class="card-body d-flex justify-content-between align-items-center">
                                            <div>
                                                <div class="font-weight-bold text-gray-800">{{ $item->name }}</div>
                                                <small class="text-muted">
                                                    Harga: Rp {{ number_format($item->price, 0, ',', '.') }} |
                                                    Satuan: {{ $item->unit?->name ?? '-' }}
                                                </small>
                                            </div>
                                            <div class="d-flex align-items-center">
                                                <div class="form-check mr-3">
                                                    <input type="checkbox" 
                                                           name="items[{{ $item->id }}][use]" 
                                                           value="1" 
                                                           class="form-check-input">
                                                </div>
                                                <input type="number" 
                                                       name="items[{{ $item->id }}][quantity]" 
                                                       placeholder="Qty" 
                                                       class="form-control w-25" 
                                                       min="1">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> Belum ada barang terdaftar untuk supplier ini.
                        </div>
                    @endif

                    <hr class="my-4">

                    <!-- Tambah Barang Baru -->
                    <h5 class="font-weight-bold text-success mb-3">
                        <i class="fas fa-plus-circle"></i> Tambah Barang Baru
                    </h5>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <input type="text" name="new_items[0][name]" class="form-control" placeholder="Nama Barang" >
                        </div>
                        <div class="col-md-3">
                            <input type="number" name="new_items[0][price]" class="form-control" placeholder="Harga (Rp)" min="0" step="0.01" >
                        </div>
                        <div class="col-md-2">
                            <input type="number" name="new_items[0][quantity]" class="form-control" placeholder="Qty" min="1" >
                        </div>
                        <div class="col-md-2">
                            <select name="new_items[0][unit_id]" class="form-control" >
                                <option value="">Satuan</option>
                                @foreach($units as $unit)
                                    <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Tanggal Pembelian -->
                    <div class="form-group mt-4">
                        <label for="purchase_date" class="font-weight-bold">Tanggal Pembelian</label>
                        <input type="date" 
                               name="purchase_date" 
                               class="form-control" 
                               value="{{ old('purchase_date', date('Y-m-d')) }}" 
                               required>
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

