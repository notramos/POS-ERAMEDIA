<hr class="my-4">

<!-- Barang dari Supplier -->
<h5 class="font-weight-bold text-primary mb-3">
    <i class="fas fa-box-open"></i> Barang dari {{ $supplier->name }}
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
                    <div class="mr-3 d-flex align-items-center" style="height: 34px;">
                        <input type="checkbox"
                               name="items[{{ $item->id }}][use]"
                               value="1"
                               class="form-check-input mt-0"
                               style="position: static; margin-left: 0;">
                    </div>
                    <input type="number"
                           name="items[{{ $item->id }}][quantity]"
                           placeholder="Qty"
                           class="form-control mr-3"
                           style="width: 70px;"
                           min="1">
                    <button type="button" class="btn btn-sm btn-outline-danger rounded-circle delete-supplier-item"
                            style="width: 34px; height: 34px; padding: 0; display: flex; align-items: center; justify-content: center; opacity: 0.55; transition: opacity .2s, transform .2s;"
                            data-id="{{ $item->id }}"
                            data-name="{{ $item->name }}"
                            title="Hapus {{ $item->name }}"
                            onmouseover="this.style.opacity='1'; this.style.transform='scale(1.15)'"
                            onmouseout="this.style.opacity='0.55'; this.style.transform='scale(1)'">
                        <i class="fas fa-trash-alt" style="font-size: 12px;"></i>
                    </button>
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
        <input type="text" name="new_items[0][name]" class="form-control" placeholder="Nama Barang">
    </div>
    <div class="col-md-3">
        <input type="number" name="new_items[0][price]" class="form-control" placeholder="Harga (Rp)" min="0" step="0.01">
    </div>
    <div class="col-md-2">
        <input type="number" name="new_items[0][quantity]" class="form-control" placeholder="Qty" min="1">
    </div>
    <div class="col-md-2">
        <select name="new_items[0][unit_id]" class="form-control">
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
           id="purchase_date"
           class="form-control"
           value="{{ date('Y-m-d') }}"
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
