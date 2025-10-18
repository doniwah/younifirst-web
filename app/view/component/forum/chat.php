<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($komunitas['nama_komunitas']); ?> - Forum - YouNiFirst</title>
    <link rel="stylesheet" href="/css/index.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="/css/forum.css">
</head>

<body>
    <?php require_once __DIR__ . "/../../layouts/navbar.php"; ?>

    <div class="chat-container">
        <!-- Header -->
        <div class="chat-header">
            <button class="back-btn" onclick="window.location.href='/forum'">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#333" stroke-width="2">
                    <path d="M19 12H5M12 19l-7-7 7-7" />
                </svg>
            </button>
            <div class="header-icon">
                <?php if ($komunitas['icon_type'] == 'globe'): ?>
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10" />
                        <path
                            d="M2 12h20M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z" />
                    </svg>
                <?php else: ?>
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" stroke-linecap="round"
                            stroke-linejoin="round" />
                        <circle cx="9" cy="7" r="4" />
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                <?php endif; ?>
            </div>
            <div class="header-info">
                <h2><?php echo htmlspecialchars($komunitas['nama_komunitas']); ?></h2>
                <p><?php echo htmlspecialchars($komunitas['jumlah_anggota']); ?> anggota</p>
            </div>
        </div>

        <!-- Reply Preview (Hidden by default) -->
        <div class="reply-preview" id="replyPreview" style="display: none;">
            <div class="reply-content">
                <div class="reply-to-text">
                    <strong>Membalas <span id="replyUsername"></span></strong>
                    <p id="replyText"></p>
                </div>
                <button class="cancel-reply" onclick="cancelReply()">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
        </div>

        <!-- Messages -->
        <div class="chat-messages" id="chatMessages">
            <?php foreach ($messages as $msg): ?>
                <?php
                $is_sent = ($msg['user_id'] == $current_user['user_id']);
                $time = date('H:i', strtotime($msg['created_at']));
                $initials = strtoupper(substr($msg['username'], 0, 2));
                ?>
                <div class="message <?php echo $is_sent ? 'sent' : ''; ?>"
                    data-message-id="<?php echo $msg['message_id']; ?>"
                    data-username="<?php echo htmlspecialchars($msg['username']); ?>"
                    data-text="<?php echo htmlspecialchars($msg['message_text']); ?>"
                    oncontextmenu="showContextMenu(event, <?php echo $msg['message_id']; ?>, <?php echo $is_sent ? 'true' : 'false'; ?>)">
                    <div class="message-avatar"><?php echo $initials; ?></div>
                    <div class="message-content">
                        <?php if (!$is_sent): ?>
                            <div class="message-author"><?php echo htmlspecialchars($msg['username']); ?></div>
                        <?php endif; ?>

                        <?php if ($msg['reply_to_message_id']): ?>
                            <div class="message-reply-info">
                                <i class="bi bi-reply"></i>
                                <strong><?php echo htmlspecialchars($msg['reply_username']); ?></strong>
                                <p><?php echo htmlspecialchars(substr($msg['reply_message_text'], 0, 50)) . (strlen($msg['reply_message_text']) > 50 ? '...' : ''); ?>
                                </p>
                            </div>
                        <?php endif; ?>

                        <div class="message-bubble">
                            <?php echo htmlspecialchars($msg['message_text']); ?>
                        </div>
                        <div class="message-time"><?php echo $time; ?></div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Context Menu -->
        <div class="context-menu" id="contextMenu" style="display: none;">
            <div class="context-menu-item" onclick="replyToMessage()">
                <i class="bi bi-reply"></i> Balas
            </div>
            <div class="context-menu-item delete" id="deleteOption" onclick="deleteMessage()" style="display: none;">
                <i class="bi bi-trash"></i> Hapus
            </div>
        </div>

        <!-- Input -->
        <div class="chat-input-container">
            <div class="chat-input-wrapper">
                <input type="text" class="chat-input" placeholder="Ketik pesan..." id="messageInput"
                    onkeypress="handleKeyPress(event)">
                <button class="send-btn" onclick="sendMessage()">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M22 2L11 13M22 2l-7 20-4-9-9-4 20-7z" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <style>
        .chat-container {
            max-width: 1400px;
            margin: 0 auto;
            background: white;
            min-height: calc(100vh - 80px);
            display: flex;
            flex-direction: column;
        }

        .chat-header {
            background: white;
            padding: 20px 30px;
            border-bottom: 1px solid #e0e0e0;
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .back-btn {
            background: none;
            border: none;
            cursor: pointer;
            padding: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            transition: background-color 0.3s;
        }

        .back-btn:hover {
            background-color: #f0f0f0;
        }

        .header-icon {
            width: 50px;
            height: 50px;
            background-color: #0a1f44;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
        }

        .header-info h2 {
            font-size: 1.3rem;
            color: #0a1f44;
            font-weight: 600;
            margin-bottom: 2px;
        }

        .header-info p {
            font-size: 0.9rem;
            color: #6c757d;
        }

        /* Reply Preview */
        .reply-preview {
            background: #f0f2f5;
            padding: 10px 30px;
            border-bottom: 1px solid #e0e0e0;
        }

        .reply-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .reply-to-text {
            flex: 1;
        }

        .reply-to-text strong {
            color: #0a1f44;
            font-size: 0.9rem;
        }

        .reply-to-text p {
            margin: 5px 0 0 0;
            color: #666;
            font-size: 0.85rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 500px;
        }

        .cancel-reply {
            background: none;
            border: none;
            cursor: pointer;
            padding: 5px;
            color: #666;
            font-size: 1.2rem;
        }

        .cancel-reply:hover {
            color: #333;
        }

        /* Context Menu */
        .context-menu {
            position: fixed;
            background: white;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            min-width: 150px;
        }

        .context-menu-item {
            padding: 12px 20px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: background-color 0.2s;
        }

        .context-menu-item:hover {
            background-color: #f0f2f5;
        }

        .context-menu-item.delete {
            color: #dc3545;
        }

        .context-menu-item.delete:hover {
            background-color: #fff5f5;
        }

        .context-menu-item i {
            font-size: 1rem;
        }

        /* Message Reply Info */
        .message-reply-info {
            background: rgba(10, 31, 68, 0.1);
            padding: 8px 12px;
            border-radius: 8px;
            margin-bottom: 8px;
            border-left: 3px solid #0a1f44;
            font-size: 0.85rem;
        }

        .message.sent .message-reply-info {
            background: rgba(255, 255, 255, 0.3);
            border-left-color: white;
        }

        .message-reply-info i {
            margin-right: 5px;
        }

        .message-reply-info strong {
            display: block;
            margin-bottom: 2px;
        }

        .message-reply-info p {
            margin: 0;
            color: #666;
        }

        .message.sent .message-reply-info p {
            color: rgba(255, 255, 255, 0.8);
        }

        .chat-messages {
            flex: 1;
            padding: 30px;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 25px;
        }

        .message {
            display: flex;
            gap: 15px;
            align-items: flex-start;
            cursor: context-menu;
        }

        .message.sent {
            flex-direction: row-reverse;
        }

        .message-avatar {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background-color: #0a1f44;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 0.95rem;
            flex-shrink: 0;
        }

        .message-content {
            max-width: 60%;
            display: flex;
            flex-direction: column;
        }

        .message.sent .message-content {
            align-items: flex-end;
        }

        .message-author {
            font-weight: 600;
            color: #0a1f44;
            font-size: 0.95rem;
            margin-bottom: 5px;
        }

        .message.sent .message-author {
            display: none;
        }

        .message-bubble {
            background-color: #f0f2f5;
            padding: 12px 18px;
            border-radius: 18px;
            font-size: 0.95rem;
            line-height: 1.5;
            color: #333;
            word-wrap: break-word;
        }

        .message.sent .message-bubble {
            background-color: #0a1f44;
            color: white;
        }

        .message-time {
            font-size: 0.8rem;
            color: #6c757d;
            margin-top: 5px;
        }

        .chat-input-container {
            background: white;
            padding: 20px 30px;
            border-top: 1px solid #e0e0e0;
        }

        .chat-input-wrapper {
            display: flex;
            gap: 15px;
            align-items: center;
        }

        .chat-input {
            flex: 1;
            padding: 14px 20px;
            border: 1px solid #e0e0e0;
            border-radius: 25px;
            font-size: 0.95rem;
            font-family: inherit;
            outline: none;
            transition: border-color 0.3s;
        }

        .chat-input:focus {
            border-color: #0a1f44;
        }

        .send-btn {
            width: 50px;
            height: 50px;
            background-color: #0a1f44;
            color: white;
            border: none;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: background-color 0.3s;
            flex-shrink: 0;
        }

        .send-btn:hover {
            background-color: #162e5a;
        }

        @media (max-width: 768px) {
            .message-content {
                max-width: 75%;
            }

            .chat-header {
                padding: 15px 20px;
            }

            .chat-messages {
                padding: 20px;
            }

            .chat-input-container {
                padding: 15px 20px;
            }
        }
    </style>

    <script>
        const currentUsername = '<?php echo addslashes($current_user['username']); ?>';
        const currentUserId = '<?php echo addslashes($current_user['user_id']); ?>';
        const komunitas_id = <?php echo $komunitas['komunitas_id']; ?>;

        let selectedMessageId = null;
        let replyToMessageId = null;

        // Show context menu on right-click
        function showContextMenu(event, messageId, isSent) {
            event.preventDefault();

            const contextMenu = document.getElementById('contextMenu');
            const deleteOption = document.getElementById('deleteOption');

            selectedMessageId = messageId;

            // Show delete option only for own messages
            if (isSent) {
                deleteOption.style.display = 'flex';
            } else {
                deleteOption.style.display = 'none';
            }

            contextMenu.style.display = 'block';
            contextMenu.style.left = event.pageX + 'px';
            contextMenu.style.top = event.pageY + 'px';
        }

        // Hide context menu when clicking elsewhere
        document.addEventListener('click', function() {
            document.getElementById('contextMenu').style.display = 'none';
        });

        // Reply to message
        function replyToMessage() {
            const messageElement = document.querySelector(`.message[data-message-id="${selectedMessageId}"]`);
            const username = messageElement.getAttribute('data-username');
            const text = messageElement.getAttribute('data-text');

            replyToMessageId = selectedMessageId;

            document.getElementById('replyUsername').textContent = username;
            document.getElementById('replyText').textContent = text;
            document.getElementById('replyPreview').style.display = 'block';
            document.getElementById('messageInput').focus();

            document.getElementById('contextMenu').style.display = 'none';
        }

        // Cancel reply
        function cancelReply() {
            replyToMessageId = null;
            document.getElementById('replyPreview').style.display = 'none';
        }

        // Delete message
        function deleteMessage() {
            if (!confirm('Hapus pesan ini?')) {
                return;
            }

            fetch('/forum/delete-message', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'message_id=' + selectedMessageId
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Remove message from DOM
                        const messageElement = document.querySelector(
                            `.message[data-message-id="${selectedMessageId}"]`);
                        if (messageElement) {
                            messageElement.remove();
                        }
                    } else {
                        alert('Gagal menghapus pesan');
                    }
                })
                .catch(error => console.error('Error:', error));

            document.getElementById('contextMenu').style.display = 'none';
        }

        function sendMessage() {
            const input = document.getElementById('messageInput');
            const message = input.value.trim();

            if (message) {
                let body = 'komunitas_id=' + komunitas_id + '&message=' + encodeURIComponent(message);

                if (replyToMessageId) {
                    body += '&reply_to_message_id=' + replyToMessageId;
                }

                fetch('/forum/send-message', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: body
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            const messagesContainer = document.getElementById('chatMessages');
                            const initials = currentUsername.substring(0, 2).toUpperCase();

                            let replyHTML = '';
                            if (data.reply_to) {
                                replyHTML = `
                                <div class="message-reply-info">
                                    <i class="bi bi-reply"></i>
                                    <strong>${escapeHtml(data.reply_to.username)}</strong>
                                    <p>${escapeHtml(data.reply_to.text.substring(0, 50))}${data.reply_to.text.length > 50 ? '...' : ''}</p>
                                </div>
                            `;
                            }

                            const messageHTML = `
                            <div class="message sent" data-message-id="${data.message_id}" data-username="${escapeHtml(currentUsername)}" data-text="${escapeHtml(message)}" oncontextmenu="showContextMenu(event, ${data.message_id}, true)">
                                <div class="message-avatar">${initials}</div>
                                <div class="message-content">
                                    ${replyHTML}
                                    <div class="message-bubble">${escapeHtml(message)}</div>
                                    <div class="message-time">${data.time}</div>
                                </div>
                            </div>
                        `;

                            messagesContainer.insertAdjacentHTML('beforeend', messageHTML);
                            input.value = '';
                            cancelReply();

                            // Scroll to bottom
                            messagesContainer.scrollTop = messagesContainer.scrollHeight;
                        }
                    })
                    .catch(error => console.error('Error:', error));
            }
        }

        function handleKeyPress(event) {
            if (event.key === 'Enter') {
                sendMessage();
            }
        }

        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        // Auto scroll to bottom on load
        document.addEventListener('DOMContentLoaded', function() {
            const messagesContainer = document.getElementById('chatMessages');
            if (messagesContainer) {
                messagesContainer.scrollTop = messagesContainer.scrollHeight;
            }
        });

        // Auto refresh messages setiap 10 detik
        setInterval(function() {
            location.reload();
        }, 10000);
    </script>
</body>

</html>