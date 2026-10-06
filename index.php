<?php
// Yêu cầu 6: Cấu hình persistent cookie tồn tại 3 năm (tính bằng giây)
$lifetime = 60 * 60 * 24 * 365 * 3; 
session_set_cookie_params($lifetime, '/');
session_start();

// Khởi tạo mảng cart nếu chưa tồn tại
if (empty($_SESSION['cart12'])) {
    $_SESSION['cart12'] = array();
}

// Lấy dữ liệu danh sách sản phẩm mẫu
require_once('cart.php');
$products = get_products();

// Lấy action từ request
$action = filter_input(INPUT_POST, 'action');
if ($action == NULL) {
    $action = filter_input(INPUT_GET, 'action');
    if ($action == NULL) {
        $action = 'show_cart';
    }
}

// Điều hướng các action
switch ($action) {
    case 'show_add_item':
        include('add_item_view.php');
        break;

    case 'add':
        $product_key = filter_input(INPUT_POST, 'productkey');
        $item_qty = filter_input(INPUT_POST, 'itemqty');
        add_item($product_key, $item_qty);
        include('cart_view.php');
        break;

    case 'update':
        $new_qty_list = filter_input(INPUT_POST, 'newqty', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
        if ($new_qty_list !== NULL) {
            foreach ($new_qty_list as $key => $qty) {
                if ($_SESSION['cart12'][$key]['qty'] != $qty) {
                    update_item($key, $qty);
                }
            }
        }
        include('cart_view.php');
        break;

    case 'empty_cart':
        unset($_SESSION['cart12']);
        include('cart_view.php');
        break;

    // Yêu cầu 9: Xử lý xoá session và xoá cookie
    case 'end_session':
        // 1. Xóa toàn bộ dữ liệu session khỏi RAM
        $_SESSION = array();

        // 2. Hủy session ID trên server
        session_destroy();

        // 3. Xóa cookie session phía trình duyệt
        $name = session_name();
        $expire = time() - 42000;
        $params = session_get_cookie_params();
        setcookie($name, '', $expire, $params['path'], $params['domain'], $params['secure'], $params['httponly']);

        // Chuyển hướng lại trang chính
        header('Location: .');
        break;

    case 'show_cart':
    default:
        include('cart_view.php');
        break;
}
?>