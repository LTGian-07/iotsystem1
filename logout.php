<<<<<<< HEAD
<?php
// logout.php - Đăng xuất
require_once 'config.php';

// Destroy session
session_start();
session_unset();
session_destroy();

// Clear remember me cookie
if (isset($_COOKIE['remember_token'])) {
    setcookie('remember_token', '', time() - 3600, '/');
}

// Redirect to login page
header("Location: login.php?logout=1");
exit();
=======
<?php
// logout.php - Đăng xuất
require_once 'config.php';

// Destroy session
session_start();
session_unset();
session_destroy();

// Clear remember me cookie
if (isset($_COOKIE['remember_token'])) {
    setcookie('remember_token', '', time() - 3600, '/');
}

// Redirect to login page
header("Location: login.php?logout=1");
exit();
>>>>>>> a4c5e7def030979bbdbcc4a9d30360a35a72f944
?>