@extends('layouts.home')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Kasir - POS ERAMEDIA</h1>

    <div class="row">
        <!-- Daftar Produk -->
        <div class="col-md-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Pilih Produk</h6>
                    <button id="resetSearch" class="btn btn-outline-secondary btn-sm" style="display:none;">↺ Reset</button>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <input type="text" id="productSearch" class="form-control" placeholder="Cari produk (min. 2 karakter)...">
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Stok</th>
                                    <th>Harga</th>
                                    <th>Satuan</th>
                                    <th>Aksi</th>
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
                                        @if($product->stock <= 0) class="out-of-stock" @endif
                                    >
                                        <td>{{ $product->name }}</td>
                                        <td>{{ $product->stock }}</td>
                                        <td>Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                                        <td>{{ optional($product->unit)->name ?? 'pcs' }}</td>
                                        <td>
                                            @if($product->stock > 0)
                                                <div class="input-group input-group-sm" style="width: 150px;">
                                                    <input 
                                                        type="number" 
                                                        class="form-control form-control-sm qty-add" 
                                                        value="1" 
                                                        min="1" 
                                                        max="{{ $product->stock }}" 
                                                        style="width: 60px; text-align: center;"
                                                        onkeypress="return event.charCode >= 48 && event.charCode <= 57"
                                                    >
                                                    <button type="button" class="btn btn-success btn-sm btn-add-item">+</button>
                                                </div>
                                            @else
                                                <span class="text-danger fw-bold">Habis</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="d-flex justify-content-end mt-3" id="paginationContainer">
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

                        <!-- Hidden input untuk metode pembayaran -->
                        <input type="hidden" id="selected_payment_method" name="payment_method" value="tunai">

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

                        <!-- Diskon Persen -->
                        <div class="form-group mt-3">
                            <label for="discount_percent_display">Diskon (%)</label>
                            <div class="input-group">
                                <input 
                                    type="number" 
                                    id="discount_percent_display" 
                                    class="form-control" 
                                    min="0" 
                                    max="100"
                                    step="0.01"
                                    value=""
                                    placeholder="0.00"
                                >
                                <div class="input-group-append">
                                    <span class="input-group-text">%</span>
                                </div>
                            </div>
                            <small class="form-text text-muted">Contoh: 10 untuk 10%</small>
                        </div>

                        <!-- Hidden input diskon (rupiah) -->
                        <input type="hidden" id="discount" name="discount" value="0">

                        <div id="dynamicInputs" class="d-none"></div>

                        <hr>

                        <div id="paymentSection" style="display: none;">
                            <!-- Input Bayar (akan disembunyikan saat QRIS) -->
                            <div class="form-group" id="paidAmountGroup">
                                <label for="paid_amount">Jumlah Bayar (Rp)</label>
                                <input 
                                    type="number" 
                                    id="paid_amount" 
                                    name="paid_amount" 
                                    class="form-control" 
                                    min="0"
                                >
                            </div>

                            <!-- Metode Pembayaran: BUTTON -->
                            <div class="form-group mt-3">
                                <label class="font-weight-bold">Metode Pembayaran:</label><br>
                                <div class="btn-group w-100" role="group">
                                    <button type="button" class="btn btn-outline-primary payment-btn active" data-method="tunai">
                                        Tunai
                                    </button>
                                    <button type="button" class="btn btn-outline-success payment-btn" data-method="qris">
                                        QRIS
                                    </button>
                                </div>
                            </div>

                            <div class="row mt-2">
                                <div class="col-6">
                                    <label>Subtotal: <span id="subtotalAmount">Rp 0</span></label>
                                </div>
                                <div class="col-6 text-right">
                                    <label>Diskon: <span id="discountAmount">- Rp 0</span></label>
                                </div>
                            </div>
                            <div class="row mt-1">
                                <div class="col-6">
                                    <strong>Total: <span id="totalAmount">Rp 0</span></strong>
                                </div>
                                <div class="col-6 text-right">
                                    <label>Kembalian: <span id="changeAmount">Rp 0</span></label>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary btn-block mt-3" id="submitBtn" disabled>
                                Simpan Transaksi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal QRIS -->
<div 
    class="modal"
    id="qrisModal"
    role="dialog"
    aria-modal="true"
    aria-labelledby="qrisModalLabel"
    aria-hidden="true"
>
    <div style="background: white; border-radius: 8px; max-width: 400px; width: 90%; margin: auto; padding: 1.5rem;">
        <div class="d-flex justify-content-between align-items-start mb-3">
            <h5 id="qrisModalLabel">Scan QRIS untuk Bayar</h5>
            <button type="button" onclick="closeQrisModal()" style="font-size:1.5rem; background:none; border:none; cursor:pointer;">&times;</button>
        </div>
        <div class="text-center">
            <p>Scan kode QR berikut menggunakan aplikasi e-wallet Anda.</p>
            <img src="{{ asset('img/qris.jpeg') }}" alt="QRIS" class="img-fluid mb-3" style="max-width: 250px; height: auto;">
            <p><small>Total Tagihan: <span id="qrisTotal">Rp 0</span></small></p>
        </div>
        <div class="d-flex justify-content-end gap-2 mt-3">
            <button type="button" class="btn btn-secondary" onclick="cancelQrisPayment()">Batal</button>
            <button type="button" class="btn btn-success" onclick="confirmQrisPayment()">Oke, Sudah Dibayar</button>
        </div>
    </div>
</div>

@push('styles')
<style>
    .out-of-stock {
        background-color: #ffebee !important;
        opacity: 0.8;
    }
    .out-of-stock td {
        color: #f44336 !important;
    }
    .search-loading {
        text-align: center;
        padding: 1rem;
        color: #6c757d;
    }

    #qrisModal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.5);
        z-index: 1050;
        justify-content: center;
        align-items: center;
    }

    .modal-content {
        background: white;
        border-radius: 12px;
        max-width: 500px;
        width: 90%;
        padding: 2rem;
        box-shadow: 0 10px 25px rgba(0,0,0,0.3);
    }
    .close {
        font-size: 1.5rem;
        cursor: pointer;
    }
</style>
@endpush

@push('scripts')
<script>
    let selectedPaymentMethod = 'tunai';
    let cart = [];
    let isSearching = false;

    function attachAddItemListeners() {
        document.querySelectorAll('.btn-add-item').forEach(btn => {
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

    // Event listeners keranjang
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

    // AJAX SEARCH
    const searchInput = document.getElementById('productSearch');
    const resetBtn = document.getElementById('resetSearch');
    const paginationContainer = document.getElementById('paginationContainer');

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
            if (paginationContainer) paginationContainer.style.display = 'none';

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

    // Submit handler
    document.getElementById('transactionForm').addEventListener('submit', async function (e) {
        e.preventDefault();

        if (cart.length === 0) {
            createToast('error', 'Minimal tambahkan satu produk!');
            return;
        }

        if (selectedPaymentMethod === 'qris') {
            console.log('Memproses pembayaran QRIS...');
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
        console.log('Memproses pembayaran Tunai...');
        const paid = parseFloat(document.getElementById('paid_amount')?.value) || 0;
        const totalText = document.getElementById('totalAmount').textContent;
        const total = parseFloat(totalText.replace(/[^0-9]/g, '')) || 0;

        if (paid < total) {
            createToast('error', 'Jumlah bayar tidak mencukupi!');
            return;
        }

        await sendTransactionToServer();
    });

    // ✅ FUNGSI BARU: KELOLA MODAL QRIS DENGAN ARIA
    function showQrisModal() {
        console.log("Menampilkan modal QRIS...");
        const modal = document.getElementById('qrisModal');
        modal.style.display = 'flex';
        modal.setAttribute('aria-hidden', 'false');
        const firstButton = modal.querySelector('button');
        if (firstButton) firstButton.focus();
        document.body.style.overflow = 'hidden';
        console.log('✅ Modal seharusnya terlihat sekarang');
    }

    function closeQrisModal() {
        const modal = document.getElementById('qrisModal');
        modal.style.display = 'none';
        modal.setAttribute('aria-hidden', 'true');
        document.body.style.overflow = '';
    }

    // Kirim ke server
    async function sendTransactionToServer() {
          const form = document.getElementById('transactionForm');
        const submitBtn = document.getElementById('submitBtn');
        const originalText = submitBtn.innerHTML;

        // 🔑 ISI paid_amount OTOMATIS JIKA QRIS
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

    // Modal QRIS actions
    function cancelQrisPayment() {
        closeQrisModal();
        createToast('info', 'Pembayaran QRIS dibatalkan.');
    }

    function confirmQrisPayment() {
        closeQrisModal();
        createToast('success', 'Pembayaran QRIS dikonfirmasi!');
        sendTransactionToServer();
    }

    // Modal Receipt
    function showModal(content) {
        let modal = document.getElementById('receiptModal');
        if (!modal) {
            modal = document.createElement('div');
            modal.id = 'receiptModal';
            modal.innerHTML = `
                <div class="modal fade show" style="display:block; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:1050;" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered" style="max-width:400px;">
                        <div class="modal-content">
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

    // Global: load print preview and auto-print
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


    // Init
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

        // Escape key support
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && document.getElementById('qrisModal').style.display !== 'none') {
                closeQrisModal();
            }
        });


    });
</script>
@endpush
@endsection
