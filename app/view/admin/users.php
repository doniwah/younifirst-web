<?php
// Admin Users List View
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Daftar Pengguna' ?></title>

    <!-- CSS Files -->
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
        z-index: 9999;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        backdrop-filter: blur(5px);
        animation: fadeIn 0.3s ease;
        align-items: center;
        justify-content: center;
    }

    .modal.active {
        display: flex;
    }

    .modal-content {
        background: linear-gradient(135deg, #ffffff, #f8fafc);
        padding: 32px;
        border-radius: 16px;
        width: 90%;
        max-width: 500px;
        max-height: 90vh;
        overflow-y: auto;
        border: 1px solid #e5e7eb;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        position: relative;
        animation: modalSlideIn 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
        padding-bottom: 16px;
        border-bottom: 1px solid #e5e7eb;
    }

    .modal-header h2 {
        margin: 0;
        font-size: 24px;
        font-weight: 700;
        color: #111827;
        background: linear-gradient(135deg, #4f46e5, #7c3aed);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .close-modal {
        background: #f3f4f6;
        border: none;
        border-radius: 8px;
        font-size: 24px;
        cursor: pointer;
        color: #6b7280;
        line-height: 1;
        padding: 8px;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
    }

    .close-modal:hover {
        background: #ef4444;
        color: white;
        transform: rotate(90deg);
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: #374151;
        font-size: 14px;
    }

    .form-group input,
    .form-group select,
    .form-group textarea {
        width: 100%;
        padding: 12px 16px;
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        font-size: 14px;
        transition: all 0.3s;
        background: white;
        color: #111827;
    }

    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: #4f46e5;
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
    }

    .form-group textarea {
        resize: vertical;
        min-height: 100px;
        font-family: inherit;
    }

    .modal-actions {
        display: flex;
        gap: 12px;
        justify-content: flex-end;
        margin-top: 32px;
        padding-top: 20px;
        border-top: 1px solid #e5e7eb;
    }

    .btn {
        padding: 12px 24px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-size: 14px;
        font-weight: 600;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .btn-cancel {
        background: #f3f4f6;
        color: #6b7280;
    }

    .btn-cancel:hover {
        background: #e5e7eb;
        transform: translateY(-2px);
    }

    .btn-submit {
        background: linear-gradient(135deg, #4f46e5, #7c3aed);
        color: white;
        box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
    }

    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(79, 70, 229, 0.4);
    }

    .btn-danger {
        background: linear-gradient(135deg, #ef4444, #dc2626);
        color: white;
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
    }

    .btn-danger:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(239, 68, 68, 0.4);
    }

    /* Alert Styles */
    .alert {
        padding: 16px 20px;
        border-radius: 12px;
        margin-bottom: 24px;
        display: none;
        align-items: center;
        gap: 12px;
        animation: slideDown 0.3s ease;
        border: 1px solid;
    }

    .alert.active {
        display: flex;
    }

    .alert-success {
        background: linear-gradient(135deg, #d1fae5, #a7f3d0);
        color: #065f46;
        border-color: #34d399;
    }

    .alert-error {
        background: linear-gradient(135deg, #fee2e2, #fecaca);
        color: #7f1d1d;
        border-color: #f87171;
    }

    .alert i {
        font-size: 20px;
    }

    /* Animation Keyframes */
    @keyframes modalSlideIn {
        from {
            opacity: 0;
            transform: translateY(-50px) scale(0.95);
        }

        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Emergency styles */
    .users-container {
        padding: 32px !important;
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%) !important;
        min-height: 100vh !important;
    }

    .action-buttons {
        display: flex !important;
        gap: 12px !important;
        align-items: center !important;
    }

    .add-user-btn,
    .export-btn {
        display: inline-flex !important;
        visibility: visible !important;
        opacity: 1 !important;
        position: relative !important;
        z-index: 1 !important;
        pointer-events: auto !important;
    }

    .add-user-btn {
        padding: 12px 24px !important;
        background: linear-gradient(135deg, #4f46e5, #7c3aed) !important;
        border: none !important;
        border-radius: 12px !important;
        color: white !important;
        font-weight: 600 !important;
        cursor: pointer !important;
        text-decoration: none !important;
        display: inline-flex !important;
        align-items: center !important;
        gap: 8px !important;
        transition: all 0.3s !important;
        box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3) !important;
    }

    .export-btn {
        padding: 12px 20px !important;
        background: white !important;
        border: 2px solid #e5e7eb !important;
        border-radius: 12px !important;
        color: #374151 !important;
        font-weight: 500 !important;
        cursor: pointer !important;
        text-decoration: none !important;
        display: inline-flex !important;
        align-items: center !important;
        gap: 8px !important;
        transition: all 0.3s !important;
    }

    body {
        font-family: "Poppins", sans-serif !important;
        font-weight: 400;
        line-height: 1.5;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
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
                <div class="page-header-left">
                    <h1 class="page-title">Kelola Pengguna</h1>
                    <p class="page-subtitle">Kelola semua pengguna sistem YouniFirst</p>
                </div>
                <div class="search-filter-group">
                    <form method="GET" action="/admin/users" class="search-box" id="searchForm">
                        <i class="bi bi-search"></i>
                        <input type="text" name="search"
                            placeholder="Cari pengguna berdasarkan nama, email, atau role..."
                            value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                    </form>
                    <button class="filter-btn" onclick="toggleFilter()">
                        <i class="bi bi-funnel"></i>
                        <span>Filter</span>
                    </button>
                </div>
                <div class="action-buttons">
                    <button class="export-btn" onclick="exportUsers(event)">
                        <i class="bi bi-file-earmark-arrow-down"></i>
                        Export PDF
                    </button>
                    <button class="add-user-btn" onclick="openAddUserModal(event)">
                        <i class="bi bi-plus-circle"></i>
                        Tambah Pengguna
                    </button>
                </div>
            </div>

            <!-- Stats Row -->
            <div class="stats-row">
                <div class="stat-card">
                    <div class="stat-title">Total Pengguna</div>
                    <div class="stat-value"><?= number_format($stats['total'] ?? 0) ?></div>
                    <div class="stat-trend positive">
                        <i class="bi bi-arrow-up-right"></i>
                        <span><?= $stats['growth'] ?? '0' ?>% dari bulan lalu</span>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-title">Aktif</div>
                    <div class="stat-value green"><?= number_format($stats['active'] ?? 0) ?></div>
                    <div class="stat-trend">
                        <span>Online: <?= $stats['online'] ?? '0' ?></span>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-title">Suspended</div>
                    <div class="stat-value orange"><?= number_format($stats['suspended'] ?? 0) ?></div>
                    <div class="stat-trend">
                        <span>Perlu perhatian</span>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-title">Diblokir</div>
                    <div class="stat-value red"><?= number_format($stats['blocked'] ?? 0) ?></div>
                    <div class="stat-trend">
                        <span>Selamanya</span>
                    </div>
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
                                    <?php
                                                // Ambil inisial dari nama lengkap atau username
                                                $fullName = $user['nama_lengkap'] ?? $user['username'];
                                                $initials = '';
                                                $nameParts = explode(' ', $fullName);
                                                if (count($nameParts) >= 2) {
                                                    $initials = strtoupper(substr($nameParts[0], 0, 1) . substr($nameParts[1], 0, 1));
                                                } else {
                                                    $initials = strtoupper(substr($fullName, 0, 2));
                                                }
                                                ?>
                                    <div class="user-avatar-initial" data-initial="<?= substr($initials, 0, 1) ?>">
                                        <?= $initials ?>
                                    </div>
                                    <?php endif; ?>
                                    <div class="user-details">
                                        <span
                                            class="user-name"><?= htmlspecialchars($user['nama_lengkap'] ?? $user['username']) ?></span>
                                        <span class="user-email"><?= htmlspecialchars($user['email']) ?></span>
                                        <?php if (!empty($user['jurusan'])): ?>
                                        <span class="user-meta">
                                            <i class="bi bi-mortarboard"></i> <?= htmlspecialchars($user['jurusan']) ?>
                                        </span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="role-badge" data-role="<?= strtolower($user['role']) ?>">
                                    <?php if ($user['role'] == 'admin'): ?>
                                    <i class="bi bi-shield-check"></i>
                                    <?php elseif ($user['role'] == 'satpam'): ?>
                                    <i class="bi bi-shield"></i>
                                    <?php else: ?>
                                    <i class="bi bi-person"></i>
                                    <?php endif; ?>
                                    <?= ucfirst($user['role']) ?>
                                </span>
                            </td>
                            <td>
                                <span class="status-badge <?= strtolower($user['status']) ?>">
                                    <?php if ($user['status'] == 'aktif'): ?>
                                    <i class="bi bi-check-circle"></i> Aktif
                                    <?php elseif ($user['status'] == 'suspended'): ?>
                                    <i class="bi bi-pause-circle"></i> Suspended
                                    <?php else: ?>
                                    <i class="bi bi-x-circle"></i> Blocked
                                    <?php endif; ?>
                                </span>
                            </td>
                            <td class="text-cell">
                                <div class="date-info">
                                    <i class="bi bi-calendar-check"></i>
                                    <?= date('d M Y', strtotime($user['joined_date'] ?? 'N/A')) ?>
                                </div>
                            </td>
                            <td class="text-cell">
                                <div class="last-active">
                                    <?php if (!empty($user['last_active']) && $user['last_active'] != 'Never'): ?>
                                    <i class="bi bi-clock-history"></i>
                                    <?= $user['last_active'] ?>
                                    <?php else: ?>
                                    <span style="color: #9ca3af;">Belum pernah</span>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td class="action-cell">
                                <button class="more-btn" onclick="toggleDropdown(event, '<?= $user['user_id'] ?>')">
                                    <i class="bi bi-three-dots-vertical"></i>
                                </button>
                                <div class="dropdown-menu" id="dropdown-<?= $user['user_id'] ?>">
                                    <button class="dropdown-item" onclick="editUser('<?= $user['user_id'] ?>')">
                                        <i class="bi bi-pencil"></i> Edit
                                    </button>
                                    <button class="dropdown-item"
                                        onclick="changeStatus('<?= $user['user_id'] ?>', 'active')">
                                        <i class="bi bi-check-circle"></i> Aktifkan
                                    </button>
                                    <button class="dropdown-item"
                                        onclick="changeStatus('<?= $user['user_id'] ?>', 'suspended')">
                                        <i class="bi bi-pause-circle"></i> Suspend
                                    </button>
                                    <button class="dropdown-item"
                                        onclick="changeStatus('<?= $user['user_id'] ?>', 'blocked')">
                                        <i class="bi bi-x-circle"></i> Block
                                    </button>
                                    <button class="dropdown-item danger"
                                        onclick="deleteUser('<?= $user['user_id'] ?>')">
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
                <p style="margin-bottom: 20px;">Apakah Anda yakin ingin menghapus pengguna ini? Tindakan ini tidak dapat
                    dibatalkan.</p>
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
    // DOM Elements
    const searchInput = document.querySelector('input[name="search"]');
    let searchTimeout;

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

    // Modal Functions
    function openAddUserModal(event) {
        event.preventDefault();
        document.getElementById('addUserModal').classList.add('active');
    }

    function closeModal(modalId) {
        document.getElementById(modalId).classList.remove('active');
    }

    // Alert System
    function showAlert(message, type = 'success') {
        const alert = document.getElementById('alertMessage');
        alert.className = `alert alert-${type} active`;
        alert.innerHTML = `
            <i class="bi bi-${type === 'success' ? 'check-circle' : 'exclamation-triangle'}"></i>
            <span>${message}</span>
        `;

        setTimeout(() => {
            alert.classList.remove('active');
        }, 5000);
    }

    // User Management Functions
    async function submitAddUser(event) {
        event.preventDefault();
        const form = event.target;
        const formData = new FormData(form);

        try {
            showAlert('Menambahkan pengguna...', 'success');
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
            showAlert('Memuat data pengguna...', 'success');
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
                showAlert('Data pengguna dimuat', 'success');
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
            showAlert('Mengupdate pengguna...', 'success');
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
            showAlert('Mengubah status pengguna...', 'success');
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
            showAlert('Menghapus pengguna...', 'success');
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
        showAlert('Filter feature coming soon!', 'success');
    }

    // Search with debounce
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                if (this.value.length >= 2 || this.value.length === 0) {
                    document.getElementById('searchForm').submit();
                }
            }, 800);
        });

        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                document.getElementById('searchForm').submit();
            }
        });
    }

    // Hover effects for stat cards
    document.querySelectorAll('.stat-card').forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-8px)';
        });

        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
    </script>

</body>

</html>