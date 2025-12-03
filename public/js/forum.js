document.addEventListener('DOMContentLoaded', function() {
    console.log('Forum JS Loaded');
    
    const data = window.FORUM_DATA || {};
    const komunitas_id = data.komunitas_id;
    const group_id = data.group_id;
    const currentUsername = data.username;

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
            if (modal) modal.style.display = 'flex';
        });
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
        const message = messageInput.value.trim();

        if (message && group_id > 0) {
            const body = `komunitas_id=${komunitas_id}&group_id=${group_id}&message=${encodeURIComponent(message)}`;

            fetch('/forum/send-message', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: body
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const html = `
                        <div class="message sent">
                            <img src="https://api.dicebear.com/7.x/avataaars/svg?seed=${currentUsername}" class="msg-avatar">
                            <div class="msg-content">
                                <div class="msg-bubble">
                                    ${escapeHtml(message)}
                                    <div class="msg-time">${data.time}</div>
                                </div>
                            </div>
                        </div>
                    `;
                    chatMessages.insertAdjacentHTML('beforeend', html);
                    messageInput.value = '';
                    chatMessages.scrollTop = chatMessages.scrollHeight;
                } else {
                    alert('Gagal mengirim pesan: ' + (data.message || 'Unknown error'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan koneksi');
            });
        } else {
            if (!message) return;
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
