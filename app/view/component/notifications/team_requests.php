<?php
// File: app/view/component/notifications/team_requests.php
// Komponen untuk menampilkan notifikasi pengajuan tim di dashboard

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['user_id'])) {
    require_once __DIR__ . '/../../../Models/TeamMember.php';

    $teamMember = new App\Models\TeamMember();
    $stmt = $teamMember->readPendingByTeamOwner($_SESSION['user_id']);
    $pendingRequests = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!empty($pendingRequests)):
?>
        <div class="notification-section"
            style="background: #fff; padding: 20px; border-radius: 12px; margin-bottom: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 15px;">
                <h3 style="margin: 0; font-size: 18px; color: #333;">
                    <i class="bi bi-bell-fill" style="color: #ff6b6b; margin-right: 8px;"></i>
                    Pengajuan Bergabung Tim
                </h3>
                <span class="badge"
                    style="background: #ff6b6b; color: white; padding: 4px 12px; border-radius: 12px; font-size: 12px;">
                    <?= count($pendingRequests) ?> Baru
                </span>
            </div>

            <div class="requests-list">
                <?php foreach ($pendingRequests as $request): ?>
                    <div class="request-item"
                        style="border-left: 3px solid #4CAF50; padding: 12px; margin-bottom: 10px; background: #f8f9fa; border-radius: 8px;">
                        <div style="display: flex; justify-content: space-between; align-items: start;">
                            <div style="flex: 1;">
                                <p style="margin: 0 0 5px 0; font-weight: 600; color: #333;">
                                    <?= htmlspecialchars($request['username']) ?> mengajukan bergabung
                                </p>
                                <p style="margin: 0 0 5px 0; font-size: 14px; color: #666;">
                                    <strong>Tim:</strong> <?= htmlspecialchars($request['nama_team']) ?>
                                </p>
                                <p style="margin: 0 0 5px 0; font-size: 14px; color: #666;">
                                    <strong>Role:</strong> <span
                                        style="background: #e3f2fd; padding: 2px 8px; border-radius: 4px; font-size: 12px;"><?= htmlspecialchars($request['role']) ?></span>
                                </p>
                                <p style="margin: 5px 0 0 0; font-size: 12px; color: #999;">
                                    <?= date('d M Y H:i', strtotime($request['tanggal_gabung'])) ?>
                                </p>
                            </div>
                            <div style="display: flex; gap: 8px; margin-left: 15px;">
                                <button onclick="viewRequestDetail(<?= $request['detail_id'] ?>)"
                                    style="padding: 6px 12px; background: #2196F3; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 12px;">
                                    <i class="bi bi-eye"></i> Lihat
                                </button>
                                <form method="POST" action="/team/request/<?= $request['detail_id'] ?>/approve"
                                    style="display: inline;">
                                    <button type="submit"
                                        style="padding: 6px 12px; background: #4CAF50; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 12px;">
                                        <i class="bi bi-check-lg"></i> Terima
                                    </button>
                                </form>
                                <form method="POST" action="/team/request/<?= $request['detail_id'] ?>/reject"
                                    style="display: inline;">
                                    <button type="submit"
                                        style="padding: 6px 12px; background: #f44336; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 12px;">
                                        <i class="bi bi-x-lg"></i> Tolak
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Modal Detail Request -->
        <div id="requestDetailModal" class="modal" style="display: none;">
            <div class="modal-content" style="max-width: 600px;">
                <span class="close" onclick="closeRequestDetail()"><i class="bi bi-x"></i></span>
                <h2 class="title_pop">Detail Pengajuan</h2>

                <div id="requestDetailContent">
                    <!-- Will be populated by JavaScript -->
                </div>
            </div>
        </div>

        <script>
            function viewRequestDetail(detailId) {
                fetch(`/team/request/${detailId}/detail`)
                    .then(response => response.json())
                    .then(data => {
                        const content = `
                <div style="padding: 20px 0;">
                    <div style="margin-bottom: 20px;">
                        <h4 style="color: #333; margin-bottom: 8px;">Informasi Pelamar</h4>
                        <p><strong>User ID:</strong> ${data.user_id}</p>
                        <p><strong>Kontak:</strong> ${data.kontak || 'Tidak ada keterangan'}</p>
                    </div>
                    
                    <div style="margin-bottom: 20px;">
                        <h4 style="color: #333; margin-bottom: 8px;">Role yang Diminati</h4>
                        <p style="background: #e3f2fd; padding: 8px 12px; border-radius: 6px; display: inline-block;">
                            ${data.role}
                        </p>
                    </div>
                    
                    <div style="margin-bottom: 20px;">
                        <h4 style="color: #333; margin-bottom: 8px;">Alasan Bergabung</h4>
                        <p style="background: #f8f9fa; padding: 12px; border-radius: 6px; line-height: 1.6;">
                            ${data.alasan_bergabung || 'Tidak ada keterangan'}
                        </p>
                    </div>
                    
                    <div style="margin-bottom: 20px;">
                        <h4 style="color: #333; margin-bottom: 8px;">Keahlian & Pengalaman</h4>
                        <p style="background: #f8f9fa; padding: 12px; border-radius: 6px; line-height: 1.6;">
                            ${data.keahlian_pengalaman || 'Tidak ada keterangan'}
                        </p>
                    </div>
                    
                    ${data.portfolio_link ? `
                        <div style="margin-bottom: 20px;">
                            <h4 style="color: #333; margin-bottom: 8px;">Portfolio/Project</h4>
                            <a href="${data.portfolio_link}" target="_blank" style="color: #2196F3; text-decoration: none;">
                                <i class="bi bi-link-45deg"></i> ${data.portfolio_link}
                            </a>
                        </div>
                    ` : ''}
                    
                    <div style="display: flex; gap: 10px; margin-top: 30px;">
                        <form method="POST" action="/team/request/${detailId}/approve" style="flex: 1;">
                            <button type="submit" style="width: 100%; padding: 12px; background: #4CAF50; color: white; border: none; border-radius: 8px; cursor: pointer; font-size: 14px;">
                                <i class="bi bi-check-lg"></i> Terima Pengajuan
                            </button>
                        </form>
                        <form method="POST" action="/team/request/${detailId}/reject" style="flex: 1;">
                            <button type="submit" style="width: 100%; padding: 12px; background: #f44336; color: white; border: none; border-radius: 8px; cursor: pointer; font-size: 14px;">
                                <i class="bi bi-x-lg"></i> Tolak Pengajuan
                            </button>
                        </form>
                    </div>
                </div>
            `;

                        document.getElementById('requestDetailContent').innerHTML = content;
                        document.getElementById('requestDetailModal').style.display = 'block';
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Gagal memuat detail pengajuan');
                    });
            }

            function closeRequestDetail() {
                document.getElementById('requestDetailModal').style.display = 'none';
            }

            // Close modal when clicking outside
            window.addEventListener('click', function(event) {
                const modal = document.getElementById('requestDetailModal');
                if (event.target == modal) {
                    closeRequestDetail();
                }
            });
        </script>

<?php
    endif; // End if pendingRequests not empty
} // End if session user_id exists
?>