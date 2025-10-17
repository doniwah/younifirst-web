<?php
session_start();

?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Session & Posting Lomba</title>
    <style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        padding: 20px;
        background: #f5f5f5;
    }

    .container {
        max-width: 800px;
        margin: 0 auto;
    }

    .card {
        background: white;
        padding: 20px;
        margin: 15px 0;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .success {
        border-left: 4px solid #28a745;
        background: #d4edda;
    }

    .error {
        border-left: 4px solid #dc3545;
        background: #f8d7da;
    }

    .warning {
        border-left: 4px solid #ffc107;
        background: #fff3cd;
    }

    .info {
        border-left: 4px solid #17a2b8;
        background: #d1ecf1;
    }

    h1 {
        color: #333;
        margin-bottom: 20px;
    }

    h2 {
        color: #555;
        margin-bottom: 15px;
        font-size: 18px;
    }

    pre {
        background: #f8f9fa;
        padding: 15px;
        border-radius: 4px;
        overflow-x: auto;
        font-size: 13px;
    }

    .btn {
        display: inline-block;
        padding: 10px 20px;
        margin: 5px;
        text-decoration: none;
        border-radius: 5px;
        font-weight: 500;
        transition: all 0.3s;
    }

    .btn-primary {
        background: #007bff;
        color: white;
    }

    .btn-success {
        background: #28a745;
        color: white;
    }

    .btn-danger {
        background: #dc3545;
        color: white;
    }

    .btn:hover {
        opacity: 0.8;
        transform: translateY(-1px);
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
    }

    table td {
        padding: 8px;
        border-bottom: 1px solid #eee;
    }

    table td:first-child {
        font-weight: 600;
        width: 150px;
        color: #555;
    }

    .status-indicator {
        display: inline-block;
        width: 10px;
        height: 10px;
        border-radius: 50%;
        margin-right: 8px;
    }

    .status-ok {
        background: #28a745;
    }

    .status-error {
        background: #dc3545;
    }
    </style>
</head>

<body>
    <div class="container">
        <h1>🔍 Session & Database Test</h1>

        <!-- Session Status -->
        <div
            class="card <?= !empty($_SESSION) && (isset($_SESSION['user']) || isset($_SESSION['user_id'])) ? 'success' : 'error' ?>">
            <h2>
                <span
                    class="status-indicator <?= !empty($_SESSION) && (isset($_SESSION['user']) || isset($_SESSION['user_id'])) ? 'status-ok' : 'status-error' ?>"></span>
                Status Login
            </h2>
            <?php
            $userId = $_SESSION['user']['user_id'] ?? $_SESSION['user_id'] ?? null;
            $userEmail = $_SESSION['user']['email'] ?? $_SESSION['email'] ?? null;
            ?>

            <?php if ($userId): ?>
            <p style="color: #155724; font-weight: 600;">✅ User sudah login</p>
            <table>
                <tr>
                    <td>User ID</td>
                    <td><?= $userId ?></td>
                </tr>
                <tr>
                    <td>Email</td>
                    <td><?= htmlspecialchars($userEmail ?? 'N/A') ?></td>
                </tr>
                <tr>
                    <td>Session ID</td>
                    <td><?= session_id() ?></td>
                </tr>
            </table>
            <?php else: ?>
            <p style="color: #721c24; font-weight: 600;">❌ User belum login</p>
            <p style="margin-top: 10px;">Silakan <a href="/login" class="btn btn-primary">Login disini</a></p>
            <?php endif; ?>
        </div>

        <!-- Session Data Detail -->
        <div class="card info">
            <h2>📦 Session Data</h2>
            <?php if (!empty($_SESSION)): ?>
            <pre><?php print_r($_SESSION); ?></pre>
            <?php else: ?>
            <p style="color: #666;">Session kosong</p>
            <?php endif; ?>
        </div>

        <!-- Database Connection Test -->
        <div class="card">
            <h2>🔗 Test Database Connection</h2>
            <?php
            try {
                require_once __DIR__ . '/../vendor/autoload.php';
                require_once __DIR__ . '/../app/Models/Database.php';

                $db = App\Models\Database::getInstance();
                echo '<p style="color: #28a745; font-weight: 600;">✅ Database connection berhasil</p>';

                // Test query tabel lomba
                $stmt = $db->query("SELECT COUNT(*) as total FROM lomba");
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                echo '<p>Total lomba di database: <strong>' . $result['total'] . '</strong></p>';

                // Ambil 3 lomba terakhir
                $stmt = $db->query("SELECT lomba_id, nama_lomba, status, user_id, tanggal_lomba FROM lomba ORDER BY lomba_id DESC LIMIT 3");
                $competitions = $stmt->fetchAll(PDO::FETCH_ASSOC);

                if ($competitions) {
                    echo '<h3 style="margin-top: 15px;">3 Lomba Terakhir:</h3>';
                    echo '<table>';
                    echo '<tr style="background: #f8f9fa; font-weight: 600;">
                            <td>ID</td><td>Nama</td><td>Status</td><td>User ID</td><td>Tanggal</td>
                          </tr>';
                    foreach ($competitions as $comp) {
                        $statusColor = $comp['status'] === 'confirm' ? '#28a745' : ($comp['status'] === 'waiting' ? '#ffc107' : '#dc3545');
                        echo '<tr>';
                        echo '<td>' . $comp['lomba_id'] . '</td>';
                        echo '<td>' . htmlspecialchars($comp['nama_lomba']) . '</td>';
                        echo '<td><span style="color: ' . $statusColor . '; font-weight: 600;">' . $comp['status'] . '</span></td>';
                        echo '<td>' . $comp['user_id'] . '</td>';
                        echo '<td>' . $comp['tanggal_lomba'] . '</td>';
                        echo '</tr>';
                    }
                    echo '</table>';
                }
            } catch (Exception $e) {
                echo '<p style="color: #dc3545; font-weight: 600;">❌ Database error: ' . $e->getMessage() . '</p>';
            }
            ?>
        </div>

        <!-- Quick Actions -->
        <div class="card">
            <h2>⚡ Quick Actions</h2>
            <div style="display: flex; flex-wrap: wrap; gap: 10px;">
                <?php if ($userId): ?>
                <a href="/kompetisi" class="btn btn-success">📝 Go to Kompetisi</a>
                <a href="/dashboard" class="btn btn-primary">🏠 Dashboard</a>
                <a href="/logout" class="btn btn-danger">🚪 Logout</a>
                <?php else: ?>
                <a href="/login" class="btn btn-primary">🔑 Login</a>
                <?php endif; ?>
                <a href="javascript:location.reload()" class="btn btn-primary">🔄 Refresh</a>
            </div>
        </div>

        <!-- Test Manual Insert -->
        <?php if ($userId): ?>
        <div class="card warning">
            <h2>🧪 Test Manual Insert (HANYA UNTUK TESTING)</h2>
            <p style="margin-bottom: 15px;">Klik tombol ini untuk test insert data lomba secara manual:</p>

            <?php
                if (isset($_GET['test_insert']) && $_GET['test_insert'] === '1') {
                    try {
                        require_once __DIR__ . '/../vendor/autoload.php';
                        require_once __DIR__ . '/../app/Models/Database.php';
                        require_once __DIR__ . '/../app/Models/Competition.php';

                        $competition = new App\Models\Competition();
                        $competition->nama_lomba = "Test " . date('ymd-His'); // Format pendek: Test 251017-065509
                        $competition->deskripsi = "Test auto insert dari test_session_simple.php";
                        $competition->kategori = "Technology";
                        $competition->tanggal_lomba = date('Y-m-d', strtotime('+30 days'));
                        $competition->lokasi = "Jakarta";
                        $competition->hadiah = "1000000";
                        $competition->user_id = $userId;
                        $competition->poster_lomba = "";

                        if ($competition->create()) {
                            echo '<div style="background: #d4edda; color: #155724; padding: 15px; border-radius: 5px; margin: 10px 0;">';
                            echo '✅ <strong>BERHASIL!</strong> Lomba berhasil diinsert ke database!';
                            echo '<br>Nama lomba: ' . htmlspecialchars($competition->nama_lomba);
                            echo '<br><a href="?" style="color: #155724; font-weight: 600;">Refresh halaman untuk melihat data terbaru</a>';
                            echo '</div>';
                        } else {
                            echo '<div style="background: #f8d7da; color: #721c24; padding: 15px; border-radius: 5px; margin: 10px 0;">';
                            echo '❌ <strong>GAGAL!</strong> Tidak bisa insert lomba. Cek error log PHP.';
                            echo '</div>';
                        }
                    } catch (Exception $e) {
                        echo '<div style="background: #f8d7da; color: #721c24; padding: 15px; border-radius: 5px; margin: 10px 0;">';
                        echo '❌ <strong>ERROR!</strong> ' . htmlspecialchars($e->getMessage());
                        echo '</div>';
                    }
                }
                ?>

            <a href="?test_insert=1" class="btn btn-danger">🚀 Test Insert Sekarang</a>
            <p style="margin-top: 10px; font-size: 12px; color: #666;">
                Catatan: Ini akan insert data dummy ke database. Gunakan hanya untuk testing.
            </p>
        </div>
        <?php endif; ?>

        <!-- PHP Error Log Location -->
        <div class="card info">
            <h2>📝 Informasi Error Log</h2>
            <p><strong>Error Log Location:</strong> <?= ini_get('error_log') ?: 'Default location (cek php.ini)' ?></p>
            <p><strong>Display Errors:</strong> <?= ini_get('display_errors') ? 'ON' : 'OFF' ?></p>
            <p><strong>Log Errors:</strong> <?= ini_get('log_errors') ? 'ON' : 'OFF' ?></p>
        </div>
    </div>
</body>

</html>