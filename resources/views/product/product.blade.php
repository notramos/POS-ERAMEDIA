@extends('layouts.home')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Manajemen Produk</h1>
        <a href="{{ route('pembelian.create') }}" 
        class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i> Tambah Produk
        </a>
    </div>

    <!-- DataTales Example -->
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
                            <th>Harga</th>
                            <th>Satuan</th>
                            <th>Stok</th>
                            <th>Detail</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($products as $product)
                        <tr>
                            <td>{{ $product->id }}</td>
                            <td>{{ $product->name }}</td>
                            <td>Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                            <td>{{ optional($product->unit)->name ?? '–' }}</td>
                            <td>{{ $product->stock }}</td>
                            <td>{{ $product->detail ?? '–' }}</td>
                            <td>
                                <button type="button" class="btn btn-sm btn-warning" 
                                    data-toggle="modal" 
                                    data-target="#modalProduct"
                                    onclick="editProduct({{ $product->id }}, '{{ addslashes($product->name) }}', {{ $product->price }}, {{ $product->unit_id ?? 'null' }}, {{ $product->stock }}, '{{ addslashes($product->detail ?? '') }}' ,{{ $product->supplier_id }})">
                                    Edit
                                </button>
                                <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" 
                                        onclick="return confirm('Yakin hapus {{ $product->name }}?')">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="d-flex justify-content-center">
                    {{ $products->appends(request()->query())->links('public.pagination.sb-admin-2')  }}
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah/Edit Produk -->
<div class="modal fade" id="modalProduct" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="productForm" method="POST">
                @csrf
                <input type="hidden" name="_method" id="formMethod" value="POST">
                <input type="hidden" name="id" id="productId">

                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Tambah Produk</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nama Produk</label>
                        <input type="text" name="name" id="productName" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Harga</label>
                        <input type="number" name="price" id="productPrice" class="form-control" min="0" required>
                    </div>
                    <div class="form-group">
                        <label>Satuan (unit_id)</label>
                        <select name="unit_id" id="productUnit" class="form-control">
                            <option value="">– Pilih Satuan –</option>
                            @foreach($units as $unit)
                                <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Supplier</label>
                        <select name="supplier_id" id="productSupplier" class="form-control" required>
                            <option value="">– Pilih Supplier –</option>
                            @foreach( $suppliers as $supplier)
                                <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                            @endforeach
                        </select>
                    <div class="form-group">
                        <label>Stok</label>
                        <input type="number" name="stock" id="productStock" class="form-control" min="0" required>
                    </div>
                    <div class="form-group">
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

    // Isi form saat edit
    function editProduct(id, name, price, unitId, stock, detail,supplierId) {
        $('#modalTitle').text('Edit Produk');
        $('#formMethod').val('PUT');
        $('#productId').val(id);
        $('#productName').val(name);
        $('#productSupplier').val(supplierId);
        $('#productPrice').val(price);
        $('#productUnit').val(unitId === 'null' ? '' : unitId);
        $('#productStock').val(stock);
        $('#productDetail').val(detail);
        $('#productForm').attr('action', '/products/' + id);
    }

         // Fungsi untuk menampilkan notifikasi sukses
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
                setTimeout(() => toast.remove(), 300);
            }, 5000);
        }

        
        @if(session()->has('success'))
            createToast('success', "{{ session('success') }}");
        @elseif(session()->has('error'))
            createToast('error', "{{ session('error') }}");
        @endif
</script>
@endpush
@endsection