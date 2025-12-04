<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Moderasi Konten' ?></title>
    <link rel="stylesheet" href="/css/variable.css">
    <link rel="stylesheet" href="/css/sidebar.css">
    <link rel="stylesheet" href="/css/admin_dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        .moderation-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            padding: 20px 0;
        }
        .moderation-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            border: 1px solid #e5e7eb;
        }
        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 15px;
        }
        .type-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }
        .type-competition { background: #e0e7ff; color: #4338ca; }
        .type-team { background: #dbeafe; color: #1e40af; }
        .type-event { background: #fce7f3; color: #be185d; }
        .type-lost_found { background: #ffedd5; color: #c2410c; }
        
        .card-title {
            font-size: 16px;
            font-weight: 600;
            color: #111827;
            margin-bottom: 8px;
        }
        .card-date {
            font-size: 12px;
            color: #6b7280;
            margin-bottom: 20px;
        }
        .card-actions {
            display: flex;
            gap: 10px;
        }
        .btn-action {
            flex: 1;
            padding: 8px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 500;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            transition: all 0.2s;
        }
        .btn-approve {
            background: #dcfce7;
            color: #166534;
        }
        .btn-approve:hover { background: #bbf7d0; }
        .btn-reject {
            background: #fee2e2;
            color: #991b1b;
        }
        .btn-reject:hover { background: #fecaca; }
    </style>
</head>
<body>
    
    <?php require __DIR__ . '/../layouts/sidebar.php'; ?>
    
    <div class="main-content">
        <div class="admin-dashboard">
            <div class="dashboard-header">
                <h1>Moderasi Konten</h1>
                <div class="header-actions">
                    <span class="badge medium"><?= count($pendingItems) ?> Pending</span>
                </div>
            </div>

            <?php if (isset($_GET['status'])): ?>
            <div class="alert alert-<?= $_GET['status'] === 'success' ? 'success' : 'danger' ?>" style="margin-bottom: 20px; padding: 15px; border-radius: 8px; background: <?= $_GET['status'] === 'success' ? '#d1fae5' : '#fee2e2' ?>; color: <?= $_GET['status'] === 'success' ? '#065f46' : '#991b1b' ?>;">
                <?= htmlspecialchars($_GET['message'] ?? '') ?>
            </div>
            <?php endif; ?>

            <?php if (empty($pendingItems)): ?>
            <div class="empty-state" style="text-align: center; padding: 40px; color: #6b7280;">
                <i class="bi bi-check-circle" style="font-size: 48px; margin-bottom: 16px; display: block;"></i>
                <p>Tidak ada konten yang perlu dimoderasi saat ini.</p>
            </div>
            <?php else: ?>
            <div class="moderation-grid">
                <?php foreach ($pendingItems as $item): ?>
                <div class="moderation-card">
                    <div class="card-header">
                        <span class="type-badge type-<?= $item['type'] ?>">
                            <?= ucfirst(str_replace('_', ' ', $item['type'])) ?>
                        </span>
                        <a href="/<?= $item['type'] === 'lost_found' ? 'lost_found' : $item['type'] ?>/detail/<?= $item['id'] ?>" target="_blank" style="color: #6b7280;">
                            <i class="bi bi-box-arrow-up-right"></i>
                        </a>
                    </div>
                    <div class="card-title"><?= htmlspecialchars($item['title']) ?></div>
                    <div class="card-date">
                        <i class="bi bi-clock"></i> <?= !empty($item['created_at']) ? date('d M Y H:i', strtotime($item['created_at'])) : '-' ?>
                    </div>
                    <div class="card-actions">
                        <form action="/admin/moderation/update" method="POST" style="flex: 1;">
                            <input type="hidden" name="type" value="<?= $item['type'] ?>">
                            <input type="hidden" name="id" value="<?= $item['id'] ?>">
                            <input type="hidden" name="status" value="rejected">
                            <button type="submit" class="btn-action btn-reject" onclick="return confirm('Tolak konten ini?')">
                                <i class="bi bi-x-lg"></i> Tolak
                            </button>
                        </form>
                        <form action="/admin/moderation/update" method="POST" style="flex: 1;">
                            <input type="hidden" name="type" value="<?= $item['type'] ?>">
                            <input type="hidden" name="id" value="<?= $item['id'] ?>">
                            <input type="hidden" name="status" value="approved">
                            <button type="submit" class="btn-action btn-approve">
                                <i class="bi bi-check-lg"></i> Setuju
                            </button>
                        </form>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
