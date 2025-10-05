<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Daftar Transaksi - ERAMEDIA POS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
    </style>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#3B82F6',
                        secondary: '#64748B'
                    }
                }
            }
        }
    </script>
</head>

<body class="bg-gray-50 min-h-screen">
    <!-- Navigation -->
    <nav class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <!-- Tombol Kembali ke Dashboard -->
                <div class="flex items-center space-x-4">
                    <a href="{{ route('dashboard') }}"
                        class="inline-flex items-center text-gray-700 hover:text-gray-900 transition-colors duration-200">
                        <i class="fas fa-arrow-left mr-2"></i>
                        <span class="font-medium">Kembali ke Dashboard</span>
                    </a>
                </div>

                <!-- Tombol Tambah Transaksi -->
                <div class="flex items-center space-x-4">
                    <button onclick="window.location.href='{{ route('kasir.index') }}'"
                        class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition-colors">
                        <i class="fas fa-plus mr-2"></i>Tambah Transaksi
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <!-- Alert Messages -->
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        <!-- Search & Filter Section -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <div class="flex flex-col md:flex-row gap-4 items-end">
                <div class="flex-1">
                    <div class="relative">
                        <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        <input type="text" id="search-input" placeholder="Search by ID or amount..."
                            class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                    </div>
                </div>

                <div>
                    <label for="filter-date" class="text-sm text-gray-700">Filter by Date:</label>
                    <input type="date" id="filter-date"
                        class="w-full py-2 px-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                </div>

                <div class="flex gap-2">
                    <button id="search-reset"
                        class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                        <i class="fas fa-refresh mr-2"></i>Reset
                    </button>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Transactions Table -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-sm">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">Transactions</h2>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        ID</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Total Price</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Paid Amount</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Change</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Date</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Actions</th>
                                </tr>
                            </thead>
                            <tbody id="transaction-results" class="bg-white divide-y divide-gray-200">
                                @include('transaction.partials.list')
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if ($transactions->hasPages())
                        <div class="px-6 py-4 border-t border-gray-200">
                            <div class="flex items-center justify-between">
                                <div class="text-sm text-gray-700">
                                    Showing {{ $transactions->firstItem() ?? 0 }} to
                                    {{ $transactions->lastItem() ?? 0 }} of {{ $transactions->total() }} results
                                </div>
                                <div class="flex space-x-2">
                                    {{ $transactions->appends(request()->query())->links('pagination::tailwind') }}
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Transaction Details Panel -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-sm sticky top-6">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Transaction Details</h3>
                        <p class="text-sm text-gray-500 mt-1" id="selectedTransactionInfo">Select a transaction to view
                            details</p>
                    </div>

                    <div class="p-6" id="transactionDetailsContent">
                        <div class="text-center text-gray-500 py-8">
                            <i class="fas fa-receipt text-4xl mb-4 text-gray-300"></i>
                            <p>Click on a transaction to view its details</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Create Transaction Modal -->
    <div id="createModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Create New Transaction</h3>

                <form method="POST" action="" id="createTransactionForm">
                    @csrf
                    <!-- Products Section -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Products</label>
                        <div id="productItems">
                            <div class="product-item flex gap-4 mb-3 items-center">
                                <select name="items[0][product_id]"
                                    class="flex-1 border border-gray-300 rounded-lg px-3 py-2 product-select" required>
                                    <option value="">Select Product</option>
                                    @foreach (\App\Models\Product::all() as $product)
                                        <option value="{{ $product->id }}" data-price="{{ $product->price }}">
                                            {{ $product->name }} - {{ $product->formatted_price }}
                                        </option>
                                    @endforeach
                                </select>
                                <input type="number" name="items[0][quantity]" placeholder="Qty" min="1"
                                    class="w-20 border border-gray-300 rounded-lg px-3 py-2 quantity-input" required>
                                <span class="w-32 text-sm text-gray-600 subtotal-display">Rp 0</span>
                            </div>
                        </div>
                        <button type="button" onclick="addProductItem()"
                            class="text-primary hover:text-blue-600 text-sm">
                            <i class="fas fa-plus mr-1"></i>Add Product
                        </button>
                    </div>

                    <!-- Total Section -->
                    <div class="mb-4 bg-gray-50 p-4 rounded-lg">
                        <div class="flex justify-between items-center mb-2">
                            <span class="font-medium">Total:</span>
                            <span id="totalAmount" class="font-bold text-lg">Rp 0</span>
                        </div>
                    </div>

                    <!-- Payment Section -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Paid Amount</label>
                        <input type="number" name="paid_amount" id="paidAmount" step="0.01" min="0"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2" placeholder="Enter paid amount"
                            required>
                        <div id="changeAmount" class="text-sm mt-2 hidden">
                            <span class="font-medium">Change: </span>
                            <span id="changeValue" class="font-bold text-green-600">Rp 0</span>
                        </div>
                        <div id="insufficientPayment" class="text-sm mt-2 text-red-600 hidden">
                            <i class="fas fa-exclamation-triangle mr-1"></i>
                            Payment amount is insufficient
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="closeCreateModal()"
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors">
                            Cancel
                        </button>
                        <button type="submit" id="submitBtn"
                            class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-blue-600 transition-colors">
                            Create Transaction
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div id="deleteModal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white rounded-lg shadow-lg max-w-sm w-full p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Konfirmasi Hapus</h3>
            <p class="text-gray-600 mb-6">Apakah Anda yakin ingin menghapus transaksi ini? Tindakan ini tidak bisa
                dibatalkan.</p>
            <div class="flex justify-end space-x-3">
                <button type="button" id="cancelDelete"
                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded hover:bg-gray-300">
                    Batal
                </button>
                <button type="button" id="confirmDelete"
                    class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded hover:bg-red-700">
                    Hapus
                </button>
            </div>
        </div>
    </div>
</body>
<script>
    // Variabel untuk menyimpan form yang akan dihapus
    let deleteForm = null;

    // Buka modal konfirmasi
    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', function() {
            deleteForm = this.closest('form'); // Simpan form yang akan dihapus
            document.getElementById('deleteModal').classList.remove('hidden');
        });
    });

    // Tutup modal
    document.getElementById('cancelDelete').addEventListener('click', function() {
        document.getElementById('deleteModal').classList.add('hidden');
        deleteForm = null;
    });

    // Tutup modal jika klik di luar
    document.getElementById('deleteModal').addEventListener('click', function(e) {
        if (e.target === this) {
            this.classList.add('hidden');
            deleteForm = null;
        }
    });

    // Jalankan penghapusan
    document.getElementById('confirmDelete').addEventListener('click', function() {
        if (deleteForm) {
            deleteForm.submit(); // Submit form asli
        }
    });


    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('search-input');
        const dateInput = document.getElementById('filter-date');
        const resetButton = document.getElementById('search-reset');
        const resultsContainer = document.getElementById('transaction-results');

        let debounceTimeout;

        function performSearch() {
            const search = searchInput.value.trim();
            const date = dateInput.value;

            const params = new URLSearchParams();
            if (search) params.append('search', search);
            if (date) params.append('date', date);

            fetch(`{{ route('laporan.index') }}?${params.toString()}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(res => res.text())
                .then(html => {
                    resultsContainer.innerHTML = html;
                })
                .catch(err => {
                    console.error('AJAX search error:', err);
                });
        }

        searchInput.addEventListener('input', function() {
            clearTimeout(debounceTimeout);
            debounceTimeout = setTimeout(performSearch, 300);
        });

        dateInput.addEventListener('change', performSearch);

        resetButton.addEventListener('click', function(e) {
            e.preventDefault();
            searchInput.value = '';
            dateInput.value = '';
            performSearch();
        });
        // tampil detail transaksi
        const rows = document.querySelectorAll('.transaction-row');
        const info = document.getElementById('selectedTransactionInfo');
        const detailsContainer = document.getElementById('transactionDetailsContent');

        resultsContainer.addEventListener('click', function(e) {
            const row = e.target.closest('.transaction-row');
            if (!row) return; // bukan baris transaksi

            const id = row.dataset.id;
            info.textContent = `Loading transaction #${id}...`;

            fetch(`/laporan/${id}`)
                .then(response => response.json())
                .then(data => {
                    info.textContent = `Transaction ID: ${id}`;

                    if (data.details && data.details.length > 0) {
                        let html = `<ul class="space-y-2">`;
                        data.details.forEach(item => {
                            html += `
                        <li class="border-b pb-2">
                            <div class="font-semibold">${item.product_name}</div>
                            <div class="text-sm text-gray-600">Qty: ${item.formatted_qty} x ${item.price}</div>
                            <div class="text-sm text-gray-800">Subtotal: ${item.subtotal}</div>
                        </li>
                    `;
                        });
                        html += `</ul>`;
                        detailsContainer.innerHTML = html;
                    } else {
                        detailsContainer.innerHTML =
                            `<p class="text-center text-gray-500 py-8">No items found.</p>`;
                    }
                })
                .catch(() => {
                    info.textContent = `Failed to load transaction #${id}`;
                    detailsContainer.innerHTML =
                        `<p class="text-center text-red-500 py-8">Error loading data.</p>`;
                });
        });
    });
</script>

</html>
