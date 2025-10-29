<?php
header('Content-Type: application/json');
require_once 'config.php';

try {
    // Get all menu items grouped by category
    $query = "SELECT category, item_name, description, price FROM menu_items WHERE is_available = TRUE ORDER BY category, item_name";
    
    $result = $conn->query($query);

    if (!$result) {
        throw new Exception("Query failed: " . $conn->error);
    }

    $menu = [];
    while ($row = $result->fetch_assoc()) {
        $category = $row['category'];
        
        if (!isset($menu[$category])) {
            $menu[$category] = [];
        }

        $menu[$category][] = [
            'name' => $row['item_name'],
            'description' => $row['description'],
            'price' => floatval($row['price'])
        ];
    }

    echo json_encode([
        'success' => true,
        'menu' => $menu
    ]);

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}

$conn->close();
?>
