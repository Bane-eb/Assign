<?php
require_once 'config.php';

echo "<h2>Database Connection Test</h2>";

// Test connection
if ($conn->connect_error) {
    echo "<p style='color: red;'><strong>❌ Connection Failed:</strong> " . $conn->connect_error . "</p>";
} else {
    echo "<p style='color: green;'><strong>✓ Connection Successful!</strong></p>";
}

// Test database exists
$result = $conn->query("SELECT DATABASE()");
$db = $result->fetch_row();
echo "<p><strong>Current Database:</strong> " . ($db[0] ? $db[0] : "None") . "</p>";

// Test tables
echo "<h3>Tables in Database:</h3>";
$tables = $conn->query("SHOW TABLES");
if ($tables->num_rows > 0) {
    echo "<ul>";
    while ($table = $tables->fetch_row()) {
        echo "<li>" . $table[0] . "</li>";
    }
    echo "</ul>";
} else {
    echo "<p style='color: orange;'>⚠️ No tables found. Run database.sql to create tables.</p>";
}

// Test menu items
echo "<h3>Menu Items:</h3>";
$menu = $conn->query("SELECT * FROM menu_items LIMIT 5");
if ($menu && $menu->num_rows > 0) {
    echo "<p style='color: green;'>✓ Found " . $menu->num_rows . " menu items</p>";
    while ($item = $menu->fetch_assoc()) {
        echo "<p>" . $item['item_name'] . " - ₵" . $item['price'] . "</p>";
    }
} else {
    echo "<p style='color: orange;'>⚠️ No menu items found. Insert sample data from database.sql</p>";
}

$conn->close();
?>
