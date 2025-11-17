<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forum - YouNiFirst</title>
    <link rel="stylesheet" href="/css/index.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="/css/forum.css">
    <style>
        /* Toast Notification */
        .toast-notification {
            position: fixed;
            bottom: -100px;
            right: 30px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 18px 24px;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            display: flex;
            align-items: center;
            gap: 15px;
            min-width: 320px;
            max-width: 400px;
            z-index: 9999;
            animation: slideUp 0.4s ease-out forwards;
        }

        @keyframes slideUp {
            to {
                bottom: 30px;
            }
        }

        .toast-notification.hide {
            animation: slideDown 0.4s ease-out forwards;
        }

        @keyframes slideDown {
            to {
                bottom: -100px;
                opacity: 0;
            }
        }

        .toast-icon {
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: 1.3rem;
        }

        .toast-content {
            flex: 1;
        }

        .toast-content p {
            margin: 0;
            font-size: 1rem;
            font-weight: 500;
            line-height: 1.5;
        }

        .toast-close {
            background: none;
            border: none;
            color: white;
            cursor: pointer;
            padding: 5px;
            opacity: 0.8;
            transition: opacity 0.2s;
            font-size: 1.2rem;
        }

        .toast-close:hover {
            opacity: 1;
        }

        /* Progress bar */
        .toast-progress {
            position: absolute;
            bottom: 0;
            left: 0;
            height: 4px;
            background: rgba(255, 255, 255, 0.5);
            width: 100%;
            border-radius: 0 0 12px 12px;
            animation: progressBar 3s linear forwards;
        }

        @keyframes progressBar {
            from {
                width: 100%;
            }

            to {
                width: 0%;
            }
        }
    </style>
</head>

<body>
    <?php require_once __DIR__ . "/../../layouts/navbar.php"; ?>

    <?php require __DIR__ . "/main.php"; ?>

    <div id="toastContainer"></div>

    <script>
        // Show toast notification
        function showToast(message, icon = '⚠️', duration = 3000) {
            const container = document.getElementById('toastContainer');

            const toast = document.createElement('div');
            toast.className = 'toast-notification';
            toast.innerHTML = `
                <div class="toast-icon">${icon}</div>
                <div class="toast-content">
                    <p>${message}</p>
                </div>
                <button class="toast-close" onclick="closeToast(this)">
                    <i class="bi bi-x-lg"></i>
                </button>
                <div class="toast-progress"></div>
            `;

            container.appendChild(toast);

            // Auto hide after duration
            setTimeout(() => {
                closeToast(toast.querySelector('.toast-close'));
            }, duration);
        }

        function closeToast(button) {
            const toast = button.closest('.toast-notification');
            toast.classList.add('hide');
            setTimeout(() => {
                toast.remove();
            }, 400);
        }

        // Check for error parameter in URL
        window.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            const error = urlParams.get('error');

            if (error === 'access_denied') {
                showToast('Pilih komunitas sesuai jurusan anda', '🔒', 3500);

                // Remove error parameter from URL without reload
                const url = new URL(window.location);
                url.searchParams.delete('error');
                window.history.replaceState({}, '', url);
            }
        });
    </script>
</body>

</html>