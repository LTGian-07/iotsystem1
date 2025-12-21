<<<<<<< HEAD
<?php
require_once 'config.php';
require_once 'db.php';

// Kiểm tra phiên đăng nhập
requireLogin();

// Chỉ Admin mới được quản lý tài khoản
$current_user = getCurrentUser();
if ($current_user['role'] !== 'admin') {
    header('Location: index.php');
    exit();
}

$page_title = 'Quản lý Tài khoản';

// --- LOGIC TRUY VẤN DANH SÁCH NGƯỜI DÙNG ---
try {
    $users_col = Database::getCollection('users');
    $users = $users_col->find([], ['sort' => ['created_at' => -1]])->toArray();
    $db_error = false;
} catch (Exception $e) {
    $users = [];
    $db_error = true;
    $_SESSION['message'] = [
        'type' => 'danger',
        'text' => 'Lỗi kết nối database: ' . $e->getMessage()
    ];
}

include 'includes/header.php';
?>

<div class="page-header">
    <h1><i class="bi bi-people me-2"></i> Quản lý Tài khoản</h1>
    <a href="themTaiKhoan.php" class="btn btn-primary">
        <i class="bi bi-person-plus me-1"></i> Thêm Tài khoản mới
    </a>
</div>

<?php if (isset($_SESSION['message'])): ?>
    <div class="alert alert-<?php echo $_SESSION['message']['type']; ?> alert-dismissible fade show" role="alert">
        <?php echo $_SESSION['message']['text']; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php unset($_SESSION['message']); ?>
<?php endif; ?>

<div class="card shadow-sm">
    <div class="card-header">Danh sách Người dùng (<?php echo count($users); ?>)</div>
    <div class="card-body p-0">
        <?php if ($db_error): ?>
             <div class="alert alert-danger m-3">Không thể tải dữ liệu.</div>
        <?php elseif (empty($users)): ?>
            <div class="alert alert-info m-3">Chưa có tài khoản nào.</div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-striped table-hover mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Tên đầy đủ</th>
                            <th>Tên đăng nhập</th>
                            <th>Quyền</th>
                            <th>Ngày tạo</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1; foreach ($users as $user): 
                            $role_badge = match ($user['role']) {
                                'admin' => '<span class="badge bg-danger">Admin</span>',
                                'manager' => '<span class="badge bg-warning text-dark">Quản lý</span>',
                                default => '<span class="badge bg-secondary">User</span>',
                            };
                        ?>
                            <tr>
                                <td><?php echo $i++; ?></td>
                                <td><?php echo htmlspecialchars($user['fullname'] ?? 'N/A'); ?></td>
                                <td><?php echo htmlspecialchars($user['username'] ?? 'N/A'); ?></td>
                                <td><?php echo $role_badge; ?></td>
                                <td><?php echo isset($user['created_at']) ? date('d/m/Y H:i', $user['created_at']->toDateTime()->getTimestamp()) : 'N/A'; ?></td>
                                <td>
                                    <a href="suaTaiKhoan.php?id=<?php echo $user['_id']; ?>" 
                                       class="btn btn-sm btn-info text-white me-2">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    
                                    <?php if ((string)$user['_id'] !== (string)$current_user['_id']): ?>
                                        <button type="button" class="btn btn-sm btn-danger" 
                                                onclick="confirmDelete('<?php echo $user['_id']; ?>', '<?php echo htmlspecialchars($user['fullname']); ?>')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<div class="modal fade" id="deleteUserModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header"><h5>Xác nhận Xóa</h5></div>
            <div class="modal-body">Bạn có chắc muốn xóa <strong id="userNameToDelete"></strong>?</div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <a id="confirmDeleteButton" href="#" class="btn btn-danger">Xóa ngay</a>
            </div>
        </div>
    </div>
</div>

<script>
    function confirmDelete(userId, userName) {
        document.getElementById('userNameToDelete').textContent = userName;
        // Sửa lỗi 3: Dùng đường dẫn tương đối cho file xử lý
        const deleteUrl = 'xuLyTaiKhoan.php?action=delete&id=' + userId; 
        document.getElementById('confirmDeleteButton').href = deleteUrl;
        new bootstrap.Modal(document.getElementById('deleteUserModal')).show();
    }
</script>

=======
<?php
require_once 'config.php';
require_once 'db.php';

// Kiểm tra phiên đăng nhập
requireLogin();

// Chỉ Admin mới được quản lý tài khoản
$current_user = getCurrentUser();
if ($current_user['role'] !== 'admin') {
    header('Location: index.php');
    exit();
}

$page_title = 'Quản lý Tài khoản';

// --- LOGIC TRUY VẤN DANH SÁCH NGƯỜI DÙNG ---
try {
    $users_col = Database::getCollection('users');
    $users = $users_col->find([], ['sort' => ['created_at' => -1]])->toArray();
    $db_error = false;
} catch (Exception $e) {
    $users = [];
    $db_error = true;
    $_SESSION['message'] = [
        'type' => 'danger',
        'text' => 'Lỗi kết nối database: ' . $e->getMessage()
    ];
}

include 'includes/header.php';
?>

<div class="page-header">
    <h1><i class="bi bi-people me-2"></i> Quản lý Tài khoản</h1>
    <a href="themTaiKhoan.php" class="btn btn-primary">
        <i class="bi bi-person-plus me-1"></i> Thêm Tài khoản mới
    </a>
</div>

<?php if (isset($_SESSION['message'])): ?>
    <div class="alert alert-<?php echo $_SESSION['message']['type']; ?> alert-dismissible fade show" role="alert">
        <?php echo $_SESSION['message']['text']; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php unset($_SESSION['message']); ?>
<?php endif; ?>

<div class="card shadow-sm">
    <div class="card-header">Danh sách Người dùng (<?php echo count($users); ?>)</div>
    <div class="card-body p-0">
        <?php if ($db_error): ?>
             <div class="alert alert-danger m-3">Không thể tải dữ liệu.</div>
        <?php elseif (empty($users)): ?>
            <div class="alert alert-info m-3">Chưa có tài khoản nào.</div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-striped table-hover mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Tên đầy đủ</th>
                            <th>Tên đăng nhập</th>
                            <th>Quyền</th>
                            <th>Ngày tạo</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1; foreach ($users as $user): 
                            $role_badge = match ($user['role']) {
                                'admin' => '<span class="badge bg-danger">Admin</span>',
                                'manager' => '<span class="badge bg-warning text-dark">Quản lý</span>',
                                default => '<span class="badge bg-secondary">User</span>',
                            };
                        ?>
                            <tr>
                                <td><?php echo $i++; ?></td>
                                <td><?php echo htmlspecialchars($user['fullname'] ?? 'N/A'); ?></td>
                                <td><?php echo htmlspecialchars($user['username'] ?? 'N/A'); ?></td>
                                <td><?php echo $role_badge; ?></td>
                                <td><?php echo isset($user['created_at']) ? date('d/m/Y H:i', $user['created_at']->toDateTime()->getTimestamp()) : 'N/A'; ?></td>
                                <td>
                                    <a href="suaTaiKhoan.php?id=<?php echo $user['_id']; ?>" 
                                       class="btn btn-sm btn-info text-white me-2">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    
                                    <?php if ((string)$user['_id'] !== (string)$current_user['_id']): ?>
                                        <button type="button" class="btn btn-sm btn-danger" 
                                                onclick="confirmDelete('<?php echo $user['_id']; ?>', '<?php echo htmlspecialchars($user['fullname']); ?>')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<div class="modal fade" id="deleteUserModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header"><h5>Xác nhận Xóa</h5></div>
            <div class="modal-body">Bạn có chắc muốn xóa <strong id="userNameToDelete"></strong>?</div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <a id="confirmDeleteButton" href="#" class="btn btn-danger">Xóa ngay</a>
            </div>
        </div>
    </div>
</div>

<script>
    function confirmDelete(userId, userName) {
        document.getElementById('userNameToDelete').textContent = userName;
        // Sửa lỗi 3: Dùng đường dẫn tương đối cho file xử lý
        const deleteUrl = 'xuLyTaiKhoan.php?action=delete&id=' + userId; 
        document.getElementById('confirmDeleteButton').href = deleteUrl;
        new bootstrap.Modal(document.getElementById('deleteUserModal')).show();
    }
</script>

>>>>>>> a4c5e7def030979bbdbcc4a9d30360a35a72f944
<?php include 'includes/footer.php'; ?>