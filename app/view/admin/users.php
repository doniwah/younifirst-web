<?php
// Admin Users List View
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Daftar Pengguna' ?></title>
    <link rel="stylesheet" href="/css/variable.css">
    <link rel="stylesheet" href="/css/sidebar.css">
    <link rel="stylesheet" href="/css/admin_users.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
            animation: fadeIn 0.3s;
        }

        .modal.active {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background-color: white;
            padding: 30px;
            border-radius: 12px;
            width: 90%;
            max-width: 500px;
            max-height: 90vh;
            overflow-y: auto;
            animation: slideUp 0.3s;
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .modal-header h2 {
            margin: 0;
            font-size: 24px;
            color: #1a1a1a;
        }

        .close-modal {
            background: none;
            border: none;
            font-size: 28px;
            cursor: pointer;
            color: #666;
            line-height: 1;
            padding: 0;
            width: 30px;
            height: 30px;
        }

        .close-modal:hover {
            color: #333;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
            transition: border-color 0.3s;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #4CAF50;
        }

        .form-group textarea {
            resize: vertical;
            min-height: 80px;
        }

        .modal-actions {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
            margin-top: 25px;
        }

        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            transition: all 0.3s;
        }

        .btn-cancel {
            background-color: #f5f5f5;
            color: #666;
        }

        .btn-cancel:hover {
            background-color: #e0e0e0;
        }

        .btn-submit {
            background-color: #4CAF50;
            color: white;
        }

        .btn-submit:hover {
            background-color: #45a049;
        }

        .btn-danger {
            background-color: #f44336;
            color: white;
        }

        .btn-danger:hover {
            background-color: #da190b;
        }

        /* Dropdown Menu Styles */
        .dropdown-menu {
            position: absolute;
            right: 0;
            top: 100%;
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            min-width: 180px;
            display: none;
            z-index: 100;
            margin-top: 5px;
            overflow: hidden;
        }

        .dropdown-menu.active {
            display: block;
            animation: slideDown 0.2s;
        }

        .dropdown-item {
            padding: 12px 16px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: background-color 0.2s;
            border: none;
            background: none;
            width: 100%;
            text-align: left;
            font-size: 14px;
            color: #333;
        }

        .dropdown-item:hover {
            background-color: #f5f5f5;
        }

        .dropdown-item.danger {
            color: #f44336;
        }

        .dropdown-item.danger:hover {
            background-color: #ffebee;
        }

        .action-cell {
            position: relative;
        }

        .more-btn {
            background: none;
            border: none;
            cursor: pointer;
            padding: 8px;
            border-radius: 4px;
            transition: background-color 0.2s;
        }

        .more-btn:hover {
            background-color: #f5f5f5;
        }
        .user-avatar-initial {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 16px;
    flex-shrink: 0;
}

/* Variasi warna untuk inisial berbeda */
.user-avatar-initial[data-initial^="A"],
.user-avatar-initial[data-initial^="B"],
.user-avatar-initial[data-initial^="C"] {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
}

.user-avatar-initial[data-initial^="D"],
.user-avatar-initial[data-initial^="E"],
.user-avatar-initial[data-initial^="F"] {
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
}

.user-avatar-initial[data-initial^="G"],
.user-avatar-initial[data-initial^="H"],
.user-avatar-initial[data-initial^="I"] {
    background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
}

.user-avatar-initial[data-initial^="J"],
.user-avatar-initial[data-initial^="K"],
.user-avatar-initial[data-initial^="L"] {
    background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
}

.user-avatar-initial[data-initial^="M"],
.user-avatar-initial[data-initial^="N"],
.user-avatar-initial[data-initial^="O"] {
    background: linear-gradient(135deg, #30cfd0 0%, #330867 100%);
}

.user-avatar-initial[data-initial^="P"],
.user-avatar-initial[data-initial^="Q"],
.user-avatar-initial[data-initial^="R"] {
    background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
}

.user-avatar-initial[data-initial^="S"],
.user-avatar-initial[data-initial^="T"],
.user-avatar-initial[data-initial^="U"] {
    background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%);
}

.user-avatar-initial[data-initial^="V"],
.user-avatar-initial[data-initial^="W"],
.user-avatar-initial[data-initial^="X"] {
    background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%);
}

.user-avatar-initial[data-initial^="Y"],
.user-avatar-initial[data-initial^="Z"] {
    background: linear-gradient(135deg, #a1c4fd 0%, #c2e9fb 100%);
}

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes slideUp {
            from {
                transform: translateY(50px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        @keyframes slideDown {
            from {
                transform: translateY(-10px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .alert {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: none;
        }

        .alert.active {
            display: block;
            animation: slideDown 0.3s;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>
<body>
    
    <?php require __DIR__ . '/../layouts/sidebar.php'; ?>

    <div class="main-content">
        <div class="users-container">
            
            <!-- Alert Messages -->
            <div id="alertMessage" class="alert"></div>

            <!-- Header & Search -->
            <div class="page-header">
                <div class="search-filter-group">
                    <form method="GET" action="/admin/users" class="search-box" id="searchForm">
                        <i class="bi bi-search"></i>
                        <input type="text" name="search" placeholder="Cari pengguna..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                    </form>
                    <button class="filter-btn" onclick="toggleFilter()">
                        <i class="bi bi-filter-left"></i>
                    </button>
                </div>
                <div class="action-buttons">
                    <a href="#" class="add-user-btn" onclick="openAddUserModal(event)">Tambah Pengguna</a>
                    <a href="#" class="export-btn" onclick="exportUsers(event)">
                        <i class="bi bi-download"></i>
                        Export
                    </a>
                </div>
            </div>

            <!-- Stats Row -->
            <div class="stats-row">
                <div class="stat-card">
                    <div class="stat-title">Total User</div>
                    <div class="stat-value"><?= $stats['total'] ?></div>
                </div>
                <div class="stat-card">
                    <div class="stat-title">Aktif</div>
                    <div class="stat-value green"><?= $stats['active'] ?></div>
                </div>
                <div class="stat-card">
                    <div class="stat-title">Suspended</div>
                    <div class="stat-value orange"><?= $stats['suspended'] ?></div>
                </div>
                <div class="stat-card">
                    <div class="stat-title">Blocked</div>
                    <div class="stat-value red"><?= $stats['blocked'] ?></div>
                </div>
            </div>

            <!-- Users Table -->
            <div class="users-table-container">
                <table class="users-table">
                    <thead>
                        <tr>
                            <th>Pengguna</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Bergabung</th>
                            <th>Terakhir Aktif</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($users)): ?>
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 40px;">
                                <i class="bi bi-inbox" style="font-size: 48px; color: #ccc;"></i>
                                <p style="margin-top: 10px; color: #999;">Tidak ada pengguna ditemukan</p>
                            </td>
                        </tr>
                        <?php else: ?>
                        <?php foreach ($users as $user): ?>
                        <tr>
                            <td>
    <div class="user-info">
        <?php if (!empty($user['avatar']) && $user['avatar'] !== '/images/default-avatar.png'): ?>
            <img src="<?= $user['avatar'] ?>" alt="Avatar" class="user-avatar">
        <?php else: ?>
            <div class="user-avatar-initial">
                <?= strtoupper(substr($user['nama_lengkap'] ?: $user['username'], 0, 1)) ?>
            </div>
        <?php endif; ?>
        <div class="user-details">
            <span class="user-name"><?= htmlspecialchars($user['username']) ?></span>
            <span class="user-email"><?= htmlspecialchars($user['email']) ?></span>
        </div>
    </div>
</td>
                            <td>
                                <span class="role-badge"><?= ucfirst($user['role']) ?></span>
                            </td>
                            <td>
                                <span class="status-badge <?= strtolower($user['status']) ?>"><?= $user['status'] ?></span>
                            </td>
                            <td class="text-cell"><?= $user['joined_date'] ?></td>
                            <td class="text-cell"><?= $user['last_active'] ?></td>
                            <td class="action-cell">
                                <button class="more-btn" onclick="toggleDropdown(event, '<?= $user['user_id'] ?>')">
                                    <i class="bi bi-three-dots-vertical"></i>
                                </button>
                                <div class="dropdown-menu" id="dropdown-<?= $user['user_id'] ?>">
                                    <button class="dropdown-item" onclick="editUser('<?= $user['user_id'] ?>')">
                                        <i class="bi bi-pencil"></i> Edit
                                    </button>
                                    <button class="dropdown-item" onclick="changeStatus('<?= $user['user_id'] ?>', 'active')">
                                        <i class="bi bi-check-circle"></i> Aktifkan
                                    </button>
                                    <button class="dropdown-item" onclick="changeStatus('<?= $user['user_id'] ?>', 'suspended')">
                                        <i class="bi bi-pause-circle"></i> Suspend
                                    </button>
                                    <button class="dropdown-item" onclick="changeStatus('<?= $user['user_id'] ?>', 'blocked')">
                                        <i class="bi bi-x-circle"></i> Block
                                    </button>
                                    <button class="dropdown-item danger" onclick="deleteUser('<?= $user['user_id'] ?>')">
                                        <i class="bi bi-trash"></i> Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    <!-- Add User Modal -->
    <div id="addUserModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Tambah Pengguna Baru</h2>
                <button class="close-modal" onclick="closeModal('addUserModal')">&times;</button>
            </div>
            <form id="addUserForm" onsubmit="submitAddUser(event)">
                <div class="form-group">
                    <label>Username *</label>
                    <input type="text" name="username" required>
                </div>
                <div class="form-group">
                    <label>Email *</label>
                    <input type="email" name="email" required>
                </div>
                <div class="form-group">
                    <label>Nama Lengkap *</label>
                    <input type="text" name="nama_lengkap" required>
                </div>
                <div class="form-group">
                    <label>Password *</label>
                    <input type="password" name="password" required minlength="6">
                </div>
                <div class="form-group">
                    <label>Jurusan</label>
                    <input type="text" name="jurusan">
                </div>
                <div class="form-group">
                    <label>Angkatan</label>
                    <input type="text" name="angkatan" placeholder="2024">
                </div>
                <div class="form-group">
                    <label>Role *</label>
                    <select name="role" required>
                        <option value="mahasiswa">Mahasiswa</option>
                        <option value="admin">Admin</option>
                        <option value="satpam">Satpam</option>
                    </select>
                </div>
                <div class="modal-actions">
                    <button type="button" class="btn btn-cancel" onclick="closeModal('addUserModal')">Batal</button>
                    <button type="submit" class="btn btn-submit">Tambah</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit User Modal -->
    <div id="editUserModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Edit Pengguna</h2>
                <button class="close-modal" onclick="closeModal('editUserModal')">&times;</button>
            </div>
            <form id="editUserForm" onsubmit="submitEditUser(event)">
                <input type="hidden" name="user_id" id="edit_user_id">
                <div class="form-group">
                    <label>Username *</label>
                    <input type="text" name="username" id="edit_username" required>
                </div>
                <div class="form-group">
                    <label>Email *</label>
                    <input type="email" name="email" id="edit_email" required>
                </div>
                <div class="form-group">
                    <label>Nama Lengkap *</label>
                    <input type="text" name="nama_lengkap" id="edit_nama_lengkap" required>
                </div>
                <div class="form-group">
                    <label>Jurusan</label>
                    <input type="text" name="jurusan" id="edit_jurusan">
                </div>
                <div class="form-group">
                    <label>Angkatan</label>
                    <input type="text" name="angkatan" id="edit_angkatan">
                </div>
                <div class="form-group">
                    <label>Role *</label>
                    <select name="role" id="edit_role" required>
                        <option value="mahasiswa">Mahasiswa</option>
                        <option value="admin">Admin</option>
                        <option value="satpam">Satpam</option>
                    </select>
                </div>
                <div class="modal-actions">
                    <button type="button" class="btn btn-cancel" onclick="closeModal('editUserModal')">Batal</button>
                    <button type="submit" class="btn btn-submit">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Change Status Modal -->
    <div id="statusModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Ubah Status Pengguna</h2>
                <button class="close-modal" onclick="closeModal('statusModal')">&times;</button>
            </div>
            <form id="statusForm" onsubmit="submitStatusChange(event)">
                <input type="hidden" name="user_id" id="status_user_id">
                <input type="hidden" name="status" id="status_value">
                <div class="form-group">
                    <label>Alasan</label>
                    <textarea name="reason" placeholder="Masukkan alasan perubahan status..."></textarea>
                </div>
                <div class="modal-actions">
                    <button type="button" class="btn btn-cancel" onclick="closeModal('statusModal')">Batal</button>
                    <button type="submit" class="btn btn-submit">Konfirmasi</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Hapus Pengguna</h2>
                <button class="close-modal" onclick="closeModal('deleteModal')">&times;</button>
            </div>
            <form id="deleteForm" onsubmit="submitDeleteUser(event)">
                <input type="hidden" name="user_id" id="delete_user_id">
                <p style="margin-bottom: 20px;">Apakah Anda yakin ingin menghapus pengguna ini? Tindakan ini tidak dapat dibatalkan.</p>
                <div class="form-group">
                    <label>Alasan Penghapusan</label>
                    <textarea name="reason" placeholder="Masukkan alasan penghapusan..." required></textarea>
                </div>
                <div class="modal-actions">
                    <button type="button" class="btn btn-cancel" onclick="closeModal('deleteModal')">Batal</button>
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            if (!event.target.closest('.action-cell')) {
                document.querySelectorAll('.dropdown-menu').forEach(menu => {
                    menu.classList.remove('active');
                });
            }
        });

        function toggleDropdown(event, userId) {
            event.stopPropagation();
            const dropdown = document.getElementById('dropdown-' + userId);
            const allDropdowns = document.querySelectorAll('.dropdown-menu');
            
            allDropdowns.forEach(menu => {
                if (menu !== dropdown) {
                    menu.classList.remove('active');
                }
            });
            
            dropdown.classList.toggle('active');
        }

        function openAddUserModal(event) {
            event.preventDefault();
            document.getElementById('addUserModal').classList.add('active');
        }

        function closeModal(modalId) {
            document.getElementById(modalId).classList.remove('active');
        }

        function showAlert(message, type = 'success') {
            const alert = document.getElementById('alertMessage');
            alert.className = `alert alert-${type} active`;
            alert.textContent = message;
            
            setTimeout(() => {
                alert.classList.remove('active');
            }, 5000);
        }

        async function submitAddUser(event) {
            event.preventDefault();
            const form = event.target;
            const formData = new FormData(form);

            try {
                const response = await fetch('/admin/users/add', {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();

                if (result.success) {
                    showAlert('Pengguna berhasil ditambahkan!', 'success');
                    closeModal('addUserModal');
                    form.reset();
                    setTimeout(() => location.reload(), 1500);
                } else {
                    showAlert(result.message || 'Gagal menambahkan pengguna', 'error');
                }
            } catch (error) {
                showAlert('Terjadi kesalahan: ' + error.message, 'error');
            }
        }

        async function editUser(userId) {
            try {
                const response = await fetch(`/admin/users/get/${userId}`);
                const result = await response.json();

                if (result.success) {
                    const user = result.data;
                    document.getElementById('edit_user_id').value = user.user_id;
                    document.getElementById('edit_username').value = user.username;
                    document.getElementById('edit_email').value = user.email;
                    document.getElementById('edit_nama_lengkap').value = user.nama_lengkap || '';
                    document.getElementById('edit_jurusan').value = user.jurusan || '';
                    document.getElementById('edit_angkatan').value = user.angkatan || '';
                    document.getElementById('edit_role').value = user.role;
                    
                    document.getElementById('editUserModal').classList.add('active');
                } else {
                    showAlert('Gagal mengambil data pengguna', 'error');
                }
            } catch (error) {
                showAlert('Terjadi kesalahan: ' + error.message, 'error');
            }
        }

        async function submitEditUser(event) {
            event.preventDefault();
            const form = event.target;
            const formData = new FormData(form);

            try {
                const response = await fetch('/admin/users/update', {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();

                if (result.success) {
                    showAlert('Pengguna berhasil diupdate!', 'success');
                    closeModal('editUserModal');
                    setTimeout(() => location.reload(), 1500);
                } else {
                    showAlert(result.message || 'Gagal mengupdate pengguna', 'error');
                }
            } catch (error) {
                showAlert('Terjadi kesalahan: ' + error.message, 'error');
            }
        }

        function changeStatus(userId, status) {
            document.getElementById('status_user_id').value = userId;
            document.getElementById('status_value').value = status;
            document.getElementById('statusModal').classList.add('active');
        }

        async function submitStatusChange(event) {
            event.preventDefault();
            const form = event.target;
            const formData = new FormData(form);

            try {
                const response = await fetch('/admin/users/change-status', {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();

                if (result.success) {
                    showAlert('Status pengguna berhasil diubah!', 'success');
                    closeModal('statusModal');
                    form.reset();
                    setTimeout(() => location.reload(), 1500);
                } else {
                    showAlert(result.message || 'Gagal mengubah status', 'error');
                }
            } catch (error) {
                showAlert('Terjadi kesalahan: ' + error.message, 'error');
            }
        }

        function deleteUser(userId) {
            document.getElementById('delete_user_id').value = userId;
            document.getElementById('deleteModal').classList.add('active');
        }

        async function submitDeleteUser(event) {
            event.preventDefault();
            const form = event.target;
            const formData = new FormData(form);

            try {
                const response = await fetch('/admin/users/delete', {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();

                if (result.success) {
                    showAlert('Pengguna berhasil dihapus!', 'success');
                    closeModal('deleteModal');
                    form.reset();
                    setTimeout(() => location.reload(), 1500);
                } else {
                    showAlert(result.message || 'Gagal menghapus pengguna', 'error');
                }
            } catch (error) {
                showAlert('Terjadi kesalahan: ' + error.message, 'error');
            }
        }

        async function exportUsers(event) {
    event.preventDefault();
    
    try {
        showAlert('Membuat PDF...', 'success');
        
        const response = await fetch('/admin/users/export-pdf');
        const blob = await response.blob();
        
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = `Daftar_Pengguna_${new Date().toISOString().split('T')[0]}.pdf`;
        document.body.appendChild(a);
        a.click();
        window.URL.revokeObjectURL(url);
        document.body.removeChild(a);
        
        showAlert('PDF berhasil diunduh!', 'success');
    } catch (error) {
        showAlert('Gagal mengekspor PDF: ' + error.message, 'error');
    }
}

        function toggleFilter() {
            // Implement filter panel toggle
            showAlert('Filter feature coming soon!', 'success');
        }

        // Auto-submit search on input
        let searchTimeout;
        document.querySelector('input[name="search"]').addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                document.getElementById('searchForm').submit();
            }, 500);
        });
    </script>

</body>
</html>