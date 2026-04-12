@extends('layouts.home')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Manajemen Unit</h1>
        <button type="button" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" 
                data-toggle="modal" data-target="#unitModal"
                onclick="openCreateUnitModal()">
            <i class="fas fa-plus fa-sm text-white-50"></i> Tambah Unit
        </button>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Unit</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Unit</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($units as $unit)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $unit->name }}</td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-warning"
                                            data-toggle="modal" data-target="#unitModal"
                                            onclick="openEditUnitModal({{ $unit->id }}, '{{ addslashes($unit->name) }}')">
                                        <i class="fas fa-edit"></i> Edit
                                    </button>
                                    <form action="{{ route('unit.destroy', $unit->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger"
                                                onclick="return confirm('Yakin hapus unit: {{ $unit->name }}?')">
                                            <i class="fas fa-trash"></i> Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted">Belum ada data unit.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                 <div class="d-flex justify-content-center mt-3">
                        {{ $units->appends(request()->query())->links('public.pagination.sb-admin-2') }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah/Edit Unit -->
<div class="modal fade" id="unitModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="unitForm">
                @csrf
                <input type="hidden" name="_method" id="formMethod" value="POST">
                <input type="hidden" name="id" id="unitId">

                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Tambah Unit</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nama Unit</label>
                        <input type="text" name="name" id="unitName" class="form-control" required 
                               placeholder="Contoh: Buah, Bungkus, Liter">
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


    // Reset modal saat ditutup
    $('#unitModal').on('hidden.bs.modal', function () {
        $('#unitForm')[0].reset();
        $('#formMethod').val('POST');
        $('#unitId').val('');
        $('#modalTitle').text('Tambah Unit');
        $('#unitForm').attr('action', '{{ route("unit.tambah") }}');
    });

    // Buka modal untuk Tambah
    function openCreateUnitModal() {
        $('#modalTitle').text('Tambah Unit');
        $('#formMethod').val('POST');
        $('#unitId').val('');
        $('#unitName').val('');
        $('#unitForm').attr('action', '{{ route("unit.tambah") }}');
    }

    // Buka modal untuk Edit
    function openEditUnitModal(id, name) {
        $('#modalTitle').text('Edit Unit');
        $('#formMethod').val('PUT');
        $('#unitId').val(id);
        $('#unitName').val(name);
        $('#unitForm').attr('action', '/kategories/' + id);
    }

    $('#unitForm').on('submit', function (e) {
        e.preventDefault();
        const url = $(this).attr('action');
        const method = $('#formMethod').val() === 'PUT' ? 'PUT' : 'POST';
        const formData = {
            _token: $('input[name="_token"]').val(),
            name: $('#unitName').val(),
            ...(method === 'PUT' && { _method: 'PUT' }),
            ...(method === 'PUT' && { id: $('#unitId').val() })
        };

        $.ajax({
            url: url,
            method: method,
            data: formData,
            success: function (response) {
                $('#unitModal').modal('hide');
                createToast('success', response.message || 'Data berhasil disimpan!');
                location.reload(); 
            },
            error: function (xhr) {
                const errorMsg = xhr.responseJSON?.message || 'Terjadi kesalahan!';
                createToast('error', errorMsg);
            }
        });
    });


</script>
@endpush
@endsection