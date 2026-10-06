<?php
// Mảng danh sách sản phẩm mẫu
$products = array();
$products['MKN-852'] = array('name' => 'Fender Stratocaster', 'cost' => '699.00');
$products['MKN-853'] = array('name' => 'Gibson Les Paul', 'cost' => '1199.00');
$products['MKN-854'] = array('name' => 'Gibson SG', 'cost' => '2517.00');

// Hàm lấy danh sách sản phẩm
function get_products() {
    global $products;
    return $products;
}

// Hàm thêm sản phẩm vào giỏ hàng
function add_item($key, $quantity) {
    global $products;
    if ($quantity < 1) return;

    // Nếu sản phẩm đã có trong giỏ, cộng thêm số lượng
    if (isset($_SESSION['cart12'][$key])) {
        $quantity += $_SESSION['cart12'][$key]['qty'];
        update_item($key, $quantity);
        return;
    }

    // Nếu chưa có, thêm mới
    $cost = $products[$key]['cost'];
    $total = $cost * $quantity;
    $item = array(
        'name' => $products[$key]['name'],
        'cost' => $cost,
        'qty'  => $quantity,
        'total' => $total
    );
    $_SESSION['cart12'][$key] = $item;
}

// Hàm cập nhật số lượng sản phẩm
function update_item($key, $quantity) {
    $quantity = (int) $quantity;
    if (isset($_SESSION['cart12'][$key])) {
        if ($quantity <= 0) {
            unset($_SESSION['cart12'][$key]);
        } else {
            $_SESSION['cart12'][$key]['qty'] = $quantity;
            $total = $_SESSION['cart12'][$key]['cost'] * $_SESSION['cart12'][$key]['qty'];
            $_SESSION['cart12'][$key]['total'] = $total;
        }
    }
}

// Hàm tính tổng tiền giỏ hàng
function get_subtotal() {
    $subtotal = 0;
    if (isset($_SESSION['cart12'])) {
        foreach ($_SESSION['cart12'] as $item) {
            $subtotal += $item['total'];
        }
    }
    return number_format($subtotal, 2);
}
?>