<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($komunitas['nama_komunitas']); ?> - Forum - YouNiFirst</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="/css/forum.css">
    <link rel="stylesheet" href="/css/forum-dropdown.css">
    <style>
        body {
            background-color: #f5f7fa;
            margin: 0;
            font-family: 'Inter', sans-serif;
        }

        .forum-layout-container {
            display: flex;
            height: 100vh;
            max-width: 1600px;
            margin: 0 auto;
            background: white;
            box-shadow: 0 0 20px rgba(0,0,0,0.05);
        }

        /* Sidebar Styles */
        .forum-sidebar-left {
            width: 350px;
            border-right: 1px solid #e0e0e0;
            display: flex;
            flex-direction: column;
            background: #fff;
            flex-shrink: 0;
        }

        .sidebar-header {
            padding: 20px;
            border-bottom: 1px solid #f0f0f0;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .back-btn {
            background: none;
            border: none;
            cursor: pointer;
            padding: 5px;
            color: #333;
        }

        .community-info {
            padding: 20px;
        }

        .community-header {
            display: flex;
            gap: 15px;
            margin-bottom: 15px;
        }

        .community-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            object-fit: cover;
        }

        .community-details h2 {
            margin: 0 0 5px 0;
            font-size: 1.1rem;
            color: #1a1a1a;
        }

        .community-meta {
            font-size: 0.85rem;
            color: #666;
        }

        .tags {
            display: flex;
            gap: 8px;
            margin-bottom: 15px;
        }

        .tag {
            background: #f0f2f5;
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 0.8rem;
            color: #555;
        }

        .members-preview {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 20px;
        }

        .member-avatars {
            display: flex;
        }

        .member-avatar-small {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            border: 2px solid #fff;
            margin-left: -8px;
            background: #ddd;
        }

        .member-avatar-small:first-child {
            margin-left: 0;
        }

        .member-count {
            font-size: 0.9rem;
            color: #666;
            font-weight: 500;
        }

        .add-group-btn {
            width: 100%;
            padding: 12px;
            background: #4A90E2;
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
            margin-bottom: 20px;
        }

        .add-group-btn:hover {
            background: #357abd;
        }

        .groups-list {
            flex: 1;
            overflow-y: auto;
            padding: 0 10px;
        }

        .group-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 15px;
            border-radius: 10px;
            cursor: pointer;
            transition: background 0.2s;
            margin-bottom: 5px;
            text-decoration: none;
            color: inherit;
        }

        .group-item:hover {
            background: #f5f7fa;
        }

        .group-item.active {
            background: #e6f0ff;
        }

        .group-icon {
            width: 40px;
            height: 40px;
            background: #e1e8ed;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #555;
            font-size: 1.2rem;
        }

        .group-item.active .group-icon {
            background: #4A90E2;
            color: white;
        }

        .group-info {
            flex: 1;
            min-width: 0;
        }

        .group-name {
            font-weight: 600;
            font-size: 0.95rem;
            color: #333;
            margin-bottom: 2px;
        }

        .group-last-msg {
            font-size: 0.8rem;
            color: #888;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .group-meta {
            text-align: right;
            font-size: 0.75rem;
            color: #999;
        }

        .unread-badge {
            background: #ff3b30;
            color: white;
            font-size: 0.7rem;
            padding: 2px 6px;
            border-radius: 10px;
            display: inline-block;
            margin-top: 4px;
        }

        /* Chat Main Area */
        .chat-main {
            flex: 1;
            display: flex;
            flex-direction: column;
            background: #f9f9f9;
        }

        .chat-header-main {
            padding: 15px 25px;
            background: white;
            border-bottom: 1px solid #e0e0e0;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .chat-header-info h3 {
            margin: 0;
            font-size: 1.1rem;
            color: #333;
        }

        .chat-header-info span {
            font-size: 0.85rem;
            color: #666;
        }

        .chat-messages-area {
            flex: 1;
            padding: 20px;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .chat-input-area {
            padding: 20px;
            background: white;
            border-top: 1px solid #e0e0e0;
        }

        .input-wrapper {
            display: flex;
            gap: 10px;
            background: #f0f2f5;
            padding: 10px;
            border-radius: 12px;
            align-items: center;
        }

        .chat-input-field {
            flex: 1;
            border: none;
            background: transparent;
            padding: 8px;
            font-size: 0.95rem;
            outline: none;
        }

        .attach-btn, .send-msg-btn {
            background: none;
            border: none;
            cursor: pointer;
            color: #666;
            font-size: 1.2rem;
            padding: 5px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .send-msg-btn {
            color: #4A90E2;
        }

        .send-msg-btn:hover {
            color: #357abd;
        }

        /* Message Bubbles */
        .message {
            display: flex;
            gap: 15px;
            max-width: 70%;
        }

        .message.sent {
            align-self: flex-end;
            flex-direction: row-reverse;
        }

        .msg-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #ddd;
            flex-shrink: 0;
        }

        .msg-content {
            display: flex;
            flex-direction: column;
        }

        .msg-sender {
            font-size: 0.85rem;
            font-weight: 600;
            margin-bottom: 4px;
            color: #4A90E2;
        }

        .message.sent .msg-sender {
            display: none;
        }

        .msg-bubble {
            background: white;
            padding: 12px 16px;
            border-radius: 0 12px 12px 12px;
            box-shadow: 0 1px 2px rgba(0,0,0,0.1);
            font-size: 0.95rem;
            line-height: 1.5;
            position: relative;
        }

        .message.sent .msg-bubble {
            background: #dcf8c6; /* WhatsApp style green/blue */
            background: #cce5ff;
            border-radius: 12px 0 12px 12px;
        }

        .msg-time {
            font-size: 0.7rem;
            color: #999;
            text-align: right;
            margin-top: 4px;
        }

        /* Modal */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background: white;
            padding: 30px;
            border-radius: 12px;
            width: 400px;
        }

        .modal h3 {
            margin-top: 0;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
        }

        .form-control {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 6px;
            box-sizing: border-box;
        }

        .modal-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 20px;
        }

        .btn-cancel {
            background: #f0f0f0;
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            cursor: pointer;
        }

        .btn-submit {
            background: #4A90E2;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            cursor: pointer;
        }

        /* Image Styles */
        .group-icon-img {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            object-fit: cover;
        }

        .message-image {
            max-width: 300px;
            max-height: 300px;
            border-radius: 8px;
            margin-bottom: 8px;
            display: block;
        }
    </style>
</head>

<body>
    <div class="forum-layout-container">
        <!-- Left Sidebar -->
        <div class="forum-sidebar-left">
            <div class="sidebar-header">
                <button class="back-btn" onclick="window.location.href='/forum'">
                    <i class="bi bi-arrow-left" style="font-size: 1.2rem;"></i>
                </button>
                <h2 style="margin: 0; font-size: 1.2rem;">Forum</h2>
                <div style="flex: 1;"></div>
                <button class="back-btn"><i class="bi bi-three-dots-vertical"></i></button>
            </div>

            <div class="community-info">
                <div class="community-header">
                    <img src="<?= htmlspecialchars($komunitas['image_url'] ?? 'https://via.placeholder.com/60') ?>" alt="Icon" class="community-icon">
                    <div class="community-details">
                        <h2><?= htmlspecialchars($komunitas['nama_komunitas']) ?></h2>
                        <div class="community-meta">Forum • <?= count($groups) ?> grup</div>
                    </div>
                </div>

                <div class="tags">
                    <span class="tag">Diskusi</span>
                    <span class="tag">Projek</span>
                    <span class="tag">Tim</span>
                </div>

                <div class="members-preview">
                    <div class="member-avatars">
                        <!-- Mock avatars -->
                        <div class="member-avatar-small" style="background-image: url('https://api.dicebear.com/7.x/avataaars/svg?seed=1'); background-size: cover;"></div>
                        <div class="member-avatar-small" style="background-image: url('https://api.dicebear.com/7.x/avataaars/svg?seed=2'); background-size: cover;"></div>
                        <div class="member-avatar-small" style="background-image: url('https://api.dicebear.com/7.x/avataaars/svg?seed=3'); background-size: cover;"></div>
                    </div>
                    <div class="member-count"><?= htmlspecialchars($komunitas['jumlah_anggota']) ?> anggota</div>
                </div>

                <button class="add-group-btn" id="btnAddGroup">Tambah Grup</button>
            </div>

            <div class="groups-list">
                <?php foreach ($groups as $group): ?>
                <a href="/forum/chat?id=<?= $komunitas['komunitas_id'] ?>&group_id=<?= $group['group_id'] ?>" 
                   class="group-item <?= ($current_group && $current_group['group_id'] == $group['group_id']) ? 'active' : '' ?>">
                    <div class="group-icon">
                        <?php if (!empty($group['image_url'])): ?>
                            <img src="<?= htmlspecialchars($group['image_url']) ?>" class="group-icon-img">
                        <?php else: ?>
                            <i class="bi bi-<?= htmlspecialchars($group['icon'] ?? 'hash') ?>"></i>
                        <?php endif; ?>
                    </div>
                    <div class="group-info">
                        <div class="group-name"><?= htmlspecialchars($group['name']) ?></div>
                        <div class="group-last-msg">
                            <?php if ($group['last_message']): ?>
                                <i class="bi bi-check2-all" style="color: #4A90E2;"></i> <?= htmlspecialchars($group['last_message']) ?>
                            <?php else: ?>
                                Belum ada pesan
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="group-meta">
                        <div><?= $group['last_message_time'] ? date('H:i', strtotime($group['last_message_time'])) : '' ?></div>
                        <?php if ($group['message_count'] > 0 && false): // Mock unread ?>
                        <div class="unread-badge">2</div>
                        <?php endif; ?>
                    </div>
                </a>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Right Chat Area -->
        <div class="chat-main">
            <div class="chat-header-main">
                <div class="chat-header-info">
                    <h3><?= htmlspecialchars($komunitas['nama_komunitas']) ?></h3>
                    <span><?= htmlspecialchars($current_group['name'] ?? 'Pilih Grup') ?></span>
                </div>
                <div class="dropdown">
                    <button class="back-btn" onclick="toggleDropdown()" id="dropdownBtn"><i class="bi bi-three-dots-vertical"></i></button>
                    <div id="myDropdown" class="dropdown-content">
                        <?php if ($current_group): ?>
                        <a href="#" onclick="openEditGroupModal('<?= $current_group['group_id'] ?>', '<?= htmlspecialchars($current_group['name']) ?>', '<?= $current_group['image_url'] ?? '' ?>')">
                            <i class="bi bi-pencil"></i> Edit Group
                        </a>
                        <?php else: ?>
                        <a href="#" style="color: #999; cursor: not-allowed;">Pilih grup dulu</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="chat-messages-area" id="chatMessages">
                <?php foreach ($messages as $msg): ?>
                <?php $is_sent = ($msg['user_id'] == $current_user->user_id); ?>
                <div class="message <?= $is_sent ? 'sent' : '' ?>">
                    <img src="https://api.dicebear.com/7.x/avataaars/svg?seed=<?= htmlspecialchars($msg['username']) ?>" class="msg-avatar">
                    <div class="msg-content">
                        <div class="msg-sender"><?= htmlspecialchars($msg['username']) ?></div>
                        <div class="msg-bubble">
                            <?php if (!empty($msg['image_url'])): ?>
                                <img src="<?= htmlspecialchars($msg['image_url']) ?>" class="message-image" alt="Image">
                            <?php endif; ?>
                            <?php if (!empty($msg['message_text'])): ?>
                                <?= htmlspecialchars($msg['message_text']) ?>
                            <?php endif; ?>
                            <div class="msg-time"><?= date('H:i', strtotime($msg['created_at'])) ?></div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <div class="chat-input-area">
                <div id="imagePreviewContainer" style="display:none; padding: 10px; background: #f0f0f0; border-radius: 8px; margin-bottom: 10px;">
                    <img id="imagePreview" style="max-width: 200px; max-height: 200px; border-radius: 8px;">
                    <button onclick="removeImagePreview()" style="margin-left: 10px; padding: 5px 10px; background: #ff4444; color: white; border: none; border-radius: 4px; cursor: pointer;">×</button>
                </div>
                <div class="input-wrapper">
                    <input type="file" id="messageImage" accept="image/*" style="display:none" onchange="previewMessageImage(this)">
                    <button class="attach-btn" onclick="document.getElementById('messageImage').click()"><i class="bi bi-paperclip"></i></button>
                    <input type="text" class="chat-input-field" id="messageInput" placeholder="Ketik Pesan">
                    <button class="send-msg-btn" id="btnSend"><i class="bi bi-send-fill"></i></button>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Group Modal -->
    <div id="addGroupModal" class="modal">
        <div class="modal-content">
            <h3>Tambah Grup Baru</h3>
            <form action="/forum/create-group" method="POST">
                <input type="hidden" name="komunitas_id" value="<?= $komunitas['komunitas_id'] ?>">
                <div class="form-group">
                    <label>Nama Grup</label>
                    <input type="text" name="group_name" class="form-control" placeholder="Contoh: Diskusi Santai" required>
                </div>
                <div class="modal-actions">
                    <button type="button" class="btn-cancel" id="btnCancelModal">Batal</button>
                    <button type="submit" class="btn-submit">Buat Grup</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Group Modal -->
    <div id="editGroupModal" class="modal">
        <div class="modal-content">
            <h3>Edit Grup</h3>
            <form action="/forum/edit-group" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="komunitas_id" value="<?= $komunitas['komunitas_id'] ?>">
                <input type="hidden" name="group_id" id="edit_group_id">
                
                <div class="image-preview-container" onclick="document.getElementById('group_image').click()" style="cursor: pointer;">
                    <img id="edit_group_image_preview" src="" style="display: none;">
                    <i class="bi bi-camera image-preview-placeholder" id="edit_group_image_placeholder"></i>
                </div>
                <input type="file" name="group_image" id="group_image" accept="image/*" style="display: none;" onchange="previewImage(this)">
                <p style="text-align: center; font-size: 12px; color: #666;">Klik gambar untuk mengganti</p>

                <div class="form-group">
                    <label>Nama Grup</label>
                    <input type="text" name="group_name" id="edit_group_name" class="form-control" required>
                </div>
                <div class="modal-actions">
                    <button type="button" class="btn-cancel" onclick="closeEditGroupModal()">Batal</button>
                    <button type="submit" class="btn-submit">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Dropdown
        function toggleDropdown() {
            document.getElementById("myDropdown").classList.toggle("show");
        }

        // Close dropdown if clicked outside
        window.onclick = function(event) {
            if (!event.target.matches('.back-btn') && !event.target.matches('.bi-three-dots-vertical')) {
                var dropdowns = document.getElementsByClassName("dropdown-content");
                for (var i = 0; i < dropdowns.length; i++) {
                    var openDropdown = dropdowns[i];
                    if (openDropdown.classList.contains('show')) {
                        openDropdown.classList.remove('show');
                    }
                }
            }
            if (event.target == document.getElementById('editGroupModal')) {
                closeEditGroupModal();
            }
        }

        // Edit Group Modal
        function openEditGroupModal(id, name, imageUrl) {
            document.getElementById('editGroupModal').style.display = 'flex';
            document.getElementById('edit_group_id').value = id;
            document.getElementById('edit_group_name').value = name;
            
            const preview = document.getElementById('edit_group_image_preview');
            const placeholder = document.getElementById('edit_group_image_placeholder');
            
            if (imageUrl) {
                preview.src = imageUrl;
                preview.style.display = 'block';
                placeholder.style.display = 'none';
            } else {
                preview.src = '';
                preview.style.display = 'none';
                placeholder.style.display = 'block';
            }
        }

        function closeEditGroupModal() {
            document.getElementById('editGroupModal').style.display = 'none';
        }

        function previewImage(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('edit_group_image_preview').src = e.target.result;
                    document.getElementById('edit_group_image_preview').style.display = 'block';
                    document.getElementById('edit_group_image_placeholder').style.display = 'none';
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        // Image message preview
        function previewMessageImage(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('imagePreview').src = e.target.result;
                    document.getElementById('imagePreviewContainer').style.display = 'block';
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        function removeImagePreview() {
            document.getElementById('messageImage').value = '';
            document.getElementById('imagePreviewContainer').style.display = 'none';
        }

        document.addEventListener('DOMContentLoaded', function() {
            console.log('Forum Inline JS Loaded');
            
            const komunitas_id = <?= json_encode($komunitas['komunitas_id']) ?>;
            const group_id = <?= json_encode($current_group ? $current_group['group_id'] : 0) ?>;
            const currentUsername = <?= json_encode($current_user->username) ?>;

            console.log('Komunitas:', komunitas_id, 'Group:', group_id);

            // Elements
            const addGroupBtn = document.getElementById('btnAddGroup');
            const modal = document.getElementById('addGroupModal');
            const btnCancel = document.getElementById('btnCancelModal');
            const messageInput = document.getElementById('messageInput');
            const btnSend = document.getElementById('btnSend');
            const chatMessages = document.getElementById('chatMessages');

            // Modal Logic
            if (addGroupBtn) {
                addGroupBtn.addEventListener('click', function() {
                    console.log('Add Group Clicked');
                    if (modal) modal.style.display = 'flex';
                });
            } else {
                console.error('Add Group Button Not Found');
            }

            if (btnCancel) {
                btnCancel.addEventListener('click', function() {
                    if (modal) modal.style.display = 'none';
                });
            }

            // Close modal when clicking outside
            window.addEventListener('click', function(event) {
                if (event.target === modal) {
                    modal.style.display = 'none';
                }
            });

            // Chat Logic
            if (messageInput) {
                messageInput.addEventListener('keypress', function(event) {
                    if (event.key === 'Enter') {
                        sendMessage();
                    }
                });
            }

            if (btnSend) {
                btnSend.addEventListener('click', sendMessage);
            }

            function sendMessage() {
                console.log('Sending message...');
                const message = messageInput.value.trim();
                const imageFile = document.getElementById('messageImage').files[0];

                if ((message || imageFile) && group_id > 0) {
                    const formData = new FormData();
                    formData.append('komunitas_id', komunitas_id);
                    formData.append('group_id', group_id);
                    formData.append('message', message);
                    if (imageFile) {
                        formData.append('message_image', imageFile);
                    }

                    fetch('/forum/send-message', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => {
                        console.log('Response status:', response.status);
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        return response.text().then(text => {
                            console.log('Response text:', text);
                            try {
                                return JSON.parse(text);
                            } catch (e) {
                                console.error('JSON parse error:', e);
                                console.error('Response was:', text);
                                throw new Error('Invalid JSON response from server');
                            }
                        });
                    })
                    .then(data => {
                        if (data.success) {
                            let imageHtml = '';
                            if (data.image_url) {
                                imageHtml = `<img src="${data.image_url}" class="message-image" alt="Image">`;
                            }
                            const html = `
                                <div class="message sent">
                                    <img src="https://api.dicebear.com/7.x/avataaars/svg?seed=${currentUsername}" class="msg-avatar">
                                    <div class="msg-content">
                                        <div class="msg-bubble">
                                            ${imageHtml}
                                            ${message ? escapeHtml(message) : ''}
                                            <div class="msg-time">${data.time}</div>
                                        </div>
                                    </div>
                                </div>
                            `;
                            chatMessages.insertAdjacentHTML('beforeend', html);
                            messageInput.value = '';
                            removeImagePreview();
                            chatMessages.scrollTop = chatMessages.scrollHeight;
                        } else {
                            alert('Gagal mengirim pesan: ' + (data.message || 'Unknown error'));
                        }
                    })
                    .catch(error => {
                        console.error('Fetch Error:', error);
                        console.error('Error details:', error.message, error.stack);
                        alert('Terjadi kesalahan koneksi: ' + error.message);
                    });
                } else {
                    if (!message && !imageFile) return;
                    if (group_id <= 0) alert('Error: Group ID tidak valid. Silakan pilih grup terlebih dahulu.');
                }
            }

            function escapeHtml(text) {
                const div = document.createElement('div');
                div.textContent = text;
                return div.innerHTML;
            }

            // Auto scroll
            if (chatMessages) {
                chatMessages.scrollTop = chatMessages.scrollHeight;
            }
        });
    </script>
</body>
</html>