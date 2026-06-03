@extends('layouts.home')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Tambah Pembelian</h1>
        <a href="{{ route('purchase.purchase') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
</div>

    <div class="card shadow mb-4">
        <div class="card-body">
<form method="POST" action="{{ route('pembelian.store') }}">
    @csrf
    
            <!-- Pilih Supplier -->
                <div class="form-group mb-4">
                    <label for="supplier_id" class="font-weight-bold">Supplier</label>
                    <div class="d-flex gap-2">
                        <select name="supplier_id" id="supplier_id" class="form-control" required>
                            <option value="">-- Pilih Supplier --</option>
                            @foreach($allSuppliers as $supplier)
                                <option value="{{ $supplier->id }}">
                                    {{ $supplier->name }}
                                </option>
                            @endforeach
                        </select>
                        <button type="button" id="loadProductsBtn" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-sync"></i> Muat Barang
                        </button>
                    </div>
                    @error('supplier_id')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
            </div>

            <div id="supplier-items-container" style="display: none;">
                <div id="supplier-items-content"></div>
            </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Hapus Barang Supplier -->
<div class="modal fade" id="deleteItemModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
            <div class="modal-body text-center py-5 px-4">
                <div class="mb-4">
                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-danger"
                         style="width: 72px; height: 72px;">
                        <i class="fas fa-trash-alt text-white" style="font-size: 28px;"></i>
                    </div>
                </div>
                <h5 class="font-weight-bold mb-2">Hapus Barang Supplier</h5>
                <p class="text-muted mb-1" style="font-size: 15px;">
                    Yakin ingin menghapus
                </p>
                <p class="font-weight-bold text-danger mb-3" id="deleteItemName" style="font-size: 16px;"></p>
                <p class="text-muted small mb-4">
                    <i class="fas fa-info-circle"></i>
                    Data pembelian yang sudah tercatat tidak akan terpengaruh.
                </p>
                <div class="d-flex justify-content-center gap-2">
                    <button type="button" class="btn btn-outline-secondary px-4 py-2" id="cancelDeleteBtn" style="border-radius: 10px; font-weight: 500;">
                        Batal
                    </button>
                    <button type="button" class="btn btn-danger px-4 py-2" id="confirmDeleteBtn" style="border-radius: 10px; font-weight: 500;">
                        <span id="deleteBtnText">Ya, Hapus</span>
                        <span id="deleteBtnSpinner" class="spinner-border spinner-border-sm d-none" role="status"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    var deleteItemId = null;

    document.getElementById('loadProductsBtn').addEventListener('click', function() {
        var supplierId = document.getElementById('supplier_id').value;
        if (!supplierId) {
            alert('Silakan pilih supplier terlebih dahulu.');
            return;
        }

        var container = document.getElementById('supplier-items-container');
        var content = document.getElementById('supplier-items-content');
        var btn = this;

        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memuat...';

        fetch('{{ route("pembelian.supplier-items", "") }}/' + supplierId)
            .then(function(res) {
                if (!res.ok) throw new Error('Gagal memuat data');
                return res.json();
            })
            .then(function(data) {
                content.innerHTML = data.html;
                container.style.display = 'block';
            })
            .catch(function(err) {
                alert('Terjadi kesalahan: ' + err.message);
            })
            .finally(function() {
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-sync"></i> Muat Barang';
            });
    });

    // Event delegation: show delete confirmation modal
    document.getElementById('supplier-items-content').addEventListener('click', function(e) {
        var btn = e.target.closest('.delete-supplier-item');
        if (!btn) return;

        deleteItemId = btn.dataset.id;
        document.getElementById('deleteItemName').textContent = '"' + btn.dataset.name + '"';
        $('#deleteItemModal').modal('show');
    });

    // Cancel button
    document.getElementById('cancelDeleteBtn').addEventListener('click', function() {
        $('#deleteItemModal').modal('hide');
    });

    // Confirm delete
    document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
        if (!deleteItemId) return;

        var confirmBtn = this;
        var btnText = document.getElementById('deleteBtnText');
        var spinner = document.getElementById('deleteBtnSpinner');

        confirmBtn.disabled = true;
        btnText.textContent = 'Menghapus...';
        spinner.classList.remove('d-none');

        fetch('{{ route("pembelian.supplier-item.destroy", "") }}/' + deleteItemId, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(function(res) {
            if (!res.ok) throw new Error('Gagal menghapus barang');
            return res.json();
        })
        .then(function() {
            $('#deleteItemModal').modal('hide');

            var card = document.querySelector('.delete-supplier-item[data-id="' + deleteItemId + '"]');
            if (card) {
                var col = card.closest('.col-lg-6');
                if (col) {
                    col.style.transition = 'all .3s ease';
                    col.style.opacity = '0';
                    col.style.transform = 'scale(0.95)';
                    setTimeout(function() {
                        col.remove();
                        if (typeof createToast === 'function') {
                            createToast('success', 'Barang berhasil dihapus dari supplier.');
                        }
                    }, 300);
                }
            }
        })
        .catch(function(err) {
            $('#deleteItemModal').modal('hide');
            if (typeof createToast === 'function') {
                createToast('error', err.message);
            } else {
                alert('Gagal menghapus: ' + err.message);
            }
        })
        .finally(function() {
            confirmBtn.disabled = false;
            btnText.textContent = 'Ya, Hapus';
            spinner.classList.add('d-none');
            deleteItemId = null;
        });
    });
</script>
@endpush
