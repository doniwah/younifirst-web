<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kompetisi - YouNiFirst</title>
    <link rel="stylesheet" href="/css/index.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="/css/kompetisi.css">
</head>

<body>
    <?php
    require_once __DIR__ . "/../../layouts/navbar.php";
    require_once __DIR__ . "/main.php";
    ?>

    <style>
    /* Modal Animation */
    @keyframes zoomIn {
        from {
            opacity: 0;
            transform: scale(0.7);
        }

        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    @keyframes zoomOut {
        from {
            opacity: 1;
            transform: scale(1);
        }

        to {
            opacity: 0;
            transform: scale(0.7);
        }
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
    }

    .modal.show {
        display: block !important;
        animation: fadeIn 0.3s ease-in-out;
    }

    .modal.show .modal-content {
        animation: zoomIn 0.3s ease-in-out;
    }

    .modal.hide .modal-content {
        animation: zoomOut 0.3s ease-in-out;
    }

    /* Prevent body scroll when modal is open */
    body.modal-open {
        overflow: hidden !important;
        position: fixed;
        width: 100%;
        height: 100%;
    }
    </style>

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

    // Button functionality - Detail buttons
    document.querySelectorAll('.btn-detail').forEach(btn => {
        btn.addEventListener('click', function() {
            alert('Menampilkan detail kompetisi');
        });
    });

    // Button functionality - Join buttons
    document.querySelectorAll('.btn-join').forEach(btn => {
        btn.addEventListener('click', function() {
            alert('Ajukan bergabung dengan tim');
        });
    });

    // Modal functionality with animation
    const modal = document.getElementById("lombaModal");
    const postingButtons = document.querySelectorAll('.btn-posting');
    const closeBtn = document.querySelector(".close");

    // Function to open modal with animation
    function openModal() {
        modal.classList.remove('hide');
        modal.classList.add('show');
        modal.style.display = "block";

        // Disable body scroll and save current scroll position
        const scrollY = window.scrollY;
        document.body.style.position = 'fixed';
        document.body.style.top = `-${scrollY}px`;
        document.body.style.width = '100%';
        document.body.classList.add('modal-open');
    }

    // Function to close modal with animation
    function closeModal() {
        modal.classList.remove('show');
        modal.classList.add('hide');

        // Wait for animation to finish before hiding
        setTimeout(() => {
            modal.style.display = "none";
            modal.classList.remove('hide');

            // Re-enable body scroll and restore scroll position
            const scrollY = document.body.style.top;
            document.body.style.position = '';
            document.body.style.top = '';
            document.body.style.width = '';
            document.body.classList.remove('modal-open');
            window.scrollTo(0, parseInt(scrollY || '0') * -1);
        }, 300);
    }

    // Open modal when any "Posting Lomba" button is clicked
    postingButtons.forEach(btn => {
        btn.addEventListener('click', openModal);
    });

    // Close modal when X is clicked
    if (closeBtn) {
        closeBtn.addEventListener('click', closeModal);
    }

    // Close modal when clicking outside of it
    window.addEventListener('click', function(event) {
        if (event.target === modal) {
            closeModal();
        }
    });

    // Handle form submission (optional)
    const submitBtn = document.querySelector('.submit-btn');
    if (submitBtn) {
        submitBtn.addEventListener('click', function(e) {
            e.preventDefault();
            alert('Lomba berhasil diposting!');
            closeModal();
            // Reset form here if needed
        });
    }
    </script>
</body>

</html>