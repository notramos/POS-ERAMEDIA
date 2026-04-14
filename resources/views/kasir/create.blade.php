@extends('layouts.home')

@push('styles')
<style>
    :root {
        --primary: #6366f1;
        --primary-dark: #4f46e5;
        --success: #10b981;
        --danger: #ef4444;
        --warning: #f59e0b;
        --dark: #1e293b;
        --gray-100: #f1f5f9;
        --gray-200: #e2e8f0;
        --gray-500: #64748b;
    }

    .page-header {
        background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
        color: white;
        border-radius: 20px;
        padding: 28px 32px;
        margin-bottom: 24px;
        position: relative;
        overflow: hidden;
    }

    .page-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 300px;
        height: 300px;
        background: rgba(255,255,255,0.1);
        border-radius: 50%;
    }

    .page-header h1 {
        font-weight: 700;
        font-size: 1.5rem;
        margin: 0;
    }

    .product-card {
        border: none;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0,0,0,0.04);
        border: 1px solid var(--gray-200);
    }

    .product-card .card-header {
        background: white;
        border-bottom: 1px solid var(--gray-200);
        padding: 16px 20px;
    }

    .product-card .card-body {
        padding: 0;
    }

    .product-table th {
        background: var(--gray-100);
        font-weight: 600;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--gray-500);
        padding: 12px 16px;
        border: none;
    }

    .product-table td {
        padding: 12px 16px;
        vertical-align: middle;
        border-bottom: 1px solid var(--gray-100);
    }

    .product-table tbody tr {
        transition: all 0.2s;
    }

    .product-table tbody tr:hover {
        background: var(--gray-100);
    }

    .product-name {
        font-weight: 600;
        color: var(--dark);
    }

    .product-unit {
        font-size: 0.75rem;
        color: var(--gray-500);
    }

    .stock-badge {
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .stock-badge.available {
        background: #d1fae5;
        color: #059669;
    }

    .stock-badge.out {
        background: #fee2e2;
        color: #dc2626;
    }

    .price-text {
        font-weight: 700;
        color: var(--dark);
        font-size: 0.9rem;
    }

    .qty-add {
        width: 50px;
        text-align: center;
        border: 1px solid var(--gray-200);
        border-radius: 6px;
        padding: 6px;
        font-size: 0.8rem;
    }

    .btn-add-product {
        background: var(--success);
        border: none;
        border-radius: 6px;
        width: 32px;
        height: 32px;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .btn-add-product:hover {
        background: #059669;
    }

    .cart-card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.04);
        border: 1px solid var(--gray-200);
        position: sticky;
        top: 20px;
    }

    .cart-card .card-header {
        background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
        color: white;
        border: none;
        padding: 16px 20px;
        border-radius: 16px 16px 0 0;
    }

    .cart-empty {
        text-align: center;
        padding: 40px 20px;
        color: var(--gray-500);
    }

    .cart-empty i {
        font-size: 3rem;
        margin-bottom: 10px;
        opacity: 0.5;
    }

    .qty-input {
        width: 60px;
        text-align: center;
        border-radius: 8px;
        border: 1px solid var(--gray-200);
    }

    .qty-input:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(99,102,241,0.1);
    }

    .btn-add {
        background: var(--success);
        border: none;
        border-radius: 8px;
        width: 36px;
        height: 36px;
    }

    .btn-add:hover {
        background: #059669;
    }

    .out-of-stock-row {
        background: #fef2f2 !important;
        opacity: 0.7;
    }

    .out-of-stock-row td {
        color: var(--danger);
    }

    .search-box {
        border-radius: 12px;
        border: 1px solid var(--gray-200);
        padding: 12px 16px;
        transition: all 0.2s;
        background: var(--gray-100);
    }

    .search-box:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(99,102,241,0.1);
        background: white;
    }

    .payment-btn {
        border-radius: 10px;
        padding: 12px 16px;
        font-weight: 600;
        transition: all 0.2s;
        border: 2px solid transparent;
    }

    .payment-btn.active {
        border-color: var(--primary);
        background: rgba(99,102,241,0.1);
    }

    .total-section {
        background: var(--gray-100);
        border-radius: 12px;
        padding: 16px;
    }

    .total-label {
        font-size: 0.85rem;
        color: var(--gray-500);
    }

    .total-value {
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--dark);
    }

    .grand-total {
        font-size: 1.5rem;
        font-weight: 800;
        color: var(--primary);
    }

    .btn-checkout {
        background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
        border: none;
        border-radius: 12px;
        padding: 14px;
        font-weight: 700;
        font-size: 1rem;
        transition: all 0.3s;
    }

    .btn-checkout:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(99,102,241,0.4);
    }

    .btn-checkout:disabled {
        opacity: 0.5;
        cursor: not-allowed;
        transform: none;
    }

    .date-badge {
        background: rgba(255,255,255,0.2);
        backdrop-filter: blur(10px);
        padding: 10px 18px;
        border-radius: 12px;
        font-size: 0.85rem;
        font-weight: 500;
    }

    .discount-input {
        border: 1px solid var(--gray-200);
        border-radius: 10px;
        padding: 10px 14px;
        background: var(--gray-100);
    }

    .discount-input:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(99,102,241,0.1);
        background: white;
    }

    .paid-input {
        border: 1px solid var(--gray-200);
        border-radius: 10px;
        padding: 12px 16px;
        font-size: 1.1rem;
        font-weight: 600;
    }

    .paid-input:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(99,102,241,0.1);
    }

    .badge-count {
        background: rgba(255,255,255,0.25);
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
    }

    .search-loading {
        text-align: center;
        padding: 1rem;
        color: var(--gray-500);
    }

    #qrisModal {
        display: none;
        position: fixed;
        z-index: 99999;
    }

    #qrisModal.show {
        display: block;
    }

    #qrisModal.show::before {
        content: '';
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.5);
    }

    .qris-modal-content {
        position: fixed !important;
        top: 50% !important;
        left: 50% !important;
        transform: translate(-50%, -50%) !important;
        background: #fff !important;
        border-radius: 8px !important;
        max-width: 250px !important;
        width: 80% !important;
        padding: 10px !important;
        box-shadow: 0 5px 15px rgba(0,0,0,0.2) !important;
    }

    .qris-modal-content p {
        font-size: 12px !important;
        margin-bottom: 8px !important;
    }

    .qris-modal-content small {
        font-size: 11px !important;
    }

    .qris-modal-title {
        font-size: 14px !important;
        font-weight: 700;
    }

    .qris-image {
        max-width: 150px !important;
        height: auto;
    }

    .qris-total {
        font-size: 16px !important;
    }

    .qris-btn-confirm, .qris-btn-cancel {
        padding: 6px 12px !important;
        font-size: 12px !important;
    }

    .qris-modal-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--dark);
    }

    .qris-btn-close {
        font-size: 24px;
        background: none;
        border: none;
        cursor: pointer;
        color: #64748b;
        padding: 0;
        line-height: 1;
    }

    .qris-btn-close:hover {
        color: #1e293b;
    }

    .qris-image {
        max-width: 250px;
        height: auto;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    .qris-total {
        font-size: 1.5rem;
        font-weight: 800;
        color: var(--primary);
    }

    .qris-btn-confirm {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        border: none;
        border-radius: 10px;
        padding: 12px 24px;
        font-weight: 600;
        color: white;
    }

    .qris-btn-cancel {
        background: var(--gray-200);
        border: none;
        border-radius: 10px;
        padding: 12px 24px;
        font-weight: 600;
        color: var(--gray-600);
    }

    .qris-btn-confirm:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(16,185,129,0.4);
    }
</style>
@endpush

@section('content')
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1><i class="fas fa-cash-register me-2"></i> Kasir POS ERAMEDIA</h1>
                <p class="mb-0 opacity-75 mt-1">Pilih produk dan lakukan transaksi</p>
            </div>
            <div class="text-end">
                <span class="date-badge">
                    <i class="fas fa-clock me-1"></i>
                    {{ \Carbon\Carbon::now()->format('H:i') }}
                </span>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-6">
            <div class="product-card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold"><i class="fas fa-box me-2"></i>Pilih Produk</h6>
                    <button id="resetSearch" class="btn btn-sm btn-outline-secondary" style="display:none;">
                        <i class="fas fa-redo"></i> Reset
                    </button>
                </div>
                <div class="card-body p-0">
                    <div class="p-3">
                        <input type="text" id="productSearch" class="form-control search-box" placeholder="Cari produk...">
                    </div>
                    <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                        <table class="table table-hover product-table mb-0">
                            <thead class="sticky-top" style="background: var(--gray-100);">
                                <tr>
                                    <th>Produk</th>
                                    <th>Stok</th>
                                    <th>Harga</th>
                                    <th style="width: 110px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="productTableBody">
                                @foreach($products as $product)
                                    <tr 
                                        data-id="{{ $product->id }}"
                                        data-name="{{ $product->name }}"
                                        data-price="{{ $product->price }}"
                                        data-stock="{{ $product->stock }}"
                                        data-unit="{{ optional($product->unit)->name ?? 'pcs' }}"
                                        @if($product->stock <= 0) class="out-of-stock-row" @endif
                                    >
                                        <td>
                                            <div class="product-name">{{ $product->name }}</div>
                                            <small class="product-unit">{{ optional($product->unit)->name ?? 'pcs' }}</small>
                                        </td>
                                        <td>
                                            @if($product->stock > 0)
                                                <span class="stock-badge available">{{ $product->stock }}</span>
                                            @else
                                                <span class="stock-badge out">Habis</span>
                                            @endif
                                        </td>
                                        <td class="price-text">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                                        <td>
                                            @if($product->stock > 0)
                                                <div class="d-flex gap-1 align-items-center">
                                                    <input 
                                                        type="number" 
                                                        class="qty-add" 
                                                        value="1" 
                                                        min="1" 
                                                        max="{{ $product->stock }}" 
                                                    >
                                                    <button type="button" class="btn btn-add-product">
                                                        <i class="fas fa-plus" style="color: white; font-size: 10px;"></i>
                                                    </button>
                                                </div>
                                            @else
                                                <span class="text-danger fw-bold">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="p-3 border-top">
                        {{ $products->appends(request()->query())->links('public.pagination.sb-admin-2') }}
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="cart-card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold"><i class="fas fa-shopping-cart me-2"></i>Keranjang Belanja</h6>
                    <span class="badge-count" id="cartCount">0 item</span>
                </div>
                <div class="card-body">
                    <form id="transactionForm" method="POST" action="{{ route('kasir.store') }}">
                        @csrf
                        <input type="hidden" id="selected_payment_method" name="payment_method" value="tunai">
                        <input type="hidden" id="discount" name="discount" value="0">
                        <div id="dynamicInputs"></div>

                        <table class="table table-sm mb-3">
                            <thead>
                                <tr>
                                    <th>Produk</th>
                                    <th>Qty</th>
                                    <th>Subtotal</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody id="cartItems">
                                <tr class="cart-empty-row">
                                    <td colspan="4" class="text-center text-muted py-4">
                                        <i class="fas fa-shopping-basket fa-2x mb-2 d-block opacity-50"></i>
                                        Keranjang kosong<br>
                                        <small>Pilih produk dari tabel sebelah</small>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Diskon (%)</label>
                            <input type="number" id="discount_percent_display" class="form-control discount-input" min="0" max="100" step="0.01" placeholder="0">
                        </div>

                        <hr>

                        <div id="paymentSection" style="display: none;">
                            <div class="mb-3" id="paidAmountGroup">
                                <label class="form-label fw-semibold">Jumlah Bayar (Rp)</label>
                                <input type="number" id="paid_amount" name="paid_amount" class="form-control paid-input" min="0">
                            </div>

                            <div class="d-flex gap-2 mb-3">
                                <button type="button" class="payment-btn flex-fill btn btn-outline-primary active" data-method="tunai">
                                    <i class="fas fa-money-bill-wave me-1"></i> Tunai
                                </button>
                                <button type="button" class="payment-btn flex-fill btn btn-outline-success" data-method="qris">
                                    <i class="fas fa-qrcode me-1"></i> QRIS
                                </button>
                            </div>

                            <div class="total-section mb-3">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="total-label">Subtotal</span>
                                    <span class="total-value" id="subtotalAmount">Rp 0</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="total-label">Diskon</span>
                                    <span class="total-value text-danger" id="discountAmount">-Rp 0</span>
                                </div>
                                <hr>
                                <div class="d-flex justify-content-between">
                                    <span class="fw-bold">Total</span>
                                    <span class="grand-total" id="totalAmount">Rp 0</span>
                                </div>
                                <div class="d-flex justify-content-between mt-2">
                                    <span class="total-label">Kembalian</span>
                                    <span class="total-value" id="changeAmount">Rp 0</span>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-checkout w-100" id="submitBtn" disabled>
                                <i class="fas fa-check-circle me-2"></i> Proses Transaksi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div id="qrisModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:99999;">
        <div style="position:fixed; top:50%; left:50%; transform:translate(-50%,-50%); background:#fff; border-radius:8px; width:400px; padding:20px; text-align:center;">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:8px;">
                <span style="font-size:16px; font-weight:bold;">Scan QRIS</span>
                <button onclick="closeQrisModal()" style="border:none; background:none; font-size:24px; cursor:pointer;">&times;</button>
            </div>
            <p style="font-size:14px; margin:8px 0;">Scan QRIS Anda</p>
            <img src="{{ asset('img/qris.jpeg') }}" style="width:250px; margin:10px 0;">
            <p style="font-size:16px; margin:8px 0;"><span id="qrisTotal">Rp 0</span></p>
            <div style="display:flex; gap:8px; justify-content:center; margin-top:12px;">
                <button onclick="cancelQrisPayment()" style="padding:8px 16px; font-size:14px; border:1px solid #ccc; background:#eee; border-radius:4px; cursor:pointer;">Batal</button>
                <button onclick="confirmQrisPayment()" style="padding:8px 16px; font-size:14px; border:none; background:#10b981; color:#fff; border-radius:4px; cursor:pointer;">OK</button>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    let selectedPaymentMethod = 'tunai';
    let cart = [];
    let isSearching = false;

    function attachAddItemListeners() {
        document.querySelectorAll('.btn-add-product').forEach(btn => {
            const newBtn = btn.cloneNode(true);
            btn.parentNode.replaceChild(newBtn, btn);

            newBtn.addEventListener('click', function () {
                const row = this.closest('tr');
                const qtyInput = row.querySelector('.qty-add');
                const quantity = parseInt(qtyInput.value) || 1;
                if (quantity <= 0) return;

                const product = {
                    id: parseInt(row.dataset.id),
                    name: row.dataset.name,
                    price: parseFloat(row.dataset.price),
                    stock: parseInt(row.dataset.stock)
                };

                if (quantity > product.stock) {
                    alert(`Stok tidak mencukupi! Tersedia: ${product.stock}`);
                    qtyInput.value = product.stock;
                    return;
                }

                const existing = cart.find(item => item.id === product.id);
                if (existing) {
                    existing.quantity += quantity;
                    if (existing.quantity > product.stock) {
                        existing.quantity = product.stock;
                        alert(`Stok maksimal: ${product.stock}`);
                    }
                } else {
                    cart.push({ ...product, quantity });
                }

                renderCart();
                qtyInput.value = 1;
            });
        });
    }

    function renderCart() {
        const tbody = document.getElementById('cartItems');
        const inputContainer = document.getElementById('dynamicInputs');
        const paymentSection = document.getElementById('paymentSection');
        const discountPercentDisplay = document.getElementById('discount_percent_display');
        const discountHiddenInput = document.getElementById('discount');
        
        tbody.innerHTML = '';
        inputContainer.innerHTML = '';

        let subtotal = 0;
        cart.forEach((item, index) => {
            const itemSubtotal = item.price * item.quantity;
            subtotal += itemSubtotal;

            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>${item.name}</td>
                <td>
                    <input type="number" value="${item.quantity}" min="1" max="${item.stock}" 
                           class="form-control form-control-sm qty-input" style="width:80px;" data-index="${index}">
                </td>
                <td>Rp ${new Intl.NumberFormat('id-ID').format(itemSubtotal)}</td>
                <td><button class="btn btn-sm btn-danger btn-remove" data-index="${index}">Hapus</button></td>
            `;
            tbody.appendChild(tr);

            inputContainer.innerHTML += `
                <input type="hidden" name="items[${index}][product_id]" value="${item.id}">
                <input type="hidden" name="items[${index}][quantity]" value="${item.quantity}">
            `;
        });

        let discountPercent = 0;
        if (discountPercentDisplay?.value.trim() !== '') {
            const num = parseFloat(discountPercentDisplay.value);
            if (!isNaN(num)) {
                discountPercent = Math.max(0, Math.min(100, num));
            }
        }
        const discountValue = (discountPercent / 100) * subtotal;
        const totalAfterDiscount = Math.max(0, subtotal - discountValue);
        discountHiddenInput.value = Math.round(discountValue);

        if (cart.length === 0) {
            paymentSection.style.display = 'none';
        } else {
            paymentSection.style.display = '';
        }

        document.getElementById('cartCount').textContent = cart.length + ' item';
        document.getElementById('subtotalAmount').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(subtotal);
        document.getElementById('discountAmount').textContent = '- Rp ' + new Intl.NumberFormat('id-ID').format(Math.round(discountValue));
        document.getElementById('totalAmount').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(Math.round(totalAfterDiscount));
        
        const paid = parseFloat(document.getElementById('paid_amount')?.value) || 0;
        const change = Math.max(0, paid - totalAfterDiscount);
        document.getElementById('changeAmount').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(Math.round(change));

        updatePaymentUI();
    }

    function updatePaymentUI() {
        const paidAmountGroup = document.getElementById('paidAmountGroup');
        const submitBtn = document.getElementById('submitBtn');
        const totalText = document.getElementById('totalAmount').textContent;
        const total = parseFloat(totalText.replace(/[^0-9]/g, '')) || 0;

        if (selectedPaymentMethod === 'qris') {
            if (paidAmountGroup) paidAmountGroup.style.display = 'none';
            submitBtn.disabled = (cart.length === 0);
        } else {
            if (paidAmountGroup) paidAmountGroup.style.display = '';
            const paid = parseFloat(document.getElementById('paid_amount')?.value) || 0;
            submitBtn.disabled = (cart.length === 0 || paid < total);
        }
    }

    document.getElementById('cartItems').addEventListener('input', function (e) {
        if (e.target.classList.contains('qty-input')) {
            const index = e.target.dataset.index;
            const qty = parseInt(e.target.value) || 1;
            const maxQty = cart[index].stock;
            if (qty > maxQty) {
                e.target.value = maxQty;
                cart[index].quantity = maxQty;
                alert(`Stok maksimal: ${maxQty}`);
            } else {
                cart[index].quantity = qty;
            }
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

    document.getElementById('discount_percent_display')?.addEventListener('input', renderCart);
    document.getElementById('paid_amount')?.addEventListener('input', renderCart);

    const searchInput = document.getElementById('productSearch');
    const resetBtn = document.getElementById('resetSearch');

    let searchTimeout;
    searchInput.addEventListener('input', function () {
        const term = this.value.trim();
        if (term.length < 2) {
            if (isSearching) window.location.reload();
            return;
        }

        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            isSearching = true;
            resetBtn.style.display = 'inline-block';

            const tbody = document.getElementById('productTableBody');
            tbody.innerHTML = '<tr><td colspan="5" class="search-loading">Mencari produk...</td></tr>';

            fetch("{{ route('kasir.search') }}?q=" + encodeURIComponent(term))
                .then(response => response.json())
                .then(data => {
                    tbody.innerHTML = data.html || '<tr><td colspan="5" class="text-center text-muted">Tidak ada produk ditemukan</td></tr>';
                    attachAddItemListeners();
                })
                .catch(err => {
                    console.error('Search error:', err);
                    tbody.innerHTML = '<tr><td colspan="5" class="text-center text-danger">Gagal memuat hasil pencarian</td></tr>';
                });
        }, 300);
    });

    resetBtn?.addEventListener('click', () => window.location.reload());

    document.getElementById('transactionForm').addEventListener('submit', async function (e) {
        e.preventDefault();

        if (cart.length === 0) {
            createToast('error', 'Minimal tambahkan satu produk!');
            return;
        }

        if (selectedPaymentMethod === 'qris') {
            let subtotal = 0;
            cart.forEach(item => subtotal += item.price * item.quantity);
            const discountInput = document.getElementById('discount_percent_display');
            const discountPercent = discountInput?.value.trim() !== '' ? parseFloat(discountInput.value) : 0;
            const clampedDiscount = isNaN(discountPercent) ? 0 : Math.max(0, Math.min(100, discountPercent));
            const discountValue = (clampedDiscount / 100) * subtotal;
            const totalAmount = Math.max(0, subtotal - discountValue);

            document.getElementById('qrisTotal').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(Math.round(totalAmount));
            showQrisModal();
            return;
        }

        const paid = parseFloat(document.getElementById('paid_amount')?.value) || 0;
        const totalText = document.getElementById('totalAmount').textContent;
        const total = parseFloat(totalText.replace(/[^0-9]/g, '')) || 0;

        if (paid < total) {
            createToast('error', 'Jumlah bayar tidak mencukupi!');
            return;
        }

        await sendTransactionToServer();
    });

    function showQrisModal() {
        document.getElementById('qrisModal').style.display = 'block';
        document.body.style.overflow = 'hidden';
    }

    function closeQrisModal() {
        document.getElementById('qrisModal').style.display = 'none';
        document.body.style.overflow = '';
    }

    async function sendTransactionToServer() {
        const form = document.getElementById('transactionForm');
        const submitBtn = document.getElementById('submitBtn');
        const originalText = submitBtn.innerHTML;

        if (selectedPaymentMethod === 'qris') {
            const totalText = document.getElementById('totalAmount').textContent;
            const total = parseFloat(totalText.replace(/[^0-9]/g, '')) || 0;
            document.getElementById('paid_amount').value = total;
        }

        const formData = new FormData(form);
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Memproses...';

        try {
            const response = await fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' }
            });

            const result = await response.json();

            if (result.success) {
                showModal(result.html);
                cart = [];
                renderCart();
                document.getElementById('discount_percent_display').value = '';
                document.getElementById('paid_amount').value = '';
                createToast('success', 'Transaksi berhasil disimpan!');
            } else {
                createToast('error', result.message || 'Gagal menyimpan transaksi.');
            }
        } catch (err) {
            console.error('Error:', err);
            createToast('error', 'Koneksi gagal.');
        } finally {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        }
    }

    function cancelQrisPayment() {
        closeQrisModal();
        createToast('info', 'Pembayaran QRIS dibatalkan.');
    }

    function confirmQrisPayment() {
        closeQrisModal();
        createToast('success', 'Pembayaran QRIS dikonfirmasi!');
        sendTransactionToServer();
    }

    function showModal(content) {
        let modal = document.getElementById('receiptModal');
        if (!modal) {
            modal = document.createElement('div');
            modal.id = 'receiptModal';
            modal.innerHTML = `
                <div class="modal fade show" style="display:block; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:1050;" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered" style="max-width:400px;">
                        <div class="modal-content" style="border-radius: 16px;">
                            <div class="modal-header">
                                <h5 class="modal-title">Detail Transaksi</h5>
                                <button type="button" class="close" onclick="closeModal()" style="font-size:1.5rem;">&times;</button>
                            </div>
                            <div class="modal-body" id="modalBody"></div>
                        </div>
                    </div>
                </div>
            `;
            document.body.appendChild(modal);
        }
        document.getElementById('modalBody').innerHTML = content;
    }

    function closeModal() {
        const modal = document.getElementById('receiptModal');
        if (modal) modal.remove();
    }

    function loadPrintPreview(transactionId) {
        fetch(`{{ route('transaction.receipt', ':id') }}`.replace(':id', transactionId))
            .then(response => response.text())
            .then(html => {
                const printWin = window.open('', '', 'width=300,height=500');
                printWin.document.write('<html><head><title>Struk #'+transactionId+'</title>');
                printWin.document.write('<style>body{font-family:Courier New;font-size:12px;line-height:1.4;width:300px;margin:0 auto;padding:10px;}@media print{body{width:auto;margin:0;padding:0;}}</style>');
                printWin.document.write('</head><body>');
                printWin.document.write(html);
                printWin.document.write('<script>window.onload = function() { window.print(); window.close(); };<\/script>');
                printWin.document.write('</body></html>');
                printWin.document.close();
            })
            .catch(err => {
                createToast('error', 'Gagal memuat struk: ' + err.message);
            });
    }

    document.addEventListener('DOMContentLoaded', function () {
        attachAddItemListeners();

        document.querySelectorAll('.payment-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.payment-btn').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                selectedPaymentMethod = this.dataset.method;
                document.getElementById('selected_payment_method').value = selectedPaymentMethod;
                updatePaymentUI();
                renderCart();
            });
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && document.getElementById('qrisModal').style.display === 'block') {
                closeQrisModal();
            }
        });
    });
</script>
@endpush
