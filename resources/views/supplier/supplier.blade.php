@extends('layouts.home')

@section('content')
<div class="container-fluid">

    <!-- Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Kelola Supplier</h1>
        <button class="btn btn-primary btn-sm shadow-sm" data-toggle="modal" data-target="#modalSupplier">
            <i class="fas fa-plus fa-sm"></i> Tambah Supplier
        </button>
    </div>

    <!-- Tabel Supplier -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Supplier</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($suppliers as $supplier)
                            <!-- Baris utama supplier -->
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $supplier->name }}</td>
                                <td>
                                    <button class="btn btn-info btn-sm mr-1"
                                            type="button"
                                            data-toggle="collapse"
                                            data-target="#items-{{ $supplier->id }}"
                                            aria-expanded="false"
                                            aria-controls="items-{{ $supplier->id }}">
                                        <i class="fas fa-box"></i> Lihat Barang
                                    </button>

                                    <button class="btn btn-warning btn-sm"
                                        data-toggle="modal"
                                        data-target="#modalSupplier"
                                        onclick="editSupplier({{ $supplier->id }}, '{{ addslashes($supplier->name) }}', '{{ addslashes($supplier->address) }}')">
                                        <i class="fas fa-edit"></i> Edit
                                    </button>

                                    <form action="{{ route('suppliers.destroy', $supplier->id) }}" method="POST" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm"
                                            onclick="return confirm('Yakin hapus {{ addslashes($supplier->name) }}?')">
                                            <i class="fas fa-trash"></i> Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>

                            <!-- Baris collapse untuk daftar barang -->
                            <tr>
                                <td colspan="4" class="p-0">
                                    <div class="collapse" id="items-{{ $supplier->id }}">
                                        <div class="card card-body border-0 bg-light">
                                            <h6 class="font-weight-bold text-primary mb-2">
                                                Barang dari: {{ $supplier->name }}
                                            </h6>
                                            @if($supplier->supplierItems && $supplier->supplierItems->count() > 0)
                                                <div class="table-responsive">
                                                    <table class="table table-sm table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th>Kode</th>
                                                                <th>Nama Barang</th>
                                                                <th>Harga Beli</th>
                                                                <th>Stok</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($supplier->supplierItems as $si)
                                                                <tr>
                                                                    <td>{{ $si->id ?? '-' }}</td>
                                                                    <td>{{ $si->name ?? 'Barang tidak ditemukan' }}</td>
                                                                    <td>Rp {{ number_format($si->price ?? 0, 0, ',', '.') }}</td>
                                                                    <td>{{ $si->stok ?? 0 }}</td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            @else
                                                <p class="text-muted"><em>Tidak ada barang terdaftar untuk supplier ini.</em></p>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">Tidak ada data supplier.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah/Edit Supplier -->
<div class="modal fade" id="modalSupplier" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="supplierForm" method="POST">
                @csrf
                <input type="hidden" name="_method" id="formMethod" value="POST">
                <input type="hidden" name="id" id="supplierId">

                <div class="modal-header">
                    <h5 class="modal-title" id="supplierModalTitle">Tambah Supplier</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nama Supplier</label>
                        <input type="text" name="name" id="supplierName" class="form-control" required>
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
    $('#modalSupplier').on('hidden.bs.modal', function () {
        $('#supplierForm')[0].reset();
        $('#formMethod').val('POST');
        $('#supplierId').val('');
        $('#supplierModalTitle').text('Tambah Supplier');
        $('#supplierForm').attr('action', '{{ route("suppliers.store") }}');
    });

    function editSupplier(id, name, address) {
        $('#supplierModalTitle').text('Edit Supplier');
        $('#formMethod').val('PUT');
        $('#supplierId').val(id);
        $('#supplierName').val(name);
        $('#supplierAddress').val(address);
        $('#supplierForm').attr('action', "{{ route('suppliers.update', ':id') }}".replace(':id', id));
    }
</script>
@endpush
@endsection