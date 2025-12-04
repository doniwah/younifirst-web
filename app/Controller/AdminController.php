<?php

namespace App\Controller;

use App\App\View;
use App\Repository\AdminRepository;
use App\Service\SessionService;

class AdminController
{
    private $adminRepository;
    private $sessionService;

    public function __construct()
    {
        $this->adminRepository = new AdminRepository();
        $this->sessionService = new SessionService();
    }

    public function users()
    {
        $role = $this->sessionService->getRole();
        if ($role !== 'admin') {
            header('Location: /dashboard');
            exit;
        }

        $search = $_GET['search'] ?? null;
        $filterRole = $_GET['role'] ?? null;
        $filterStatus = $_GET['status'] ?? null;

        $stats = $this->adminRepository->getUserStats();
        $users = $this->adminRepository->getAllUsers($search, $filterRole, $filterStatus);

        View::render('admin/users', [
            'title' => 'Daftar Pengguna - Admin',
            'stats' => $stats,
            'users' => $users
        ]);
    }

    public function addUser()
    {
        header('Content-Type: application/json');
        
        $role = $this->sessionService->getRole();
        if ($role !== 'admin') {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            exit;
        }

        try {
            $username = $_POST['username'] ?? '';
            $email = $_POST['email'] ?? '';
            $nama_lengkap = $_POST['nama_lengkap'] ?? '';
            $password = $_POST['password'] ?? '';
            $jurusan = $_POST['jurusan'] ?? '';
            $angkatan = $_POST['angkatan'] ?? '';
            $userRole = $_POST['role'] ?? 'mahasiswa';

            $result = $this->adminRepository->createUser([
                'username' => $username,
                'email' => $email,
                'nama_lengkap' => $nama_lengkap,
                'password' => $password,
                'jurusan' => $jurusan,
                'angkatan' => $angkatan,
                'role' => $userRole
            ]);

            echo json_encode(['success' => true, 'message' => 'User created successfully']);
        } catch (\Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function getUser($userId)
    {
        header('Content-Type: application/json');
        
        $role = $this->sessionService->getRole();
        if ($role !== 'admin') {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            exit;
        }

        try {
            $user = $this->adminRepository->getUserById($userId);
            if ($user) {
                echo json_encode(['success' => true, 'data' => $user]);
            } else {
                echo json_encode(['success' => false, 'message' => 'User not found']);
            }
        } catch (\Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function updateUser()
    {
        header('Content-Type: application/json');
        
        $role = $this->sessionService->getRole();
        if ($role !== 'admin') {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            exit;
        }

        try {
            $userId = $_POST['user_id'] ?? '';
            $data = [
                'username' => $_POST['username'] ?? '',
                'email' => $_POST['email'] ?? '',
                'nama_lengkap' => $_POST['nama_lengkap'] ?? '',
                'jurusan' => $_POST['jurusan'] ?? '',
                'angkatan' => $_POST['angkatan'] ?? '',
                'role' => $_POST['role'] ?? 'mahasiswa'
            ];

            $result = $this->adminRepository->updateUser($userId, $data);
            echo json_encode(['success' => true, 'message' => 'User updated successfully']);
        } catch (\Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function changeUserStatus()
    {
        header('Content-Type: application/json');
        
        $role = $this->sessionService->getRole();
        $adminId = $this->sessionService->current()->user_id ?? 'admin';
        
        if ($role !== 'admin') {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            exit;
        }

        try {
            $userId = $_POST['user_id'] ?? '';
            $status = $_POST['status'] ?? 'active';
            $reason = $_POST['reason'] ?? null;

            $result = $this->adminRepository->updateUserStatus($userId, $status, $adminId, $reason);
            echo json_encode(['success' => true, 'message' => 'Status updated successfully']);
        } catch (\Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function deleteUser()
    {
        header('Content-Type: application/json');
        
        $role = $this->sessionService->getRole();
        $adminId = $this->sessionService->current()->user_id ?? 'admin';
        
        if ($role !== 'admin') {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            exit;
        }

        try {
            $userId = $_POST['user_id'] ?? '';
            $reason = $_POST['reason'] ?? null;

            $result = $this->adminRepository->deleteUser($userId, $adminId, $reason);
            echo json_encode(['success' => true, 'message' => 'User deleted successfully']);
        } catch (\Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function exportUsers()
    {
        $role = $this->sessionService->getRole();
        if ($role !== 'admin') {
            header('Location: /dashboard');
            exit;
        }

        $users = $this->adminRepository->getAllUsers();

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="users_export_' . date('Y-m-d') . '.csv"');

        $output = fopen('php://output', 'w');

        // Header CSV
        fputcsv($output, ['User ID', 'Username', 'Email', 'Nama Lengkap', 'Jurusan', 'Angkatan', 'Role', 'Status', 'Bergabung', 'Terakhir Aktif']);

        // Data
        foreach ($users as $user) {
            fputcsv($output, [
                $user['user_id'],
                $user['username'],
                $user['email'],
                $user['nama_lengkap'],
                $user['jurusan'] ?? '',
                $user['angkatan'] ?? '',
                $user['role'],
                $user['status'],
                $user['joined_date'],
                $user['last_active']
            ]);
        }

        fclose($output);
        exit;
    }

    public function activityLog()
    {
        $role = $this->sessionService->getRole();
        if ($role !== 'admin') {
            header('Location: /dashboard');
            exit;
        }

        $stats = $this->adminRepository->getActivityStats();
        $logs = $this->adminRepository->getActivityLogs();

        View::render('admin/activity_log', [
            'title' => 'Log Aktivitas - Admin',
            'stats' => $stats,
            'logs' => $logs
        ]);
    }

    public function exportUsersPdf()
    {
        $role = $this->sessionService->getRole();
        if ($role !== 'admin') {
            header('Location: /dashboard');
            exit;
        }

        $stats = $this->adminRepository->getUserStats();
        $users = $this->adminRepository->getAllUsersForExport();

        // Create PDF
        $pdf = new \App\Service\PdfGenerator(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        
        // Set document information
        $pdf->SetCreator('Younifirst Admin');
        $pdf->SetAuthor('Admin');
        $pdf->SetTitle('Daftar Pengguna');
        $pdf->SetSubject('Export Data Pengguna');
        
        // Set header
        $pdf->setHeaderInfo('DAFTAR PENGGUNA', 'Younifirst - Sistem Manajemen Kampus');
        
        // Set margins
        $pdf->SetMargins(15, 40, 15);
        $pdf->SetHeaderMargin(5);
        $pdf->SetFooterMargin(10);
        
        // Set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, 20);
        
        // Add page
        $pdf->AddPage();
        
        // Generate table
        $pdf->generateUsersTable($users, $stats);
        
        // Output PDF
        $pdf->Output('Daftar_Pengguna_' . date('Y-m-d') . '.pdf', 'D');
        exit;
    }

    public function exportActivityLogPdf()
    {
        $role = $this->sessionService->getRole();
        if ($role !== 'admin') {
            header('Location: /dashboard');
            exit;
        }

        $stats = $this->adminRepository->getActivityStats();
        $logs = $this->adminRepository->getActivityLogs();

        // Create PDF
        $pdf = new \App\Service\ActivityLogPdfGenerator(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        
        // Set document information
        $pdf->SetCreator('Younifirst Admin');
        $pdf->SetAuthor('Admin System');
        $pdf->SetTitle('Log Aktivitas Admin');
        $pdf->SetSubject('Laporan Log Aktivitas Pengguna');
        $pdf->SetKeywords('Log, Aktivitas, Admin, Report');
        
        // Set header
        $pdf->setHeaderInfo(
            'LOG AKTIVITAS ADMIN',
            'Younifirst - Sistem Manajemen Pengguna'
        );
        
        // Set margins
        $pdf->SetMargins(15, 45, 15);
        $pdf->SetHeaderMargin(5);
        $pdf->SetFooterMargin(10);
        
        // Set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, 20);
        
        // Add first page
        $pdf->AddPage();
        
        // Generate statistics section
        $pdf->generateStatisticsSection($stats);
        
        // Generate activity timeline
        $pdf->generateActivityTimeline($logs);
        
        // Add summary page at the end
        $pdf->generateSummaryPage($stats, count($logs));
        
        // Output PDF
        $filename = 'Log_Aktivitas_' . date('Y-m-d_His') . '.pdf';
        $pdf->Output($filename, 'D');
        exit;
    }

    public function callRequests()
    {
        $role = $this->sessionService->getRole();
        if ($role !== 'admin') {
            header('Location: /dashboard');
            exit;
        }

        $callRequestRepo = new \App\Repository\CallRequestRepository();
        
        $stats = $callRequestRepo->getStats();
        $requests = $callRequestRepo->getAllRequests();

        View::render('admin/call_requests', [
            'title' => 'Call Request - Admin',
            'stats' => $stats,
            'requests' => $requests
        ]);
    }

    public function disposeCallRequest()
    {
        header('Content-Type: application/json');
        
        $role = $this->sessionService->getRole();
        if ($role !== 'admin') {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            exit;
        }

        try {
            $requestId = $_POST['request_id'] ?? '';
            $notes = $_POST['notes'] ?? null;
            $adminId = $this->sessionService->current()->user_id ?? null;

            $callRequestRepo = new \App\Repository\CallRequestRepository();
            $callRequestRepo->updateStatus($requestId, 'disposed', $adminId, $notes);

            echo json_encode(['success' => true]);
        } catch (\Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function handleCall()
    {
        header('Content-Type: application/json');
        
        $role = $this->sessionService->getRole();
        if ($role !== 'admin') {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            exit;
        }

        try {
            $requestId = $_POST['request_id'] ?? '';
            $adminId = $this->sessionService->current()->user_id ?? null;

            $callRequestRepo = new \App\Repository\CallRequestRepository();
            $callRequestRepo->updateStatus($requestId, 'in_progress', $adminId);

            echo json_encode(['success' => true]);
        } catch (\Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function completeCall()
    {
        header('Content-Type: application/json');
        
        $role = $this->sessionService->getRole();
        if ($role !== 'admin') {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            exit;
        }

        try {
            $requestId = $_POST['request_id'] ?? '';
            $adminId = $this->sessionService->current()->user_id ?? null;

            $callRequestRepo = new \App\Repository\CallRequestRepository();
            $callRequestRepo->updateStatus($requestId, 'completed', $adminId);

            echo json_encode(['success' => true]);
        } catch (\Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    // ========================================
    // REPORTS METHODS
    // ========================================

    /**
     * Tampilkan halaman laporan masuk
     */
    public function reports()
    {
        $role = $this->sessionService->getRole();
        if ($role !== 'admin') {
            header('Location: /dashboard');
            exit;
        }

        View::render('admin/reports', [
            'title' => 'Laporan Masuk - Admin'
        ]);
    }

    /**
     * API: Get all reports dengan filter
     */
    public function getReports()
    {
        header('Content-Type: application/json');
        
        $role = $this->sessionService->getRole();
        if ($role !== 'admin') {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            exit;
        }

        try {
            $status = $_GET['status'] ?? null;
            $search = $_GET['search'] ?? null;

            $query = "SELECT r.id, r.judul, r.deskripsi, r.kategori, r.status, 
                             r.catatan, r.created_at, r.user_id,
                             u.username, u.email
                      FROM reports r
                      LEFT JOIN users u ON r.user_id = u.user_id
                      WHERE 1=1";
            
            $params = [];

            if ($status) {
                $query .= " AND r.status = ?";
                $params[] = $status;
            }

            if ($search) {
                $query .= " AND (r.judul LIKE ? OR r.deskripsi LIKE ? OR r.kategori LIKE ?)";
                $searchParam = "%$search%";
                $params[] = $searchParam;
                $params[] = $searchParam;
                $params[] = $searchParam;
            }

            $query .= " ORDER BY r.created_at DESC";

            $db = new \PDO(
                'pgsql:host=' . $_ENV['DB_HOST'] . ';port=' . $_ENV['DB_PORT'] . ';dbname=' . $_ENV['DB_NAME'],
                $_ENV['DB_USER'],
                $_ENV['DB_PASSWORD']
            );

            $stmt = $db->prepare($query);
            $stmt->execute($params);
            $reports = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            echo json_encode([
                'success' => true,
                'data' => $reports
            ]);

        } catch (\Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
        exit;
    }

    /**
     * API: Get single report detail
     */
    public function getReportDetail($id)
    {
        header('Content-Type: application/json');
        
        $role = $this->sessionService->getRole();
        if ($role !== 'admin') {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            exit;
        }

        try {
            $db = new \PDO(
                'pgsql:host=' . $_ENV['DB_HOST'] . ';port=' . $_ENV['DB_PORT'] . ';dbname=' . $_ENV['DB_NAME'],
                $_ENV['DB_USER'],
                $_ENV['DB_PASSWORD']
            );
            
            $stmt = $db->prepare("
                SELECT r.id, r.judul, r.deskripsi, r.kategori, r.status, 
                       r.catatan, r.created_at, r.user_id,
                       u.username, u.email
                FROM reports r
                LEFT JOIN users u ON r.user_id = u.user_id
                WHERE r.id = ?
            ");
            
            $stmt->execute([$id]);
            $report = $stmt->fetch(\PDO::FETCH_ASSOC);

            if (!$report) {
                http_response_code(404);
                echo json_encode([
                    'success' => false,
                    'message' => 'Laporan tidak ditemukan'
                ]);
                exit;
            }

            echo json_encode([
                'success' => true,
                'data' => $report
            ]);

        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
        exit;
    }

    /**
     * API: Update report status
     */
    public function updateReportStatus()
    {
        header('Content-Type: application/json');
        
        $role = $this->sessionService->getRole();
        if ($role !== 'admin') {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            exit;
        }

        try {
            $reportId = $_POST['report_id'] ?? null;
            $status = $_POST['status'] ?? null;
            $catatan = $_POST['catatan'] ?? null;

            if (!$reportId || !$status) {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'message' => 'Data tidak lengkap'
                ]);
                exit;
            }

            $validStatus = ['pending', 'diproses', 'ditinjau', 'ditolak'];
            if (!in_array($status, $validStatus)) {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'message' => 'Status tidak valid'
                ]);
                exit;
            }

            $db = new \PDO(
                'pgsql:host=' . $_ENV['DB_HOST'] . ';port=' . $_ENV['DB_PORT'] . ';dbname=' . $_ENV['DB_NAME'],
                $_ENV['DB_USER'],
                $_ENV['DB_PASSWORD']
            );
            
            $stmt = $db->prepare("
                UPDATE reports 
                SET status = ?, catatan = ?, updated_at = NOW()
                WHERE id = ?
            ");
            
            $result = $stmt->execute([$status, $catatan, $reportId]);

            if ($result) {
                $this->logActivity('Update Laporan', "Mengubah status laporan #$reportId menjadi $status");

                echo json_encode([
                    'success' => true,
                    'message' => 'Status berhasil diubah'
                ]);
            } else {
                throw new \Exception('Gagal mengubah status');
            }

        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
        exit;
    }

    /**
     * API: Get report statistics
     */
    public function getReportStats()
    {
        header('Content-Type: application/json');
        
        $role = $this->sessionService->getRole();
        if ($role !== 'admin') {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            exit;
        }

        try {
            $db = new \PDO(
                'pgsql:host=' . $_ENV['DB_HOST'] . ';port=' . $_ENV['DB_PORT'] . ';dbname=' . $_ENV['DB_NAME'],
                $_ENV['DB_USER'],
                $_ENV['DB_PASSWORD']
            );
            
            $statuses = ['pending', 'diproses', 'ditinjau', 'ditolak'];
            $stats = [];

            foreach ($statuses as $status) {
                $stmt = $db->prepare("SELECT COUNT(*) as count FROM reports WHERE status = ?");
                $stmt->execute([$status]);
                $stats[$status] = $stmt->fetch(\PDO::FETCH_ASSOC)['count'] ?? 0;
            }

            $stmt = $db->prepare("SELECT COUNT(*) as count FROM reports");
            $stmt->execute();
            $stats['total'] = $stmt->fetch(\PDO::FETCH_ASSOC)['count'] ?? 0;

            echo json_encode([
                'success' => true,
                'data' => $stats
            ]);

        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
        exit;
    }

    /**
     * Helper: Log activity
     */
    private function logActivity($action, $description)
    {
        try {
            $userId = $this->sessionService->current()->user_id ?? null;
            
            if ($userId) {
                $db = new \PDO(
                    'pgsql:host=' . $_ENV['DB_HOST'] . ';port=' . $_ENV['DB_PORT'] . ';dbname=' . $_ENV['DB_NAME'],
                    $_ENV['DB_USER'],
                    $_ENV['DB_PASSWORD']
                );

                $stmt = $db->prepare("
                    INSERT INTO activity_logs (user_id, action, description, created_at)
                    VALUES (?, ?, ?, NOW())
                ");
                $stmt->execute([$userId, $action, $description]);
            }
        } catch (\Exception $e) {
            // Silently fail logging
        }
    }
}