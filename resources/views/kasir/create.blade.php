<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Transaksi Kasir</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .main-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 25px 45px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 2rem;
            margin: 2rem auto;
            max-width: 900px;
            animation: slideUp 0.6s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .page-title {
            text-align: center;
            color: #4a5568;
            font-weight: 700;
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
            background: linear-gradient(135deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .page-subtitle {
            text-align: center;
            color: #718096;
            margin-bottom: 2rem;
            font-size: 1.1rem;
        }

        .item-card {
            background: linear-gradient(145deg, #ffffff, #f8fafc);
            border: 2px solid #e2e8f0;
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .item-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #667eea, #764ba2);
        }

        .item-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            border-color: #667eea;
        }

        .item-number {
            position: absolute;
            top: 15px;
            right: 20px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            width: 35px;
            height: 35px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 1.1rem;
        }

        .form-label {
            font-weight: 600;
            color: #4a5568;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .form-control, .form-select {
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
            font-size: 1rem;
            background: rgba(255, 255, 255, 0.8);
        }

        .form-control:focus, .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
            transform: translateY(-1px);
        }

        .btn {
            border-radius: 10px;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
            border: none;
            position: relative;
            overflow: hidden;
        }

        .btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s ease;
        }

        .btn:hover::before {
            left: 100%;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea, #764ba2);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 35px rgba(102, 126, 234, 0.4);
        }

        .btn-success {
            background: linear-gradient(135deg, #48bb78, #38a169);
            box-shadow: 0 8px 25px rgba(72, 187, 120, 0.3);
            color: white;
        }

        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 35px rgba(72, 187, 120, 0.4);
        }

        .btn-danger {
            background: linear-gradient(135deg, #f56565, #e53e3e);
            box-shadow: 0 6px 20px rgba(245, 101, 101, 0.3);
            color: white;
            padding: 0.5rem 1rem;
            font-size: 0.9rem;
        }

        .btn-danger:hover {
            transform: translateY(-1px);
            box-shadow: 0 10px 25px rgba(245, 101, 101, 0.4);
        }

        .add-item-section {
            text-align: center;
            margin: 2rem 0;
            padding: 1.5rem;
            background: linear-gradient(145deg, #f8fafc, #edf2f7);
            border-radius: 15px;
            border: 2px dashed #cbd5e0;
        }

        .submit-section {
            text-align: center;
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 2px solid #e2e8f0;
        }

        .icon {
            width: 20px;
            text-align: center;
        }

        .total-section {
            background: linear-gradient(135deg, #4299e1, #3182ce);
            color: white;
            padding: 1.5rem;
            border-radius: 15px;
            margin: 1.5rem 0;
            text-align: center;
        }

        .total-amount {
            font-size: 2rem;
            font-weight: bold;
            margin: 0.5rem 0;
        }

        .alert {
            border-radius: 10px;
            border: none;
            padding: 1rem 1.5rem;
            margin-bottom: 1.5rem;
        }

        @media (max-width: 768px) {
            .main-container {
                margin: 1rem;
                padding: 1.5rem;
            }
            
            .page-title {
                font-size: 2rem;
            }
            
            .item-card {
                padding: 1rem;
            }
        }
    </style>
</head>
<body>
<div class="main-container">
    <h1 class="page-title">
        <i class="fas fa-cash-register"></i> Transaksi Baru
    </h1>
    <p class="page-subtitle">Kelola transaksi penjualan dengan mudah dan cepat</p>

    <!-- Alert untuk pesan error -->
    @if ($errors->any())
        <div class="alert alert-danger">
            <strong><i class="fas fa-exclamation-triangle"></i> Terjadi kesalahan:</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Alert untuk pesan sukses -->
    @if (session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('kasir.store') }}" method="POST">
    @csrf
    <div id="items">
        <div class="item-card item">
            <div class="item-number">1</div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">
                        <i class="fas fa-box icon"></i>
                        Pilih Produk
                    </label>
                    <select name="items[0][product_id]" class="form-select product-select" required>
                        <option value="">-- Pilih Produk --</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}"
                                     data-price="{{ $product->price }}">
                                {{ $product->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                 <div class="col-md-3 mb-3">
                        <label class="form-label">
                            <i class="fas fa-layer-group icon"></i>
                            Unit
                        </label>
                            <select name="items[0][unit]" class="form-select unit-select" required>
                                <option value="">-- Pilih Unit --</option>
                                @foreach ($units as $unit)
                                <option value="{{$unit->name}}" data-multiplier="1">{{$unit->name}}</option>
                                @endforeach  
                            </select>
                        <div class="unit-info" style="display: none;">
                            <span class="unit-description"></span>
                        </div>
                    </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">
                        <i class="fas fa-sort-numeric-up icon"></i>
                        Jumlah
                    </label>
                    <input type="number" name="items[0][quantity]" class="form-control quantity-input" min="1" value="1" required>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">
                        <i class="fas fa-cog icon"></i>
                        Aksi
                    </label>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-danger remove-item flex-fill">
                            <i class="fas fa-trash-alt"></i> Hapus
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="add-item-section">
        <button type="button" id="add-item" class="btn btn-success btn-lg">
            <i class="fas fa-plus-circle"></i> Tambah Produk Baru
        </button>
    </div>

    <div class="total-section">
        <h4><i class="fas fa-calculator"></i> Total Transaksi</h4>
        <div class="total-amount" id="total-amount">Rp 0</div>
        <small>*Total akan dihitung otomatis</small>
    </div>

    <!-- Payment Section -->
       <div class="payment-section mt-4">
        <h4><i class="fas fa-money-bill-wave"></i> Pembayaran</h4>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">
                    <i class="fas fa-money-bill icon"></i>
                    Jumlah Bayar
                </label>
                <input type="number" 
                       name="paid_amount" 
                       id="paid-amount" 
                       class="form-control" 
                       min="0" 
                       step="0.01" 
                       placeholder="Masukkan jumlah bayar"
                       required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">
                    <i class="fas fa-coins icon"></i>
                    Kembalian
                </label>
                <input type="text" 
                       id="change-amount" 
                       class="form-control" 
                       readonly 
                       placeholder="Kembalian akan dihitung otomatis">
            </div>
        </div>
    </div>


    <div class="submit-section">
        <button type="submit" class="btn btn-primary btn-lg" id="submit-btn" disabled>
            <i class="fas fa-save"></i> Simpan Transaksi
        </button>
    </div>
    <div class="mt-3" id="debug-info" style="display: none;">
            <h6>Debug Info:</h6>
            <pre id="debug-data" style="background: #f8f9fa; padding: 10px; border-radius: 5px; font-size: 12px;"></pre>
        </div>
</form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let itemCount = 1;
    let currentTotal = 0;
    const unitPrices = {
    'Pcs': 0, // Harga pcs diambil dari data-price produk
    'Pack': 50000,
    'Box': 200000,
    'Lusin': 30000,
    'Rim': 55000,
    'Kotak': 10000 // Untuk semua jenis kotak (Spidol, TipX, Pensil, dll)
    };
    // Function to calculate total
    function calculateTotal() {
        let total = 0;
    
    document.querySelectorAll('.item').forEach(function(item) {
        const productSelect = item.querySelector('.product-select');
        const unitSelect = item.querySelector('.unit-select');
        const quantityInput = item.querySelector('.quantity-input');
        
        if (productSelect && productSelect.value && 
            unitSelect && unitSelect.value && 
            quantityInput && quantityInput.value) {
            
            const selectedProduct = productSelect.options[productSelect.selectedIndex];
            const selectedUnit = unitSelect.value;
            const quantity = parseInt(quantityInput.value) || 0;
            
            let itemTotal = 0;
            
            if (selectedUnit === 'Pcs') {
                // Untuk pcs, gunakan harga produk dikali quantity
                const productPrice = parseFloat(selectedProduct.getAttribute('data-price') || 0);
                itemTotal = productPrice * quantity;
            } else {
                // Untuk unit lainnya, gunakan harga unit dikali quantity
                const unitPrice = getUnitPrice(selectedUnit);
                itemTotal = unitPrice * quantity;
            }
            
            total += itemTotal;
            
            // Update tampilan harga item jika ada
            const itemTotalElement = item.querySelector('.item-total');
            if (itemTotalElement) {
                itemTotalElement.textContent = 'Rp ' + itemTotal.toLocaleString('id-ID');
            }
        }
    });
    
    currentTotal = total;
    document.getElementById('total-amount').textContent = 'Rp ' + total.toLocaleString('id-ID');
    calculateChange();
    return total;
    }

    function getUnitPrice(unit) {
    // Untuk semua jenis kotak, return harga kotak
    if (unit.includes('Kotak') || unit.startsWith('Kotak')) {
        return unitPrices['Kotak'];
    }
    
    return unitPrices[unit] || 0;
    }

    // Function to calculate change
    function calculateChange() {
        const paidAmountInput = document.getElementById('paid-amount');
        const changeInput = document.getElementById('change-amount');
        const submitBtn = document.getElementById('submit-btn');
        
        if (!paidAmountInput || !changeInput || !submitBtn) return;
        
        const paidAmount = parseFloat(paidAmountInput.value) || 0;
        const change = paidAmount - currentTotal;
        
        // Update change display
        if (paidAmount > 0 && currentTotal > 0) {
            changeInput.value = 'Rp ' + change.toLocaleString('id-ID');
            
            // Color coding for change
            if (change >= 0) {
                changeInput.style.color = 'green';
                changeInput.style.fontWeight = 'bold';
            } else {
                changeInput.style.color = 'red';
                changeInput.style.fontWeight = 'bold';
            }
            
            // Enable/disable submit button
            if (paidAmount >= currentTotal && currentTotal > 0) {
                submitBtn.disabled = false;
                submitBtn.classList.remove('btn-secondary');
                submitBtn.classList.add('btn-primary');
            } else {
                submitBtn.disabled = true;
                submitBtn.classList.remove('btn-primary');
                submitBtn.classList.add('btn-secondary');
            }
        } else {
            changeInput.value = '';
            changeInput.style.color = '';
            changeInput.style.fontWeight = '';
            submitBtn.disabled = true;
            submitBtn.classList.remove('btn-primary');
            submitBtn.classList.add('btn-secondary');
        }
    }
    
    // Event listeners for product selection and quantity changes
    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('product-select') || 
            e.target.classList.contains('quantity-input')) {
            calculateTotal(); // Small delay to ensure DOM is updated
        }
    });
    
    // Event listener for payment amount input
    document.addEventListener('input', function(e) {
        if (e.target.id === 'paid-amount') {
            calculateChange();
        }
    });
    
    // Also listen for keyup events on payment input for better responsiveness
    document.addEventListener('keyup', function(e) {
        if (e.target.id === 'paid-amount') {
            calculateChange();
        }
    });
    
    
    // Add new item
    document.getElementById('add-item').addEventListener('click', function() {
        const itemsContainer = document.getElementById('items');
        const newItem = document.querySelector('.item').cloneNode(true);
        
        // Update item number and names
        newItem.querySelector('.item-number').textContent = itemCount + 1;
        newItem.querySelector('select').name = `items[${itemCount}][product_id]`;
        newItem.querySelector('input[type="number"]').name = `items[${itemCount}][quantity]`;
        
        // Reset values
        newItem.querySelector('select').value = '';
        newItem.querySelector('input[type="number"]').value = '1';
        
        itemsContainer.appendChild(newItem);
        itemCount++;
        calculateTotal();
    });
    
    // Remove item
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-item') || e.target.closest('.remove-item')) {
            const items = document.querySelectorAll('.item');
            if (items.length > 1) {
                e.target.closest('.item').remove();
                
                // Update item numbers and names
                document.querySelectorAll('.item').forEach(function(item, index) {
                    item.querySelector('.item-number').textContent = index + 1;
                    item.querySelector('select').name = `items[${index}][product_id]`;
                    item.querySelector('input[type="number"]').name = `items[${index}][quantity]`;
                });
                
                itemCount = items.length - 1;
                calculateTotal();
            }
        }
    });

    //listener qauntity
    document.addEventListener('input', function(e) {
    if (e.target.classList.contains('quantity-input')) {
        calculateTotal();
    }
    });


    // unit change
    document.addEventListener('change', function(e) {
    if (e.target.classList.contains('unit-select')) {
        const item = e.target.closest('.item');
        const unitInfo = item.querySelector('.unit-info');
        const unitDescription = item.querySelector('.unit-description');
        
        if (e.target.value) {
            const selectedUnit = e.target.value;
            const unitPrice = getUnitPrice(selectedUnit);
            
            if (selectedUnit === 'Pcs') {
                unitDescription.textContent = 'Harga berdasarkan harga produk per pcs';
            } else {
                unitDescription.textContent = `Harga: Rp ${unitPrice.toLocaleString('id-ID')} per ${selectedUnit}`;
            }
            
            unitInfo.style.display = 'block';
        } else {
            unitInfo.style.display = 'none';
        }
        
        // Recalculate total
        calculateTotal();
    }
});
    // Quick payment buttons (optional enhancement)
    function addQuickPaymentButtons() {
        const paymentSection = document.querySelector('.payment-section');
        if (paymentSection && currentTotal > 0) {
            // Remove existing quick payment buttons
            const existingButtons = paymentSection.querySelector('.quick-payment-buttons');
            if (existingButtons) {
                existingButtons.remove();
            }
            
            // Create new quick payment buttons
            const quickPaymentDiv = document.createElement('div');
            quickPaymentDiv.className = 'quick-payment-buttons mt-2 mb-3';
            quickPaymentDiv.innerHTML = `
                <small class="text-muted">Pembayaran Cepat:</small><br>
                <button type="button" class="btn btn-outline-primary btn-sm me-2" onclick="setPaymentAmount(${currentTotal})">
                    Pas (${currentTotal.toLocaleString('id-ID')})
                </button>
                <button type="button" class="btn btn-outline-primary btn-sm me-2" onclick="setPaymentAmount(${Math.ceil(currentTotal / 50000) * 50000})">
                    ${Math.ceil(currentTotal / 50000) * 50000}
                </button>
                <button type="button" class="btn btn-outline-primary btn-sm" onclick="setPaymentAmount(${Math.ceil(currentTotal / 100000) * 100000})">
                    ${Math.ceil(currentTotal / 100000) * 100000}
                </button>
            `;
            
            paymentSection.querySelector('.row').parentNode.insertBefore(quickPaymentDiv, paymentSection.querySelector('.row').nextSibling);
        }
    }
    
    // Function to set payment amount (for quick payment buttons)
    window.setPaymentAmount = function(amount) {
        document.getElementById('paid-amount').value = amount;
        calculateChange();
    };
    
    // Enhanced calculateTotal that also updates quick payment buttons
    const originalCalculateTotal = calculateTotal;
    calculateTotal = function() {
        const result = originalCalculateTotal();
        if (result > 0) {
            setTimeout(addQuickPaymentButtons, 100);
        }
        return result;
    };
    
    // Initial calculation
    setTimeout(calculateTotal, 500);
});
</script>
</body>
</html>