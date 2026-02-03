@extends('layouts.home')

@section('content')
<div class="container-fluid">

    <h1 class="h3 mb-4 text-gray-800">Kasir - POS ERAMEDIA</h1>

    <div class="row">
        <!-- Daftar Produk -->
        <div class="col-md-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Pilih Produk</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <input type="text" id="productSearch" class="form-control" placeholder="Cari produk...">
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered" id="productTable">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Stok</th>
                                    <th>Harga</th>
                                    <th>Satuan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($products as $product)
                                    <tr data-id="{{ $product->id }}"
                                        data-name="{{ $product->name }}"
                                        data-price="{{ $product->price }}"
                                        data-stock="{{ $product->stock }}"
                                        data-unit="{{ optional($product->unit)->name ?? 'pcs' }}">
                                        <td>{{ $product->name }}</td>
                                        <td>{{ $product->stock }}</td>
                                        <td>Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                                        <td>{{ optional($product->unit)->name ?? 'pcs' }}</td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-success btn-add-item">Tambah</button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="d-flex justify-content-end mt-3">
                        {{ $products->appends(request()->query())->links('public.pagination.sb-admin-2') }}
                         </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Transaksi -->
        <div class="col-md-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Transaksi</h6>
                </div>
                <div class="card-body">
                    <form id="transactionForm" method="POST" action="{{ route('kasir.store') }}">
                        @csrf

                        <h6>Item dalam Keranjang:</h6>
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Produk</th>
                                    <th>Jumlah</th>
                                    <th>Subtotal</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="cartItems">
                                <!-- Item akan muncul di sini -->
                            </tbody>
                        </table>

                        <!-- Input dinamis untuk Laravel -->
                        <div id="dynamicInputs" class="d-none"></div>

                        <hr>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="paid_amount">Jumlah Bayar (Rp)</label>
                                    <input type="number" id="paid_amount" name="paid_amount" class="form-control" min="0" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Total: <span id="totalAmount">Rp 0</span></label><br>
                                    <label>Kembalian: <span id="changeAmount">Rp 0</span></label>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary btn-block" id="submitBtn" disabled>
                            Simpan Transaksi
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let cart = [];

    document.querySelectorAll('.btn-add-item').forEach(btn => {
        btn.addEventListener('click', function () {
            const row = this.closest('tr');
            const product = {
                id: parseInt(row.dataset.id),
                name: row.dataset.name,
                price: parseFloat(row.dataset.price),
                stock: parseInt(row.dataset.stock)
            };

            if (product.stock <= 0) {
                alert('Stok habis untuk: ' + product.name);
                return;
            }

            const existing = cart.find(item => item.id === product.id);
            if (existing) {
                existing.quantity++;
            } else {
                cart.push({ ...product, quantity: 1 });
            }
            renderCart();
        });
    });

    function renderCart() {
        const tbody = document.getElementById('cartItems');
        const inputContainer = document.getElementById('dynamicInputs');
        tbody.innerHTML = '';
        inputContainer.innerHTML = '';

        let total = 0;
        cart.forEach((item, index) => {
            const subtotal = item.price * item.quantity;
            total += subtotal;

            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>${item.name}</td>
                <td>
                    <input type="number" value="${item.quantity}" min="1" class="form-control form-control-sm qty-input" style="width:80px;" data-index="${index}">
                </td>
                <td>Rp ${new Intl.NumberFormat('id-ID').format(subtotal)}</td>
                <td><button class="btn btn-sm btn-danger btn-remove" data-index="${index}">Hapus</button></td>
            `;
            tbody.appendChild(tr);

            inputContainer.innerHTML += `
                <input type="hidden" name="items[${index}][product_id]" value="${item.id}">
                <input type="hidden" name="items[${index}][quantity]" value="${item.quantity}">
            `;
        });

        const paid = parseFloat(document.getElementById('paid_amount').value) || 0;
        document.getElementById('totalAmount').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(total);
        document.getElementById('changeAmount').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(Math.max(0, paid - total));
        document.getElementById('submitBtn').disabled = (cart.length === 0 || paid < total);
    }

    document.getElementById('cartItems').addEventListener('input', function (e) {
        if (e.target.classList.contains('qty-input')) {
            const index = e.target.dataset.index;
            const qty = parseInt(e.target.value) || 1;
            cart[index].quantity = qty;
            renderCart();
        }
    });

    document.getElementById('cartItems').addEventListener('click', function (e) {
        if (e.target.classList.contains('btn-remove')) {
            const index = e.target.dataset.index;
            cart.splice(index, 1);
            renderCart();
        }
    });

    document.getElementById('paid_amount').addEventListener('input', renderCart);

    document.getElementById('transactionForm').addEventListener('submit', function (e) {
        if (cart.length === 0) {
            e.preventDefault();
            alert('Minimal tambahkan satu produk!');
        }
    });

    document.getElementById('productSearch').addEventListener('input', function () {
        const term = this.value.toLowerCase();
        document.querySelectorAll('#productTable tbody tr').forEach(row => {
            const name = row.cells[0].textContent.toLowerCase();
            row.style.display = name.includes(term) ? '' : 'none';
        });
    });

   
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

    document.addEventListener('DOMContentLoaded', function () {
        @if(session()->has('success'))
            createToast('success', @json(session('success')));
        @elseif(session()->has('error'))
            createToast('error', @json(session('error')));
        @endif
    });
</script>
@endpush
@endsection