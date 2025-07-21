<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Daftar Produk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --secondary-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            --success-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            --danger-gradient: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
        }

        body {
            background: var(--primary-gradient);
            min-height: 100vh;
            font-family: 'Inter', 'Segoe UI', system-ui, sans-serif;
            padding: 20px 0;
        }

        .main-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 24px;
            box-shadow: 0 32px 64px rgba(31, 38, 135, 0.37);
            border: 1px solid rgba(255, 255, 255, 0.18);
            padding: 0;
            overflow: hidden;
        }

        .header-section {
            background: var(--primary-gradient);
            padding: 40px;
            color: white;
            text-align: center;
            position: relative;
        }

        .header-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="white" opacity="0.1"/><circle cx="75" cy="75" r="1" fill="white" opacity="0.1"/><circle cx="50" cy="10" r="0.5" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            opacity: 0.1;
        }

        .page-title {
            font-size: 3.5rem;
            font-weight: 800;
            margin: 0;
            position: relative;
            z-index: 1;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
        }

        .page-subtitle {
            font-size: 1.2rem;
            opacity: 0.9;
            margin-top: 10px;
            position: relative;
            z-index: 1;
        }

        .content-section {
            padding: 40px;
        }

        .action-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            flex-wrap: wrap;
            gap: 15px;
        }

        .btn-custom {
            border-radius: 50px;
            padding: 12px 30px;
            font-weight: 600;
            border: none;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-size: 0.9rem;
        }

        .btn-primary-custom {
            background: var(--primary-gradient);
            color: white;
            box-shadow: 0 8px 32px rgba(102, 126, 234, 0.4);
        }

        .btn-primary-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 40px rgba(102, 126, 234, 0.6);
            color: white;
        }

        .btn-success-custom {
            background: var(--success-gradient);
            color: white;
            box-shadow: 0 8px 32px rgba(79, 172, 254, 0.4);
        }

        .btn-danger-custom {
            background: var(--danger-gradient);
            color: white;
            box-shadow: 0 8px 32px rgba(250, 112, 154, 0.4);
        }

        .btn-warning-custom {
            background: linear-gradient(135deg, #f6d365 0%, #fda085 100%);
            color: white;
            box-shadow: 0 8px 32px rgba(246, 211, 101, 0.4);
        }

        .product-card {
            background: white;
            border-radius: 20px;
            border: none;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            overflow: hidden;
            position: relative;
            height: 100%;
            margin-bottom: 20px;
        }

        .product-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 6px;
            background: var(--primary-gradient);
            transform: scaleX(0);
            transition: transform 0.3s ease;
            z-index: 1;
        }

        .product-card:hover::before {
            transform: scaleX(1);
        }

        .product-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.15);
        }

        .card-body {
            padding: 30px;
            position: relative;
        }

        .product-icon {
            position: absolute;
            top: 20px;
            right: 20px;
            width: 60px;
            height: 60px;
            background: var(--primary-gradient);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 24px;
            box-shadow: 0 8px 32px rgba(102, 126, 234, 0.3);
        }

        .product-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #2d3748;
            margin-bottom: 15px;
            padding-right: 80px;
        }

        .product-stock {
            color: #718096;
            line-height: 1.6;
            margin-bottom: 20px;
            font-size: 0.95rem;
        }

        .product-price {
            font-size: 1.8rem;
            font-weight: 800;
            background: var(--success-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 20px;
        }

        .product-actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .btn-action {
            flex: 1;
            min-width: 100px;
            padding: 8px 16px;
            border-radius: 25px;
            font-size: 0.85rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .empty-state {
            text-align: center;
            padding: 80px 20px;
            color: #718096;
        }

        .empty-icon {
            font-size: 5rem;
            opacity: 0.3;
            margin-bottom: 20px;
        }

        .modal-content {
            border-radius: 20px;
            border: none;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
        }

        .modal-header {
            background: var(--primary-gradient);
            color: white;
            border-radius: 20px 20px 0 0;
            border: none;
            padding: 25px 30px;
        }

        .modal-title {
            font-weight: 700;
            font-size: 1.3rem;
        }

        .form-control {
            border-radius: 12px;
            border: 2px solid #e2e8f0;
            padding: 12px 16px;
            transition: all 0.3s ease;
            font-size: 0.95rem;
        }

        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .form-label {
            font-weight: 600;
            color: #4a5568;
            margin-bottom: 8px;
        }

        .loading-spinner {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: white;
            animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .product-card {
            animation: fadeInUp 0.6s ease-out;
        }

        .stats-card {
            background: white;
            border-radius: 16px;
            padding: 25px;
            text-align: center;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.08);
            margin-bottom: 30px;
        }

        .stats-number {
            font-size: 2.5rem;
            font-weight: 800;
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .stats-label {
            color: #718096;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-size: 0.9rem;
        }

        @media (max-width: 768px) {
            .page-title {
                font-size: 2.5rem;
            }

            .action-bar {
                flex-direction: column;
            }

            .btn-custom {
                width: 100%;
            }

            .product-actions {
                flex-direction: column;
            }
        }
    </style>
</head>
<div class="container">
    <div class="main-container">
        <div class="header-section">
            <h1 class="page-title"><i class="fas fa-store me-3"></i>Manajemen Produk</h1>
            <p class="page-subtitle">Kelola produk Anda dengan mudah dan efisien</p>
        </div>

        <div class="content-section">
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="stats-card">
                        <div class="stats-number" id="total-products">{{ count($products) }}</div>
                        <div class="stats-label">Total Produk</div>
                    </div>
                </div>
            </div>

            <div class="action-bar">
                <div>
                    <button class="btn btn-primary-custom btn-custom" data-bs-toggle="modal"
                        data-bs-target="#addProductModal">
                        <i class="fas fa-plus me-2"></i>Tambah Produk
                    </button>
                </div>
                <div>
                    <button id="refresh-products" class="btn btn-success-custom btn-custom">
                        <i class="fas fa-sync-alt me-2"></i>Refresh
                    </button>
                </div>
            </div>

            <div id="loading" class="d-none text-center py-5">
                <div class="loading-spinner mx-auto mb-3"></div>
                <p class="text-muted">Memuat produk...</p>
            </div>

            <div id="product-container" class="row">
                {{-- Loop ini menggunakan data awal dari Controller --}}
                @forelse($products as $product)
                    <div class="col-lg-4 col-md-6 mb-4" data-product-id="{{ $product->id }}">
                        <div class="card product-card h-100">
                            <div class="product-icon"><i class="fas fa-box"></i></div>
                            <div class="card-body d-flex flex-column">
                                <h5 class="product-title">{{ $product->name }}</h5>


                                <p class="product-stock flex-grow-1"><strong>Stok:</strong> {{ $product->stock }}</p>

                                <div class="product-price">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                                <div class="product-actions mt-auto">
                                    <button class="btn btn-warning-custom btn-action edit-product"
                                        data-id="{{ $product->id }}" data-name="{{ $product->name }}"
                                        data-stock="{{ $product->stock }}" {{-- [FIX] Memberikan data-stock --}}
                                        data-price="{{ $product->price }}">
                                        <i class="fas fa-edit me-1"></i>Edit
                                    </button>
                                    <button class="btn btn-danger-custom btn-action delete-product"
                                        data-id="{{ $product->id }}" data-name="{{ $product->name }}">
                                        <i class="fas fa-trash me-1"></i>Hapus
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="empty-state">
                            <i class="fas fa-box-open empty-icon"></i>
                            <h3>Belum ada produk</h3>
                            <p>Mulai dengan menambahkan produk pertama Anda</p>
                            <button class="btn btn-primary-custom btn-custom mt-3" data-bs-toggle="modal"
                                data-bs-target="#addProductModal">
                                <i class="fas fa-plus me-2"></i>Tambah Produk Pertama
                            </button>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addProductModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-plus me-2"></i>Tambah Produk Baru</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="addProductForm">
                    <div class="mb-3">
                        <label for="add-name" class="form-label">Nama Produk</label>
                        <input type="text" class="form-control" id="add-name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="add-stock" class="form-label">Stok</label>
                        <input type="number" class="form-control" id="add-stock" name="stock" min="0"
                            required>
                    </div>
                    <div class="mb-3">
                        <label for="add-price" class="form-label">Harga</label>
                        <input type="number" class="form-control" id="add-price" name="price" min="0"
                            required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary-custom" id="saveProduct">
                    <span class="btn-text"><i class="fas fa-save me-2"></i>Simpan Produk</span>
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editProductModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-edit me-2"></i>Edit Produk</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="editProductForm">
                    <input type="hidden" id="edit-id" name="id">
                    <div class="mb-3">
                        <label for="edit-name" class="form-label">Nama Produk</label>
                        <input type="text" class="form-control" id="edit-name" name="name" required>
                    </div>
                    {{-- [FIX] Form input untuk stok --}}
                    <div class="mb-3">
                        <label for="edit-stock" class="form-label">Stok</label>
                        <input type="number" class="form-control" id="edit-stock" name="stock" min="0"
                            required>
                    </div>
                    <div class="mb-3">
                        <label for="edit-price" class="form-label">Harga</label>
                        <input type="number" class="form-control" id="edit-price" name="price" min="0"
                            required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-warning-custom" id="updateProduct">
                    <span class="btn-text"><i class="fas fa-save me-2"></i>Update Produk</span>
                </button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Get CSRF token
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // Elements
    const productContainer = document.getElementById('product-container');
    const loadingIndicator = document.getElementById('loading');
    const refreshBtn = document.getElementById('refresh-products');
    const addProductModal = new bootstrap.Modal(document.getElementById('addProductModal'));
    const editProductModal = new bootstrap.Modal(document.getElementById('editProductModal'));
    const totalProductsEl = document.getElementById('total-products');

    // Toast notification
    function showToast(message, type = 'success') {
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });

        Toast.fire({
            icon: type,
            title: message
        });
    }

    // Loading state for buttons
    function setButtonLoading(button, loading = true) {
        const btnText = button.querySelector('.btn-text') || button;
        if (!button.dataset.originalText) {
            button.dataset.originalText = btnText.innerHTML;
        }

        if (loading) {
            button.disabled = true;
            btnText.innerHTML = `<span class="loading-spinner me-2"></span>Loading...`;
        } else {
            button.disabled = false;
            btnText.innerHTML = button.dataset.originalText;
        }
    }

    // Format currency
    function formatCurrency(amount) {
        return new Intl.NumberFormat('id-ID').format(amount);
    }

    // Refresh products function
    async function refreshProducts(event) {
        try {
            if (event) setButtonLoading(refreshBtn);
            loadingIndicator.classList.remove('d-none');
            productContainer.innerHTML = ''; // Kosongkan dulu

            const response = await fetch('{{ route('products.index') }}', {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            });

            if (!response.ok) throw new Error('Network response was not ok');

            const data = await response.json();
            updateProductDisplay(data.products);
            updateStats(data.products.length);

            if (event && event.target.id === 'refresh-products') {
                showToast('Produk berhasil di-refresh');
            }

        } catch (error) {
            console.error('Error fetching products:', error);
            showToast('Gagal memuat produk', 'error');
        } finally {
            loadingIndicator.classList.add('d-none');
            if (event) setButtonLoading(refreshBtn, false);
        }
    }

    // Update product display
    function updateProductDisplay(products) {
        if (!products || products.length === 0) {
            productContainer.innerHTML = `
                <div class="col-12">
                    <div class="empty-state">
                        <i class="fas fa-box-open empty-icon"></i>
                        <h3>Belum ada produk</h3>
                        <p>Mulai dengan menambahkan produk pertama Anda</p>
                        <button class="btn btn-primary-custom btn-custom mt-3" data-bs-toggle="modal" data-bs-target="#addProductModal">
                            <i class="fas fa-plus me-2"></i>Tambah Produk Pertama
                        </button>
                    </div>
                </div>
            `;
            return;
        }

        let html = '';
        products.forEach(product => {
            html += `
                <div class="col-lg-4 col-md-6 mb-4" data-product-id="${product.id}" style="animation: fadeInUp 0.5s ease-out forwards;">
                    <div class="card product-card h-100">
                        <div class="product-icon">
                            <i class="fas fa-box"></i>
                        </div>
                        <div class="card-body d-flex flex-column">
                            <h5 class="product-title">${product.name}</h5>
                            
                            {{-- [FIX] Memperbarui tampilan dengan data stock --}}
                            <p class="product-stock flex-grow-1"><strong>Stok:</strong> ${product.stock}</p>
                            
                            <div class="product-price">Rp ${formatCurrency(product.price)}</div>
                            <div class="product-actions mt-auto">
                                <button class="btn btn-warning-custom btn-action edit-product" 
                                        data-id="${product.id}"
                                        data-name="${product.name}"
                                        data-stock="${product.stock}"
                                        data-price="${product.price}">
                                    <i class="fas fa-edit me-1"></i>Edit
                                </button>
                                <button class="btn btn-danger-custom btn-action delete-product" 
                                        data-id="${product.id}"
                                        data-name="${product.name}">
                                    <i class="fas fa-trash me-1"></i>Hapus
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        });

        productContainer.innerHTML = html;
    }

    // Update stats
    function updateStats(count) {
        totalProductsEl.textContent = count;
    }


    // === EVENT LISTENERS ===

    // Refresh Button
    refreshBtn.addEventListener('click', refreshProducts);

    // Add product
    document.getElementById('saveProduct').addEventListener('click', async function() {
        const form = document.getElementById('addProductForm');
        const formData = new FormData(form);
        const button = this;

        try {
            setButtonLoading(button);

            const response = await fetch('{{ route('products.store') }}', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: formData
            });

            const data = await response.json();

            if (response.ok && data.success) {
                addProductModal.hide();
                form.reset();
                await refreshProducts();
                showToast(data.message);
            } else {
                throw new Error(data.message || 'Terjadi kesalahan');
            }

        } catch (error) {
            console.error('Error adding product:', error);
            showToast(error.message, 'error');
        } finally {
            setButtonLoading(button, false);
        }
    });

    // Event delegation for Edit, and Delete buttons
    document.addEventListener('click', function(e) {
        // Edit product
        if (e.target.closest('.edit-product')) {
            const button = e.target.closest('.edit-product');

            // [FIX] Mengambil data-stock dari tombol
            const id = button.dataset.id;
            const name = button.dataset.name;
            const stock = button.dataset.stock;
            const price = button.dataset.price;

            // [FIX] Mengisi form edit dengan data yang benar
            document.getElementById('edit-id').value = id;
            document.getElementById('edit-name').value = name;
            document.getElementById('edit-stock').value = stock;

            document.getElementById('edit-price').value = price;

            editProductModal.show();
        }

        // Delete product
        if (e.target.closest('.delete-product')) {
            const button = e.target.closest('.delete-product');
            const id = button.dataset.id;
            const name = button.dataset.name;

            Swal.fire({
                title: `Yakin hapus produk "${name}"?`,
                text: "Anda tidak akan dapat mengembalikan tindakan ini!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#764ba2',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then(async (result) => {
                if (result.isConfirmed) {
                    try {
                        const response = await fetch(`/products/${id}`, {
                            method: 'DELETE',
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json'
                            }
                        });

                        const data = await response.json();
                        if (response.ok && data.success) {
                            await refreshProducts();
                            showToast(data.message, 'success');
                        } else {
                            throw new Error(data.message || 'Gagal menghapus produk.');
                        }
                    } catch (error) {
                        showToast(error.message, 'error');
                    }
                }
            })
        }
    });

    // Update product
    document.getElementById('updateProduct').addEventListener('click', async function() {
        const form = document.getElementById('editProductForm');
        const formData = new FormData(form);
        const id = document.getElementById('edit-id').value;
        const button = this;

        formData.append('_method', 'PUT');

        try {
            setButtonLoading(button);

            const response = await fetch(`/products/${id}`, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: formData
            });

            const data = await response.json();

            if (response.ok && data.success) {
                editProductModal.hide();
                await refreshProducts();
                showToast(data.message);
            } else {
                throw new Error(data.message || 'Terjadi kesalahan');
            }

        } catch (error) {
            console.error('Error updating product:', error);
            showToast(error.message, 'error');
        } finally {
            setButtonLoading(button, false);
        }
    });
</script>

</html>
