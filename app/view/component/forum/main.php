<div class="container">
    <div class="header">
        <h1>Forum Komunitas</h1>
        <p>Bergabung dan diskusi dengan komunitas kampus</p>
    </div>

    <div class="competitions-grid">
        <?php foreach ($komunitas_list as $komunitas): ?>
            <!-- Card Komunitas -->
            <div class="competition-card"
                onclick="window.location.href='/forum/chat?id=<?php echo $komunitas['komunitas_id']; ?>'"
                style="cursor: pointer;">
                <div class="card-header">
                    <div class="card-title">
                        <span class="trophy-icon">
                            <?php if ($komunitas['icon_type'] == 'globe'): ?>
                                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="white"
                                    style="background-color: #0a1f44; border-radius: 50%; padding: 8px;">
                                    <circle cx="12" cy="12" r="10" stroke-width="2" />
                                    <path
                                        d="M2 12h20M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"
                                        stroke-width="2" />
                                </svg>
                            <?php else: ?>
                                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="white"
                                    style="background-color: #0a1f44; border-radius: 50%; padding: 8px;">
                                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <circle cx="9" cy="7" r="4" stroke-width="2" />
                                    <path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            <?php endif; ?>
                        </span>
                        <div>
                            <h3><?php echo htmlspecialchars($komunitas['nama_komunitas']); ?></h3>
                            <p class="card-description" style="margin: 0;">
                                <?php echo htmlspecialchars($komunitas['deskripsi']); ?>
                            </p>
                        </div>
                    </div>
                </div>

                <div style="margin: 15px 0;">
                    <span class="category-badge badge-technology">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            style="display: inline; vertical-align: middle; margin-right: 4px;">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <circle cx="9" cy="7" r="4" stroke-width="2" />
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <?php echo $komunitas['jumlah_anggota']; ?>
                    </span>

                    <?php if ($komunitas['jurusan_filter']): ?>
                        <span class="role-badge" style="margin-left: 8px; font-size: 0.85rem;">
                            <?php echo htmlspecialchars($komunitas['jurusan_filter']); ?>
                        </span>
                    <?php endif; ?>
                </div>

                <div style="border-top: 1px solid #e0e0e0; padding-top: 15px; margin-top: 15px;">
                    <?php if ($komunitas['latest_message']): ?>
                        <p style="margin: 0; color: #495057; font-size: 0.95rem; font-weight: 500;">
                            <?php echo htmlspecialchars(substr($komunitas['latest_message'], 0, 50)) . (strlen($komunitas['latest_message']) > 50 ? '...' : ''); ?>
                        </p>
                        <p style="margin: 5px 0 0 0; color: #6c757d; font-size: 0.85rem;">
                            <?php
                            $info = explode(' - ', $komunitas['latest_message_info'] ?? '');
                            echo count($info) > 1 ? $info[1] : 'Baru saja';
                            ?>
                        </p>
                    <?php else: ?>
                        <p style="margin: 0; color: #6c757d; font-size: 0.95rem; font-style: italic;">
                            Belum ada pesan
                        </p>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>