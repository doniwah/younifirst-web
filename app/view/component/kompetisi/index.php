<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kompetisi dan Tim - YouNiFirst</title>
    <link rel="stylesheet" href="/css/sidebar.css">
    <link rel="stylesheet" href="/css/kompetisi.css">
    <link rel="stylesheet" href="/css/team-modern.css">
    <link rel="stylesheet" href="/css/competition-cards.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>
    <?php require_once __DIR__ . "/../../layouts/sidebar.php"; ?>
    
    <div class="main-content">
        <?php require_once __DIR__ . "/main.php"; ?>
    </div>

    <style>
        /* Alert styles */
        .alert {
            padding: 15px;
            margin: 20px 0;
            border-radius: 8px;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        /* Prevent body scroll when modal is open */
        body.modal-open {
            overflow: hidden !important;
        }

        /* Adjust container for sidebar layout */
        .main-content .container {
            max-width: 100%;
            padding: 20px;
        }
    </style>

    <script>
        // Tab switching functionality
        document.addEventListener('DOMContentLoaded', function() {
            // Button functionality - Detail buttons
            document.querySelectorAll('.btn-detail').forEach(btn => {
                btn.addEventListener('click', function() {
                    const competitionId = this.getAttribute('data-id');
                    window.location.href = '/kompetisi/' + competitionId;
                });
            });

            // Form validation before submit
            const lombaForm = document.getElementById('lombaForm');
            if (lombaForm) {
                lombaForm.addEventListener('submit', function(e) {
                    const namaLomba = this.querySelector('[name="nama_lomba"]').value.trim();
                    const deadline = this.querySelector('[name="deadline"]').value;

                    if (!namaLomba || !deadline) {
                        e.preventDefault();
                        alert('Nama lomba dan deadline harus diisi!');
                        return false;
                    }
                });
            }

            // Form validation for Tim form
            const timForm = document.getElementById('timForm');
            if (timForm) {
                timForm.addEventListener('submit', function(e) {
                    const namaTeam = this.querySelector('[name="nama_team"]').value.trim();

                    if (!namaTeam) {
                        e.preventDefault();
                        alert('Nama tim harus diisi!');
                        return false;
                    }
                });
            }

            // Form validation for Join form
            const joinForm = document.getElementById('joinForm');
            if (joinForm) {
                joinForm.addEventListener('submit', function(e) {
                    const role = this.querySelector('[name="role_diminta"]').value;
                    const alasan = this.querySelector('[name="alasan_bergabung"]').value.trim();
                    const keahlian = this.querySelector('[name="keahlian_pengalaman"]').value.trim();
                    const kontak = this.querySelector('[name="kontak"]').value.trim();

                    if (!role || !alasan || !keahlian || !kontak) {
                        e.preventDefault();
                        alert('Semua field wajib harus diisi!');
                        return false;
                    }
                });
            }

            // Auto-hide alerts after 5 seconds
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    alert.style.transition = 'opacity 0.5s';
                    alert.style.opacity = '0';
                    setTimeout(() => alert.remove(), 500);
                }, 5000);
            });
        });
    </script>
</body>

</html>