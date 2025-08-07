<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ERAMEDIA - Kelola User Karyawan</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            color: #333;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }

        /* Header */
        .page-header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 2rem;
            border-radius: 20px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            margin-bottom: 2rem;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .page-title {
            font-size: 2rem;
            margin-bottom: 0.5rem;
            background: linear-gradient(45deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-weight: bold;
        }

        .page-subtitle {
            color: #666;
            font-size: 1.1rem;
        }

        /* Card Styles */
        .card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            overflow: hidden;
            margin-bottom: 1.5rem;
        }

        .card-header {
            padding: 1.5rem;
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
        }

        .card-body {
            padding: 1.5rem;
        }

        /* Filter Section */
        .filter-section {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr;
            gap: 1rem;
            align-items: center;
        }

        .form-control {
            padding: 0.75rem 1rem;
            border: 2px solid rgba(102, 126, 234, 0.2);
            border-radius: 8px;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            background: white;
        }

        .form-control:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        /* Button Styles */
        .btn {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 8px;
            font-size: 0.95rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
        }

        .btn-primary {
            background: linear-gradient(45deg, #667eea, #764ba2);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        .btn-warning {
            background: linear-gradient(45deg, #fd7e14, #e83e8c);
            color: white;
        }

        .btn-danger {
            background: linear-gradient(45deg, #dc3545, #c82333);
            color: white;
        }

        .btn-sm {
            padding: 0.5rem 1rem;
            font-size: 0.85rem;
        }

        /* Table Styles */
        .table-container {
            overflow-x: auto;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table th {
            background: linear-gradient(45deg, #667eea, #764ba2);
            color: white;
            padding: 1rem;
            text-align: left;
            font-weight: 600;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .table td {
            padding: 1rem;
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
            vertical-align: middle;
        }

        .table tbody tr {
            transition: all 0.3s ease;
        }

        .table tbody tr:hover {
            background: linear-gradient(90deg, rgba(102, 126, 234, 0.05), transparent);
            transform: translateX(5px);
        }

        /* Badge Styles */
        .badge {
            padding: 0.4rem 0.8rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
            color: white;
        }

        .badge-success {
            background: linear-gradient(45deg, #28a745, #20c997);
        }

        .badge-danger {
            background: linear-gradient(45deg, #dc3545, #c82333);
        }

        /* Modal Styles */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(5px);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }

        .modal-overlay.show {
            display: flex;
        }

        .modal {
            background: white;
            border-radius: 20px;
            max-width: 500px;
            width: 90%;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }

        .modal-header {
            padding: 1.5rem;
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-title {
            font-size: 1.3rem;
            font-weight: 600;
            background: linear-gradient(45deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .modal-body {
            padding: 1.5rem;
        }

        .modal-footer {
            padding: 1.5rem;
            border-top: 1px solid rgba(0, 0, 0, 0.1);
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: #555;
        }

        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 0.5rem;
        }

        /* Close Button */
        .close-btn {
            background: none;
            border: none;
            font-size: 1.5rem;
            color: #999;
            cursor: pointer;
            padding: 0.25rem;
            border-radius: 4px;
            transition: all 0.3s ease;
        }

        .close-btn:hover {
            color: #dc3545;
            background: rgba(220, 53, 69, 0.1);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .filter-section {
                grid-template-columns: 1fr;
                gap: 0.75rem;
            }

            .container {
                padding: 1rem;
            }

            .table-container {
                font-size: 0.9rem;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Header -->
        <div class="page-header">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <h1 class="page-title">
                        <i class="fas fa-users"></i> Kelola User Karyawan
                    </h1>
                    <p class="page-subtitle">Manajemen data pengguna dengan role karyawan</p>
                </div>
                <a href="/dashboard" class="btn btn-primary">
                    <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
                </a>
            </div>
        </div>

        <!-- Filter & Actions -->
        <div class="card">
            <div class="card-body">
                <div class="filter-section">
                    <input type="text" id="searchInput" class="form-control" placeholder="Cari nama atau email...">
                    <select id="departmentFilter" class="form-control">
                        <option value="">Semua Departemen</option>
                        <option value="IT">IT</option>
                        <option value="HR">HR</option>
                        <option value="Finance">Finance</option>
                        <option value="Marketing">Marketing</option>
                    </select>
                    <select id="statusFilter" class="form-control">
                        <option value="">Semua Status</option>
                        <option value="Aktif">Aktif</option>
                        <option value="Nonaktif">Nonaktif</option>
                    </select>
                    <button class="btn btn-primary" onclick="openModal('add')">
                        <i class="fas fa-plus"></i> Tambah Karyawan
                    </button>
                </div>
            </div>
        </div>

        <!-- Tabel Karyawan -->
        <div class="card">
            <div class="card-body">
                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Telepon</th>
                                <th>Departemen</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="employeeTable">
                            <!-- Data akan diisi oleh JavaScript -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tambah/Edit -->
    <div class="modal-overlay" id="employeeModal">
        <div class="modal">
            <div class="modal-header">
                <h3 class="modal-title" id="modalTitle">Tambah Karyawan</h3>
                <button class="close-btn" onclick="closeModal()">&times;</button>
            </div>
            <div class="modal-body">
                <form id="employeeForm">
                    <input type="hidden" id="employeeId">
                    <div class="form-group">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" id="name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Email</label>
                        <input type="email" id="email" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Telepon</label>
                        <input type="text" id="phone" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Departemen</label>
                        <select id="department" class="form-control" required>
                            <option value="">Pilih Departemen</option>
                            <option value="IT">IT</option>
                            <option value="HR">HR</option>
                            <option value="Finance">Finance</option>
                            <option value="Marketing">Marketing</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Status</label>
                        <select id="status" class="form-control" required>
                            <option value="Aktif">Aktif</option>
                            <option value="Nonaktif">Nonaktif</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn" onclick="closeModal()"
                    style="background: #6c757d; color: white;">Batal</button>
                <button type="button" class="btn btn-primary" onclick="saveEmployee()">Simpan</button>
            </div>
        </div>
    </div>

    <!-- Modal Konfirmasi Hapus -->
    <div class="modal-overlay" id="deleteModal">
        <div class="modal" style="max-width: 400px;">
            <div class="modal-header">
                <h3 class="modal-title">Konfirmasi Hapus</h3>
                <button class="close-btn" onclick="closeDeleteModal()">&times;</button>
            </div>
            <div class="modal-body">
                <div style="text-align: center;">
                    <i class="fas fa-exclamation-triangle"
                        style="font-size: 3rem; color: #dc3545; margin-bottom: 1rem;"></i>
                    <p>Yakin ingin menghapus karyawan ini?</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn" onclick="closeDeleteModal()"
                    style="background: #6c757d; color: white;">Batal</button>
                <button type="button" class="btn btn-danger" onclick="confirmDelete()">Hapus</button>
            </div>
        </div>
    </div>

    <script>
        // Data karyawan
        let employees = [{
                id: 1,
                name: 'Ahmad Wijaya',
                email: 'ahmad@eramedia.com',
                phone: '081234567890',
                department: 'IT',
                status: 'Aktif'
            },
            {
                id: 2,
                name: 'Siti Nurhaliza',
                email: 'siti@eramedia.com',
                phone: '081234567891',
                department: 'HR',
                status: 'Aktif'
            },
            {
                id: 3,
                name: 'Budi Santoso',
                email: 'budi@eramedia.com',
                phone: '081234567892',
                department: 'Finance',
                status: 'Aktif'
            },
            {
                id: 4,
                name: 'Maya Indira',
                email: 'maya@eramedia.com',
                phone: '081234567893',
                department: 'Marketing',
                status: 'Nonaktif'
            }
        ];

        let editingId = null;
        let deleteId = null;

        // Render tabel
        function renderTable() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            const departmentFilter = document.getElementById('departmentFilter').value;
            const statusFilter = document.getElementById('statusFilter').value;

            const filtered = employees.filter(emp => {
                const matchSearch = emp.name.toLowerCase().includes(searchTerm) ||
                    emp.email.toLowerCase().includes(searchTerm);
                const matchDept = !departmentFilter || emp.department === departmentFilter;
                const matchStatus = !statusFilter || emp.status === statusFilter;
                return matchSearch && matchDept && matchStatus;
            });

            const tbody = document.getElementById('employeeTable');
            tbody.innerHTML = '';

            if (filtered.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 2rem; color: #666;">
                            <i class="fas fa-users" style="font-size: 3rem; opacity: 0.3; margin-bottom: 1rem; display: block;"></i>
                            Tidak ada data karyawan yang ditemukan
                        </td>
                    </tr>
                `;
                return;
            }

            filtered.forEach((emp, index) => {
                const statusBadge = emp.status === 'Aktif' ?
                    '<span class="badge badge-success">Aktif</span>' :
                    '<span class="badge badge-danger">Nonaktif</span>';

                const row = `
                    <tr>
                        <td>${index + 1}</td>
                        <td><strong>${emp.name}</strong></td>
                        <td>${emp.email}</td>
                        <td>${emp.phone}</td>
                        <td>${emp.department}</td>
                        <td>${statusBadge}</td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn btn-warning btn-sm" onclick="editEmployee(${emp.id})" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-danger btn-sm" onclick="deleteEmployee(${emp.id})" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                `;
                tbody.innerHTML += row;
            });
        }

        // Modal functions
        function openModal(mode, id = null) {
            const modal = document.getElementById('employeeModal');
            const modalTitle = document.getElementById('modalTitle');
            const form = document.getElementById('employeeForm');

            if (mode === 'add') {
                modalTitle.textContent = 'Tambah Karyawan';
                form.reset();
                document.getElementById('employeeId').value = '';
                editingId = null;
            }

            modal.classList.add('show');
        }

        function closeModal() {
            document.getElementById('employeeModal').classList.remove('show');
        }

        function editEmployee(id) {
            const emp = employees.find(e => e.id === id);
            if (emp) {
                document.getElementById('employeeId').value = emp.id;
                document.getElementById('name').value = emp.name;
                document.getElementById('email').value = emp.email;
                document.getElementById('phone').value = emp.phone;
                document.getElementById('department').value = emp.department;
                document.getElementById('status').value = emp.status;
                document.getElementById('modalTitle').textContent = 'Edit Karyawan';
                editingId = id;

                document.getElementById('employeeModal').classList.add('show');
            }
        }

        function saveEmployee() {
            const form = document.getElementById('employeeForm');
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            const data = {
                name: document.getElementById('name').value,
                email: document.getElementById('email').value,
                phone: document.getElementById('phone').value,
                department: document.getElementById('department').value,
                status: document.getElementById('status').value
            };

            if (editingId) {
                const index = employees.findIndex(e => e.id === editingId);
                employees[index] = {
                    ...employees[index],
                    ...data
                };
            } else {
                const newId = Math.max(...employees.map(e => e.id)) + 1;
                employees.push({
                    id: newId,
                    ...data
                });
            }

            closeModal();
            renderTable();
        }

        function deleteEmployee(id) {
            deleteId = id;
            document.getElementById('deleteModal').classList.add('show');
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.remove('show');
            deleteId = null;
        }

        function confirmDelete() {
            if (deleteId) {
                employees = employees.filter(e => e.id !== deleteId);
                renderTable();
                deleteId = null;
                closeDeleteModal();
            }
        }

        // Event listeners
        document.getElementById('searchInput').addEventListener('input', renderTable);
        document.getElementById('departmentFilter').addEventListener('change', renderTable);
        document.getElementById('statusFilter').addEventListener('change', renderTable);

        // Close modal when clicking outside
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('modal-overlay')) {
                closeModal();
                closeDeleteModal();
            }
        });

        // Initialize
        document.addEventListener('DOMContentLoaded', renderTable);
    </script>
</body>

</html>
