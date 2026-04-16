@extends('layouts.home')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Manajemen Produk</h1>
        @if(optional(auth()->user()->role)->name === 'admin')
            <button type="button" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"
                    data-toggle="modal" data-target="#modalProduct">
                <i class="fas fa-plus fa-sm text-white-50"></i> Tambah Produk
            </button>
        @endif
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Produk</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama</th>
                            <th>Harga Jual</th>
                            <th>Stok</th>
                            <th>Supplier</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($products as $product)
                        <tr>
                            <td>{{ $product->id }}</td>
                            <td>{{ $product->name }}</td>
                            <td>Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                            <td>{{ $product->stock }}</td>
                            <td>{{ optional($product->supplier)->name ?? '–' }}</td>
                            <td>
                                @if(optional(auth()->user()->role)->name === 'admin')
                                    <button type="button" 
                                            class="btn btn-sm btn-warning btn-edit-product"
                                            data-id="{{ $product->id }}"
                                            data-name="{{ $product->name }}"
                                            data-price="{{ $product->price }}"
                                            data-stock="{{ $product->stock }}"
                                            data-detail="{{ $product->detail ?? '' }}"
                                            data-supplier-id="{{ $product->supplier_id }}">
                                        Edit
                                    </button>
                                    <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger"
                                                onclick="return confirm('Yakin hapus {{ $product->name }}?')">
                                            Hapus
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="d-flex justify-content-center mt-3">
                    {{ $products->appends(request()->query())->links('public.pagination.sb-admin-2') }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="modalProduct" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="productForm" method="POST">
                @csrf
                <input type="hidden" name="_method" id="formMethod" value="POST">
                <input type="hidden" name="id" id="productId">

                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Tambah Produk</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Untuk Tambah -->
                    <div id="addSection">
                        <div class="form-group">
                            <label>Supplier <span class="text-danger">*</span></label>
                            <select  id="productSupplier" class="form-control" >
                                <option value="">– Pilih –</option>
                                @foreach($suppliers as $s)
                                    <option value="{{ $s->id }}">{{ $s->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Barang <span class="text-danger">*</span></label>
                            <select name="item_name" id="supplierItemSelect" class="form-control" required disabled>
                                <option value="">– Pilih Supplier –</option>
                            </select>
                            <small class="d-block mt-1">
                                <span class="text-muted">Stok Supplier: <span id="availableStock">–</span></span>
                                <span class="text-muted"> | Harga Beli: <span id="purchasePrice">–</span></span>
                            </small>
                            <small id="productExistsInfo" class="d-block mt-1"></small>
                        </div>
                        <input type="hidden" name="supplier_id" id="selectedSupplierId">
                    </div>

                    <!-- Shared Fields -->
                    <div class="form-group">
                        <label>Stok <span class="text-danger">*</span></label>
                        <input type="number" name="stock" id="productStock" class="form-control" min="0" required>
                    </div>
                    @if($errors->has('stock'))
                        <div class="alert alert-danger mt-2">{{ $errors->first('stock') }}</div>
                    @endif
                    <div class="form-group" id="priceField">
                        <label>Harga Jual (Rp) <span class="text-danger">*</span></label>
                        <input type="number" name="price" id="productPrice" class="form-control" min="0">
                    </div>
                    <div class="form-group" id="detailField">
                        <label>Detail</label>
                        <textarea name="detail" id="productDetail" class="form-control" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
console.log('Product blade loaded');
$(document).ready(function() {
    // Event delegation untuk tombol edit
    $(document).on('click', '.btn-edit-product', function() {
        const btn = $(this);
        $('#modalTitle').text('Edit Produk');
        $('#formMethod').val('PUT');
        $('#productId').val(btn.data('id'));
        $('#productForm').attr('action', '/products/' + btn.data('id'));
        $('#addSection').hide();

        $('#productStock').val(btn.data('stock') || 0);
        $('#productPrice').val(btn.data('price') || 0);
        $('#productDetail').val(btn.data('detail') || '');

        $('#modalProduct').modal('show');
    });

    // Reset form saat buka modal tambah
    $('[data-target="#modalProduct"]').not('.btn-edit-product').on('click', function() {
        $('#modalTitle').text('Tambah Produk');
        $('#formMethod').val('POST');
        $('#productForm').attr('action', "{{ route('products.store') }}");
        $('#productForm')[0].reset();
        $('#addSection').show();
        $('#availableStock').text('–');
        $('#purchasePrice').text('–');
    });

// Load barang saat pilih supplier - LOAD DATA tapi tidak auto-select
    $('#productSupplier').change(function() {
        const supplierId = $(this).val();
        $('#selectedSupplierId').val(supplierId);
        const select = $('#supplierItemSelect');
        
        if (!supplierId) {
            select.empty().append('<option value="">-- Pilih Supplier --</option>').prop('disabled', true);
            $('#availableStock').text('–');
            $('#purchasePrice').text('–');
            $('#productExistsInfo').text('');
            $('#priceField').show();
            $('#detailField').show();
            return;
        }
        
        select.empty().append('<option value="">Memuat...</option>').prop('disabled', true);
        $('#availableStock').text('–');
        $('#purchasePrice').text('–');
        
        $.get(`/products/supplier-items/${supplierId}`, function(data) {
            select.empty();
            if (data.length === 0) {
                select.append('<option value="">Tidak ada barang</option>');
            } else {
                // Placeholder - user harus pilih salah satu
                select.append('<option value="">-- Pilih Barang --</option>');
                data.forEach(item => {
                    select.append(`
                        <option value="${item.name}" 
                                data-stock="${item.stock}"
                                data-price="${item.purchase_price}">
                            ${item.name} (${item.unit_name || '-'})
                        </option>
                    `);
                });
            }
            select.prop('disabled', data.length === 0);
        });
        
        $('#productExistsInfo').text('');
        $('#priceField').show();
        $('#detailField').show();
    });

    
    document.getElementById('supplierItemSelect').addEventListener('change', function(e) {
        
        const option = e.target.selectedOptions[0];
        
        // Skip if placeholder (empty value)
        if (!option || !option.value) {
            return;
        }
        
        
        const stock = option.dataset.stock;
        const price = option.dataset.price;
        const supplierId = document.getElementById('selectedSupplierId').value;
        const itemName = option.value;
        
        
        
        document.getElementById('availableStock').textContent = stock || '0';
        document.getElementById('purchasePrice').textContent = price ? `Rp ${Number(price).toLocaleString('id-ID')}` : '–';
        
        if (!supplierId || !itemName) return;
        
        fetch(`/products/check-existence?supplier_id=${supplierId}&item_name=${encodeURIComponent(itemName)}`)
            .then(res => res.json())
            .then(res => {
               
                const infoEl = document.getElementById('productExistsInfo');
                const priceField = document.getElementById('priceField');
                const detailField = document.getElementById('detailField');
                
                if (res.exists) {
                    infoEl.textContent = '⚠️ Produk sudah ada, stok akan ditambahkan';
                    infoEl.className = 'd-block mt-1 text-warning';
                    priceField.style.display = 'none';
                    detailField.style.display = 'none';
                } else {
                    infoEl.textContent = '✅ Produk baru akan dibuat';
                    infoEl.className = 'd-block mt-1 text-success';
                    priceField.style.display = 'block';
                    detailField.style.display = 'block';
                }
            });
    });

    // 🔥 SOLUSI UTAMA: Force close modal tanpa validasi
    $(document).on('click', '[data-dismiss="modal"]', function(e) {
        const modal = $('#modalProduct');
        const form = modal.find('form')[0];
        
        if (form) {
            // Nonaktifkan validasi native browser
            form.noValidate = true;
            // Reset setelah ditutup
            modal.one('hidden.bs.modal', function() {
                form.reset();
                form.noValidate = false;
                $('#addSection').show();
                $('#modalTitle').text('Tambah Produk');
                $('#availableStock').text('–');
                $('#purchasePrice').text('–');
            });
        }
        
        modal.modal('hide');
        e.preventDefault();
        return false;
    });

    // Jika ada error validasi, buka modal kembali dan tampilkan pesan
    @if($errors->any())
        // Tampilkan notifikasi error pertama
        createToast('error', @json($errors->first()));
        (function() {
            const id = @json(old('id'));
            if (id) {
                $('#modalTitle').text('Edit Produk');
                $('#formMethod').val(@json(old('_method', 'PUT')));
                $('#productId').val(id);
                $('#productForm').attr('action', '/products/' + id);
                $('#addSection').hide();
            } else {
                $('#modalTitle').text('Tambah Produk');
                $('#formMethod').val('POST');
                $('#productForm').attr('action', "{{ route('products.store') }}");
                $('#addSection').show();
            }

            $('#productStock').val(@json(old('stock')));
            $('#productPrice').val(@json(old('price')));
            $('#productDetail').val(@json(old('detail')));
            $('#modalProduct').modal('show');
        })();
    @endif

});

</script>
@endpush
@endsection