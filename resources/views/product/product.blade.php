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
                                    @php
                                        $supplierItem = $product->supplier_id
                                            ? \App\Models\SupplierItem::where('supplier_id', $product->supplier_id)
                                                ->where('name', $product->name)
                                                ->first()
                                            : null;
                                        $purchasePrice = $supplierItem?->price ?? 0;
                                        $currentMargin = $product->price - $purchasePrice;
                                    @endphp
                                    <button type="button" 
                                            class="btn btn-sm btn-warning btn-edit-product"
                                            data-id="{{ $product->id }}"
                                            data-name="{{ $product->name }}"
                                            data-price="{{ $product->price }}"
                                            data-supplier-price="{{ $purchasePrice }}"
                                            data-margin="{{ $currentMargin }}"
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
                            <select id="productSupplier" class="form-control">
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
                                <span class="text-muted"> | Harga Beli: <span id="purchasePriceAdd">–</span></span>
                            </small>
                        </div>
                        <input type="hidden" name="supplier_id" id="selectedSupplierId">
                    </div>

                     <!-- Shared Fields -->
                     <input type="hidden" name="stock" id="productStock" value="1">
                     <div class="form-group">
                        <label>Harga Beli (dari Supplier)</label>
                        <div class="form-control" style="background-color: #e9ecef;" id="purchasePrice">–</div>
                    </div>
                     <div class="form-group">
                        <label>Margin (Rp) <span class="text-danger">*</span></label>
                        <input type="number" name="margin" id="productMargin" class="form-control" min="0" value="0">
                    </div>
                    <div class="form-group">
                        <label>Harga Jual (auto)</label>
                        <div name="price" class="form-control" style="background-color: #e9ecef;" id="sellPriceDisplay">Rp 0</div>
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
    let purchasePrice = 0;

    // Event delegation untuk tombol edit
    $(document).on('click', '.btn-edit-product', function() {
        const btn = $(this);
        $('#modalTitle').text('Edit Produk');
        $('#formMethod').val('PUT');
        $('#productId').val(btn.data('id'));
        $('#productForm').attr('action', '/products/' + btn.data('id'));
        $('#addSection').hide();

        purchasePrice = parseFloat(btn.data('supplier-price')) || 0;
        $('#productMargin').val(parseFloat(btn.data('margin')) || 0);
        $('#productStock').val(btn.data('stock') || 0);
        $('#productDetail').val(btn.data('detail') || '');
        $('#purchasePrice').text(purchasePrice ? 'Rp ' + purchasePrice.toLocaleString('id-ID') : '–');
        updateSellPrice();

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
        $('#purchasePriceAdd').text('–');
        $('#purchasePrice').text('–');
        $('#productMargin').val(0);
        $('#productStock').val(1);
        purchasePrice = 0;
        updateSellPrice();
    });

    // Load barang saat pilih supplier - LOAD DATA tapi tidak auto-select
    $('#productSupplier').change(function() {
        const supplierId = $(this).val();
        $('#selectedSupplierId').val(supplierId);
        const select = $('#supplierItemSelect');
        
        if (!supplierId) {
            select.empty().append('<option value="">-- Pilih Supplier --</option>').prop('disabled', true);
            $('#availableStock').text('–');
            $('#purchasePriceAdd').text('–');
            $('#purchasePrice').text('–');
            purchasePrice = 0;
            updateSellPrice();
            return;
        }
        
        select.empty().append('<option value="">Memuat...</option>').prop('disabled', true);
        $('#availableStock').text('–');
        $('#purchasePriceAdd').text('–');
        $('#purchasePrice').text('–');
        purchasePrice = 0;
        updateSellPrice();
        
        // Use getAvailableSupplierItems - shows only items not in products
        $.get(`/products/available-supplier-items/${supplierId}`, function(data) {
            select.empty();
            if (data.length === 0) {
                select.append('<option value="">Tidak ada barang</option>');
            } else {
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
    });

    // Update sell price calculation
    function updateSellPrice() {
        const margin = parseFloat($('#productMargin').val()) || 0;
        const sellPrice = purchasePrice + margin;
        $('#sellPriceDisplay').text('Rp ' + sellPrice.toLocaleString('id-ID'));
    }

    $('#productMargin').on('input', updateSellPrice);

     document.getElementById('supplierItemSelect').addEventListener('change', function(e) {
         const option = e.target.selectedOptions[0];
         
         if (!option || !option.value) {
             return;
         }
         
         const stock = option.dataset.stock;
         const price = parseFloat(option.dataset.price) || 0;
         
         purchasePrice = price;
         document.getElementById('availableStock').textContent = stock || '0';
         document.getElementById('purchasePriceAdd').textContent = price ? `Rp ${price.toLocaleString('id-ID')}` : '–';
         document.getElementById('purchasePrice').textContent = price ? `Rp ${price.toLocaleString('id-ID')}` : '–';
         
         // Auto-set stock to available supplier stock
         document.getElementById('productStock').value = stock || 1;
         
         updateSellPrice();
     });

    // Force close modal tanpa validasi
    $(document).on('click', '[data-dismiss="modal"]', function(e) {
        const modal = $('#modalProduct');
        const form = modal.find('form')[0];
        
        if (form) {
            form.noValidate = true;
            modal.one('hidden.bs.modal', function() {
                form.reset();
                form.noValidate = false;
                $('#addSection').show();
                $('#modalTitle').text('Tambah Produk');
                $('#availableStock').text('–');
                $('#purchasePriceAdd').text('–');
                $('#purchasePrice').text('–');
                $('#productStock').val(1);
                purchasePrice = 0;
                updateSellPrice();
            });
        }
        
        modal.modal('hide');
        e.preventDefault();
        return false;
    });

    // Jika ada error validasi, buka modal kembali dan tampilkan pesan
    @if($errors->any())
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

            $('#productStock').val(@json(old('stock', 1)));
            $('#productMargin').val(@json(old('margin', 0)));
            $('#productDetail').val(@json(old('detail')));
            $('#modalProduct').modal('show');
        })();
    @endif

});
</script>
@endpush
@endsection