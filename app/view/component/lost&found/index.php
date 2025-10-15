<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kompetisi - CampusHub</title>
    <link rel="stylesheet" href="/css/index.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="/css/lostnfound.css">
</head>

<body>
    <?php
    require_once __DIR__ . "/../../layouts/navbar.php";
    require_once __DIR__ . "/main.php";
    ?>


    <script>
        // Tab switching functionality
        const tabs = document.querySelectorAll('.tab');
        const tabContents = document.querySelectorAll('.tab-content');

        tabs.forEach(tab => {
            tab.addEventListener('click', function() {
                // Remove active class from all tabs
                tabs.forEach(t => t.classList.remove('active'));

                // Add active class to clicked tab
                this.classList.add('active');

                // Hide all tab contents
                tabContents.forEach(content => content.classList.remove('active'));

                // Show the selected tab content
                const targetTab = this.getAttribute('data-tab');
                document.getElementById(targetTab).classList.add('active');

                // Update search placeholder
                const searchInput = document.getElementById('searchInput');
                if (targetTab === 'daftar-lomba') {
                    searchInput.placeholder = 'Cari kompetisi atau tim...';
                } else {
                    searchInput.placeholder = 'Cari tim...';
                }
            });
        });

        // Button functionality for Daftar Lomba
        document.querySelectorAll('.btn-posting').forEach(btn => {
            btn.addEventListener('click', function() {
                const activeTab = document.querySelector('.tab.active').getAttribute('data-tab');
                if (activeTab === 'daftar-lomba') {
                    alert('Fitur Posting Lomba');
                } else {
                    alert('Fitur Buat Tim');
                }
            });
        });

        document.querySelectorAll('.btn-detail').forEach(btn => {
            btn.addEventListener('click', function() {
                alert('Menampilkan detail kompetisi');
            });
        });

        document.querySelectorAll('.btn-join').forEach(btn => {
            btn.addEventListener('click', function() {
                alert('Ajukan bergabung dengan tim');
            });
        });
    </script>
</body>

</html>