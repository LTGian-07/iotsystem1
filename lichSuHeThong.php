<<<<<<< HEAD
<?php
require_once 'config.php';
require_once 'db.php';
requireLogin(); //

$page_title = 'Lịch sử Hệ thống';
include 'includes/header.php';

$logs_col = Database::getCollection('activity_logs');
$logs = $logs_col->find([], ['sort' => ['created_at' => -1], 'limit' => 50])->toArray();
?>

<div class="container-fluid py-4">
    <h3><i class="bi bi-clock-history me-2"></i> Nhật ký hoạt động</h3>
    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Thời gian</th>
                        <th>Người thực hiện</th>
                        <th>Hành động</th>
                        <th>Chi tiết</th>
                        <th>IP</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($logs as $log): ?>
                    <tr>
                        <td><?php echo date('d/m/Y H:i:s', $log['created_at']->toDateTime()->getTimestamp()); ?></td>
                        <td><strong><?php echo htmlspecialchars($log['username']); ?></strong></td>
                        <td><span class="badge bg-info"><?php echo htmlspecialchars($log['action']); ?></span></td>
                        <td><?php echo htmlspecialchars($log['details']); ?></td>
                        <td><small><?php echo $log['ip_address']; ?></small></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

=======
<?php
require_once 'config.php';
require_once 'db.php';
requireLogin(); //

$page_title = 'Lịch sử Hệ thống';
include 'includes/header.php';

$logs_col = Database::getCollection('activity_logs');
$logs = $logs_col->find([], ['sort' => ['created_at' => -1], 'limit' => 50])->toArray();
?>

<div class="container-fluid py-4">
    <h3><i class="bi bi-clock-history me-2"></i> Nhật ký hoạt động</h3>
    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Thời gian</th>
                        <th>Người thực hiện</th>
                        <th>Hành động</th>
                        <th>Chi tiết</th>
                        <th>IP</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($logs as $log): ?>
                    <tr>
                        <td><?php echo date('d/m/Y H:i:s', $log['created_at']->toDateTime()->getTimestamp()); ?></td>
                        <td><strong><?php echo htmlspecialchars($log['username']); ?></strong></td>
                        <td><span class="badge bg-info"><?php echo htmlspecialchars($log['action']); ?></span></td>
                        <td><?php echo htmlspecialchars($log['details']); ?></td>
                        <td><small><?php echo $log['ip_address']; ?></small></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

>>>>>>> a4c5e7def030979bbdbcc4a9d30360a35a72f944
<?php include 'includes/footer.php'; ?>