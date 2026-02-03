@extends('layouts.home')

@section('content')
<div class="container-fluid">

    @if(session('success') || session('error'))
        <div class="alert {{ session('success') ? 'alert-success' : 'alert-danger' }} 
                   position-fixed fade show"
             role="alert"
             style="top: 20px; left: 50%; transform: translateX(-50%); z-index: 9999; max-width: 90%;">
            <div class="d-flex justify-content-between align-items-center">
                <span>
                    @if(session('success'))
                        <i class="fas fa-check-circle"></i> {{ session('success') }}
                    @else
                        <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                    @endif
                </span>
            </div>
        </div>
    @endif

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
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $supplier->name }}</td>
    
                            <td>
                                <button class="btn btn-warning btn-sm" 
                                    data-toggle="modal" 
                                    data-target="#modalSupplier"
                                    onclick="editSupplier({{ $supplier->id }}, '{{ addslashes($supplier->name) }}',  '{{ addslashes($supplier->address) }}')">
                                    Edit
                                </button>
                                <form action="{{ route('suppliers.destroy',$supplier->id) }}" method="POST" class="d-inline">
                                    @csrf 
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" 
                                        onclick="return confirm('Yakin hapus {{ $supplier->name }}?')">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">Tidak ada data supplier.</td>
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
                    <div class="form-group">
                        <label>Alamat</label>
                        <textarea name="address" id="supplierAddress" class="form-control" rows="2"></textarea>
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
        $('#supplierForm').attr('action', '{{ route('suppliers.store') }}');
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