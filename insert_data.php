<<<<<<< HEAD
<?php
require_once 'db.php';

// 1. THIẾT LẬP MÀU SẮC (Dữ liệu cấu hình)
// Bước này định nghĩa các dải màu để hệ thống IoT so sánh
function setupInitialColors() {
    echo "--- Đang thiết lập danh mục màu sắc ---<br>";
    $sampleColors = [
        [
            'name' => 'Red', 'code' => '#FF0000',
            'min_r' => 150, 'min_g' => 0, 'min_b' => 0,
            'max_r' => 255, 'max_g' => 100, 'max_b' => 100,
            'sort_order' => 1, 'description' => 'Sản phẩm lỗi loại A'
        ],
        [
            'name' => 'Green', 'code' => '#00FF00',
            'min_r' => 0, 'min_g' => 150, 'min_b' => 0,
            'max_r' => 100, 'max_g' => 255, 'max_b' => 100,
            'sort_order' => 2, 'description' => 'Sản phẩm đạt chuẩn'
        ],
        [
            'name' => 'Blue', 'code' => '#0000FF',
            'min_r' => 0, 'min_g' => 0, 'min_b' => 150,
            'max_r' => 100, 'max_g' => 100, 'max_b' => 255,
            'sort_order' => 3, 'description' => 'Sản phẩm loại B'
        ]
    ];

    foreach ($sampleColors as $color) {
        $exists = Database::getCollection('colors')->findOne(['name' => $color['name']]);
        if (!$exists) {
            addColor($color);
            echo "✔ Đã thêm màu: " . $color['name'] . "<br>";
        } else {
            echo "ℹ Màu " . $color['name'] . " đã tồn tại.<br>";
        }
    }
}

// 2. TẠO NGƯỜI DÙNG (Dữ liệu quản lý)
function setupInitialUsers() {
    echo "<br>--- Đang tạo tài khoản ---<br>";
    $users = [
        [
            'username' => 'admin',
            'password' => 'admin123',
            'fullname' => 'Giang Giang',
            'email' => 'giang@iot-system.com',
            'role' => 'admin'
        ],
        [
            'username' => 'operator_01',
            'password' => 'op123456',
            'fullname' => 'Nhân viên vận hành 01',
            'role' => 'user'
        ]
    ];

    foreach ($users as $u) {
        $res = createUser($u);
        echo "👤 User " . $u['username'] . ": " . $res['message'] . "<br>";
    }
}

// 3. CHÈN DỮ LIỆU CẢM BIẾN (Dữ liệu vận hành)
// Giả lập dữ liệu từ cảm biến TCS3200 gửi về
function simulateIoTData($count = 5) {
    echo "<br>--- Đang giả lập dữ liệu cảm biến (IoT) ---<br>";
    $colors = getColors(); // Lấy danh sách màu đã tạo ở bước 1
    
    if (empty($colors)) return;

    for ($i = 0; $i < $count; $i++) {
        // Giả lập lấy ngẫu nhiên 1 màu trong DB
        $randomColor = $colors[array_rand($colors)];
        
        $productData = [
            'color_id' => (string)$randomColor['_id'],
            'rgb_r' => rand($randomColor['min_r'], $randomColor['max_r']),
            'rgb_g' => rand($randomColor['min_g'], $randomColor['max_g']),
            'rgb_b' => rand($randomColor['min_b'], $randomColor['max_b']),
            'confidence' => (float)(rand(85, 99) / 100),
            'batch_code' => generateBatchCode(),
            'line_id' => rand(1, 3)
        ];

        $id = addProduct($productData);
        echo "📦 Sản phẩm mới: " . $randomColor['name'] . " (ID: $id) - Confidence: " . $productData['confidence'] . "<br>";
    }
}

// CHẠY TỔNG HỢP
try {
    setupInitialColors();
    setupInitialUsers();
    simulateIoTData(10); // Chèn thử 10 sản phẩm
    echo "<hr><h3 style='color:green'>Hoàn tất cập nhật MongoDB Atlas!</h3>";
} catch (Exception $e) {
    echo "Lỗi: " . $e->getMessage();
=======
<?php
require_once 'db.php';

// 1. THIẾT LẬP MÀU SẮC (Dữ liệu cấu hình)
// Bước này định nghĩa các dải màu để hệ thống IoT so sánh
function setupInitialColors() {
    echo "--- Đang thiết lập danh mục màu sắc ---<br>";
    $sampleColors = [
        [
            'name' => 'Red', 'code' => '#FF0000',
            'min_r' => 150, 'min_g' => 0, 'min_b' => 0,
            'max_r' => 255, 'max_g' => 100, 'max_b' => 100,
            'sort_order' => 1, 'description' => 'Sản phẩm lỗi loại A'
        ],
        [
            'name' => 'Green', 'code' => '#00FF00',
            'min_r' => 0, 'min_g' => 150, 'min_b' => 0,
            'max_r' => 100, 'max_g' => 255, 'max_b' => 100,
            'sort_order' => 2, 'description' => 'Sản phẩm đạt chuẩn'
        ],
        [
            'name' => 'Blue', 'code' => '#0000FF',
            'min_r' => 0, 'min_g' => 0, 'min_b' => 150,
            'max_r' => 100, 'max_g' => 100, 'max_b' => 255,
            'sort_order' => 3, 'description' => 'Sản phẩm loại B'
        ]
    ];

    foreach ($sampleColors as $color) {
        $exists = Database::getCollection('colors')->findOne(['name' => $color['name']]);
        if (!$exists) {
            addColor($color);
            echo "✔ Đã thêm màu: " . $color['name'] . "<br>";
        } else {
            echo "ℹ Màu " . $color['name'] . " đã tồn tại.<br>";
        }
    }
}

// 2. TẠO NGƯỜI DÙNG (Dữ liệu quản lý)
function setupInitialUsers() {
    echo "<br>--- Đang tạo tài khoản ---<br>";
    $users = [
        [
            'username' => 'admin',
            'password' => 'admin123',
            'fullname' => 'Giang Giang',
            'email' => 'giang@iot-system.com',
            'role' => 'admin'
        ],
        [
            'username' => 'operator_01',
            'password' => 'op123456',
            'fullname' => 'Nhân viên vận hành 01',
            'role' => 'user'
        ]
    ];

    foreach ($users as $u) {
        $res = createUser($u);
        echo "👤 User " . $u['username'] . ": " . $res['message'] . "<br>";
    }
}

// 3. CHÈN DỮ LIỆU CẢM BIẾN (Dữ liệu vận hành)
// Giả lập dữ liệu từ cảm biến TCS3200 gửi về
function simulateIoTData($count = 5) {
    echo "<br>--- Đang giả lập dữ liệu cảm biến (IoT) ---<br>";
    $colors = getColors(); // Lấy danh sách màu đã tạo ở bước 1
    
    if (empty($colors)) return;

    for ($i = 0; $i < $count; $i++) {
        // Giả lập lấy ngẫu nhiên 1 màu trong DB
        $randomColor = $colors[array_rand($colors)];
        
        $productData = [
            'color_id' => (string)$randomColor['_id'],
            'rgb_r' => rand($randomColor['min_r'], $randomColor['max_r']),
            'rgb_g' => rand($randomColor['min_g'], $randomColor['max_g']),
            'rgb_b' => rand($randomColor['min_b'], $randomColor['max_b']),
            'confidence' => (float)(rand(85, 99) / 100),
            'batch_code' => generateBatchCode(),
            'line_id' => rand(1, 3)
        ];

        $id = addProduct($productData);
        echo "📦 Sản phẩm mới: " . $randomColor['name'] . " (ID: $id) - Confidence: " . $productData['confidence'] . "<br>";
    }
}

// CHẠY TỔNG HỢP
try {
    setupInitialColors();
    setupInitialUsers();
    simulateIoTData(10); // Chèn thử 10 sản phẩm
    echo "<hr><h3 style='color:green'>Hoàn tất cập nhật MongoDB Atlas!</h3>";
} catch (Exception $e) {
    echo "Lỗi: " . $e->getMessage();
>>>>>>> a4c5e7def030979bbdbcc4a9d30360a35a72f944
}