@extends('layouts.home')

@section('content')
<div class="container-fluid">

    <!-- Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Kelola Pengguna</h1>
        <button class="btn btn-primary btn-sm shadow-sm" data-toggle="modal" data-target="#modalUser">
            <i class="fas fa-plus fa-sm"></i> Tambah Pengguna
        </button>
    </div>

    <!-- Tabel User -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Pengguna</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ ucfirst($user->role?->name ?? 'karyawan') }}</td>
                            <td>
                                <button class="btn btn-warning btn-sm" 
                                    data-toggle="modal" 
                                    data-target="#modalUser"
                                    onclick="editUser(
                                        {{ $user->id }},
                                        '{{ addslashes($user->name) }}',
                                        '{{ $user->email }}',
                                        '{{ $user->role?->name ?? 'karyawan' }}'
                                    )">
                                    Edit
                                </button>
                                <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm"
                                        onclick="return confirm('Yakin hapus {{ addslashes($user->name) }}?')">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah/Edit User -->
<div class="modal fade" id="modalUser" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="userForm" method="POST">
                @csrf
                <input type="hidden" name="_method" id="formMethod" value="POST">
                <input type="hidden" name="id" id="userId">

                <div class="modal-header">
                    <h5 class="modal-title" id="userModalTitle">Tambah Pengguna</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nama</label>
                        <input type="text" name="name" id="userName" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" id="userEmail" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" name="password" id="userPassword" class="form-control" placeholder="Kosongkan jika tidak diubah">
                    </div>
                    <div class="form-group">
                        <label>Role</label>
                        <select name="role" id="userRole" class="form-control" required>
                            <option value="admin">Admin</option>
                            <option value="karyawan">Karyawan</option>
                        </select>
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
    $('#modalUser').on('hidden.bs.modal', function () {
        $('#userForm')[0].reset();
        $('#formMethod').val('POST');
        $('#userId').val('');
        $('#userModalTitle').text('Tambah Pengguna');
        $('#userForm').attr('action', '{{ route("users.store") }}');
        $('#userPassword').attr('placeholder', 'Kosongkan jika tidak diubah');
    });

    function editUser(id, name, email, role) {
        $('#userModalTitle').text('Edit Pengguna');
        $('#formMethod').val('PUT');
        $('#userId').val(id);
        $('#userName').val(name);
        $('#userEmail').val(email);
        $('#userRole').val(role);
        $('#userPassword').attr('placeholder', 'Kosongkan jika tidak ubah password');
        $('#userForm').attr('action', '/users/' + id);
    }
</script>
@endpush
@endsection