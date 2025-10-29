<?php
header('Content-Type: application/json');
require_once 'config.php';

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Get and sanitize input data
        $name = sanitize($_POST['name'] ?? '');
        $phone = sanitize($_POST['phone'] ?? '');
        $email = sanitize($_POST['email'] ?? '');
        $address = sanitize($_POST['address'] ?? '');
        $notes = sanitize($_POST['notes'] ?? '');
        $delivery = sanitize($_POST['delivery'] ?? '');
        $items = json_decode($_POST['items'] ?? '[]', true);
        $subtotal = floatval($_POST['subtotal'] ?? 0);
        $delivery_fee = floatval($_POST['deliveryFee'] ?? 0);
        $total = floatval($_POST['total'] ?? 0);

        // Validation
        if (empty($name) || empty($phone) || empty($address) || empty($delivery)) {
            throw new Exception('Missing required fields');
        }

        if (!validatePhone($phone)) {
            throw new Exception('Invalid phone number');
        }

        if (!empty($email) && !validateEmail($email)) {
            throw new Exception('Invalid email address');
        }

        if (empty($items)) {
            throw new Exception('No items selected');
        }

        // Start transaction
        $conn->begin_transaction();

        // Insert order
        $stmt = $conn->prepare("INSERT INTO orders (customer_name, customer_phone, customer_email, delivery_address, special_instructions, delivery_option, total_amount) VALUES (?, ?, ?, ?, ?, ?, ?)");
        
        if (!$stmt) {
            throw new Exception("Prepare failed: " . $conn->error);
        }

        $stmt->bind_param("ssssssd", $name, $phone, $email, $address, $notes, $delivery, $total);

        if (!$stmt->execute()) {
            throw new Exception("Execute failed: " . $stmt->error);
        }

        $order_id = $conn->insert_id;
        $stmt->close();

        // Insert order items
        $item_stmt = $conn->prepare("INSERT INTO order_items (order_id, item_name, quantity, price, subtotal) VALUES (?, ?, ?, ?, ?)");
        
        if (!$item_stmt) {
            throw new Exception("Prepare failed: " . $conn->error);
        }

        foreach ($items as $item_name => $quantity) {
            if ($quantity > 0) {
                // Get item price from menu
                $price_stmt = $conn->prepare("SELECT price FROM menu_items WHERE item_name = ?");
                $price_stmt->bind_param("s", $item_name);
                $price_stmt->execute();
                $result = $price_stmt->get_result();
                $menu_item = $result->fetch_assoc();
                $price_stmt->close();

                if ($menu_item) {
                    $price = floatval($menu_item['price']);
                    $item_subtotal = $price * $quantity;

                    $item_stmt->bind_param("isidi", $order_id, $item_name, $quantity, $price, $item_subtotal);

                    if (!$item_stmt->execute()) {
                        throw new Exception("Execute failed: " . $item_stmt->error);
                    }
                }
            }
        }
        $item_stmt->close();

        // Update or create customer
        $customer_stmt = $conn->prepare("INSERT INTO customers (name, phone, email, address) VALUES (?, ?, ?, ?) ON DUPLICATE KEY UPDATE total_orders = total_orders + 1");
        $customer_stmt->bind_param("ssss", $name, $phone, $email, $address);
        
        if (!$customer_stmt->execute()) {
            throw new Exception("Customer insert failed: " . $customer_stmt->error);
        }
        $customer_stmt->close();

        // Commit transaction
        $conn->commit();

        // Return success response
        echo json_encode([
            'success' => true,
            'message' => 'Order placed successfully!',
            'order_id' => $order_id,
            'total' => $total
        ]);

    } catch (Exception $e) {
        // Rollback transaction on error
        $conn->rollback();
        
        echo json_encode([
            'success' => false,
            'message' => $e->getMessage()
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request method'
    ]);
}

$conn->close();
?>
