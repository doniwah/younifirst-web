<?php
// File: app/view/component/admin/teams.php
// Komponen untuk admin mengelola tim (approve/reject)

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: /dashboard');
    exit();
}

require_once __DIR__ . '/../../../Models/Team.php';

$team = new App\Models\Team();
$stmt = $team->readAllForAdmin();
$teams = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="admin-teams-section"
    style="background: #fff; padding: 25px; border-radius: 12px; margin-bottom: 25px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
    <h2 style="margin: 0 0 20px 0; font-size: 24px; color: #333;">
        <i class="bi bi-people-fill" style="color: #2196F3; margin-right: 10px;"></i>
        Manajemen Tim
    </h2>

    <?php if (!empty($teams)): ?>
        <div class="teams-table" style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f8f9fa; border-bottom: 2px solid #dee2e6;">
                        <th style="padding: 12px; text-align: left; font-weight: 600; color: #495057;">Tim</th>
                        <th style="padding: 12px; text-align: left; font-weight: 600; color: #495057;">Lomba</th>
                        <th style="padding: 12px; text-align: left; font-weight: 600; color: #495057;">Anggota</th>
                        <th style="padding: 12px; text-align: left; font-weight: 600; color: #495057;">Status</th>
                        <th style="padding: 12px; text-align: left; font-weight: 600; color: #495057;">Dibuat</th>
                        <th style="padding: 12px; text-align: center; font-weight: 600; color: #495057;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($teams as $tm): ?>
                        <tr style="border-bottom: 1px solid #dee2e6;">
                            <td style="padding: 12px;">
                                <strong style="color: #333;"><?= htmlspecialchars($tm['nama_tim']) ?></strong>
                                <?php if (!empty($tm['deskripsi'])): ?>
                                    <br>
                                    <small style="color: #666;"><?= htmlspecialchars(substr($tm['deskripsi'], 0, 60)) ?>...</small>
                                <?php endif; ?>
                            </td>
                            <td style="padding: 12px;">
                                <span style="color: #666;"><?= htmlspecialchars($tm['nama_lomba']) ?></span>
                            </td>
                            <td style="padding: 12px;">
                                <span
                                    style="background: #e3f2fd; padding: 4px 10px; border-radius: 12px; font-size: 12px; color: #1976d2;">
                                    <?= $tm['anggota_saat_ini'] ?>/<?= $tm['maksimal_anggota'] ?>
                                </span>
                            </td>
                            <td style="padding: 12px;">
                                <?php
                                $statusColors = [
                                    'waiting' => ['bg' => '#fff3cd', 'text' => '#856404', 'label' => 'Menunggu'],
                                    'confirm' => ['bg' => '#d4edda', 'text' => '#155724', 'label' => 'Disetujui'],
                                    'rejected' => ['bg' => '#f8d7da', 'text' => '#721c24', 'label' => 'Ditolak']
                                ];
                                $status = $statusColors[$tm['status']] ?? ['bg' => '#e2e3e5', 'text' => '#383d41', 'label' => $tm['status']];
                                ?>
                                <span
                                    style="background: <?= $status['bg'] ?>; color: <?= $status['text'] ?>; padding: 5px 12px; border-radius: 12px; font-size: 12px; font-weight: 500;">
                                    <?= $status['label'] ?>
                                </span>
                            </td>
                            <td style="padding: 12px;">
                                <small style="color: #666;"><?= date('d M Y', strtotime($tm['created_at'])) ?></small>
                            </td>
                            <td style="padding: 12px; text-align: center;">
                                <div style="display: flex; gap: 8px; justify-content: center;">
                                    <button onclick="viewTeamDetail(<?= $tm['tim_id'] ?>)"
                                        style="padding: 6px 12px; background: #2196F3; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 12px;">
                                        <i class="bi bi-eye"></i>
                                    </button>

                                    <?php if ($tm['status'] === 'waiting'): ?>
                                        <form method="POST" action="/team/<?= $tm['tim_id'] ?>/approve" style="display: inline;">
                                            <button type="submit" onclick="return confirm('Approve tim ini?')"
                                                style="padding: 6px 12px; background: #4CAF50; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 12px;">
                                                <i class="bi bi-check-lg"></i>
                                            </button>
                                        </form>
                                        <form method="POST" action="/team/<?= $tm['tim_id'] ?>/reject" style="display: inline;">
                                            <button type="submit" onclick="return confirm('Reject tim ini?')"
                                                style="padding: 6px 12px; background: #f44336; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 12px;">
                                                <i class="bi bi-x-lg"></i>
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div style="text-align: center; padding: 40px; color: #666;">
            <i class="bi bi-inbox" style="font-size: 48px; color: #ccc;"></i>
            <p style="margin-top: 15px;">Belum ada tim yang terdaftar.</p>
        </div>
    <?php endif; ?>
</div>

<!-- Modal Team Detail -->
<div id="teamDetailModal" class="modal" style="display: none;">
    <div class="modal-content" style="max-width: 700px;">
        <span class="close" onclick="closeTeamDetail()"><i class="bi bi-x"></i></span>
        <h2 class="title_pop">Detail Tim</h2>

        <div id="teamDetailContent">
            <!-- Will be populated by JavaScript -->
        </div>
    </div>
</div>

<script>
    function viewTeamDetail(teamId) {
        fetch(`/team/detail/${teamId}`)
            .then(response => response.json())
            .then(data => {
                const statusLabels = {
                    'waiting': 'Menunggu Persetujuan',
                    'confirm': 'Disetujui',
                    'rejected': 'Ditolak'
                };

                const statusColors = {
                    'waiting': '#ffc107',
                    'confirm': '#4CAF50',
                    'rejected': '#f44336'
                };

                let rolesHtml = '';
                if (data.role_dibutuhkan) {
                    const roles = data.role_dibutuhkan.split(',');
                    rolesHtml = roles.map(role =>
                        `<span style="background: #e3f2fd; padding: 6px 12px; border-radius: 16px; font-size: 13px; display: inline-block; margin: 4px;">${role.trim()}</span>`
                    ).join('');
                }

                const content = `
                <div style="padding: 20px 0;">
                    <div style="background: #f8f9fa; padding: 20px; border-radius: 8px; margin-bottom: 20px;">
                        <h3 style="margin: 0 0 15px 0; color: #333;">${data.nama_tim}</h3>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                            <div>
                                <p style="margin: 0; color: #666; font-size: 14px;">Status</p>
                                <span style="background: ${statusColors[data.status]}; color: white; padding: 6px 14px; border-radius: 16px; font-size: 13px; display: inline-block; margin-top: 5px;">
                                    ${statusLabels[data.status]}
                                </span>
                            </div>
                            <div>
                                <p style="margin: 0; color: #666; font-size: 14px;">Anggota</p>
                                <p style="margin: 5px 0 0 0; font-weight: 600; color: #333; font-size: 16px;">
                                    ${data.anggota_saat_ini}/${data.maksimal_anggota} orang
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div style="margin-bottom: 20px;">
                        <h4 style="color: #333; margin-bottom: 10px;">Deskripsi Tim</h4>
                        <p style="background: #f8f9fa; padding: 15px; border-radius: 8px; line-height: 1.6; color: #555;">
                            ${data.deskripsi || 'Tidak ada deskripsi'}
                        </p>
                    </div>
                    
                    ${data.role_dibutuhkan ? `
                        <div style="margin-bottom: 20px;">
                            <h4 style="color: #333; margin-bottom: 10px;">Role yang Dibutuhkan</h4>
                            <div>${rolesHtml}</div>
                        </div>
                    ` : ''}
                    
                    ${data.status === 'waiting' ? `
                        <div style="display: flex; gap: 10px; margin-top: 30px; padding-top: 20px; border-top: 1px solid #dee2e6;">
                            <form method="POST" action="/team/${data.tim_id}/approve" style="flex: 1;">
                                <button type="submit" 
                                        onclick="return confirm('Approve tim ini?')"
                                        style="width: 100%; padding: 12px; background: #4CAF50; color: white; border: none; border-radius: 8px; cursor: pointer; font-size: 14px; font-weight: 500;">
                                    <i class="bi bi-check-lg"></i> Approve Tim
                                </button>
                            </form>
                            <form method="POST" action="/team/${data.tim_id}/reject" style="flex: 1;">
                                <button type="submit" 
                                        onclick="return confirm('Reject tim ini?')"
                                        style="width: 100%; padding: 12px; background: #f44336; color: white; border: none; border-radius: 8px; cursor: pointer; font-size: 14px; font-weight: 500;">
                                    <i class="bi bi-x-lg"></i> Reject Tim
                                </button>
                            </form>
                        </div>
                    ` : ''}
                </div>
            `;

                document.getElementById('teamDetailContent').innerHTML = content;
                document.getElementById('teamDetailModal').style.display = 'block';
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Gagal memuat detail tim');
            });
    }

    function closeTeamDetail() {
        document.getElementById('teamDetailModal').style.display = 'none';
    }

    // Close modal when clicking outside
    window.addEventListener('click', function(event) {
        const modal = document.getElementById('teamDetailModal');
        if (event.target == modal) {
            closeTeamDetail();
        }
    });
</script>