# Sparks Snack Bar - Database Setup Guide

## Prerequisites
- PHP 7.0 or higher
- MySQL/MariaDB server running
- Apache or any PHP-compatible web server

## Setup Instructions

### Step 1: Create the Database
1. Open phpMyAdmin or MySQL command line
2. Run the SQL commands from `database.sql` file:
   ```sql
   CREATE DATABASE IF NOT EXISTS sparks_snack_bar;
   USE sparks_snack_bar;
   ```
3. Import the `database.sql` file to create all tables

### Step 2: Configure Database Connection
Edit `config.php` and update the following credentials:
```php
define('DB_HOST', 'localhost');    // Your MySQL host
define('DB_USER', 'root');         // Your MySQL username
define('DB_PASS', '');             // Your MySQL password
define('DB_NAME', 'sparks_snack_bar');
```

### Step 3: File Structure
```
pancakes/
├── index.html              (Home page)
├── menu.html               (Menu page)
├── order.html              (Order form)
├── config.php              (Database configuration)
├── process_order.php       (Handle order submissions)
├── get_menu.php            (Fetch menu items from DB)
├── database.sql            (Database schema)
└── DATABASE_SETUP.md       (This file)
```

## Database Tables

### orders
Stores customer orders with details:
- id: Order ID
- customer_name: Customer name
- customer_phone: Phone number
- customer_email: Email address
- delivery_address: Delivery location
- special_instructions: Special requests
- delivery_option: Home delivery or pickup
- total_amount: Order total
- status: Order status (pending, confirmed, delivered)

### order_items
Stores individual items in each order:
- id: Item ID
- order_id: Reference to orders table
- item_name: Name of ordered item
- quantity: Quantity ordered
- price: Price per unit
- subtotal: Total for this item

### menu_items
Stores all available menu items:
- id: Item ID
- category: Category (Dry Pancakes, Fluffy Mini Pancakes, Extras)
- item_name: Item name
- description: Item description
- price: Item price
- is_available: Availability status

### customers
Stores customer information:
- id: Customer ID
- name: Customer name
- phone: Phone number (unique)
- email: Email address
- address: Delivery address
- total_orders: Number of orders placed

## API Endpoints

### POST /process_order.php
Submits a new order to the database.

**Parameters:**
- name: Customer name
- phone: Phone number
- email: Email (optional)
- address: Delivery address
- notes: Special instructions
- delivery: Delivery option
- items: JSON array of items
- subtotal: Order subtotal
- deliveryFee: Delivery fee
- total: Order total

**Response:**
```json
{
  "success": true,
  "message": "Order placed successfully!",
  "order_id": 1,
  "total": 65
}
```

### GET /get_menu.php
Fetches all available menu items.

**Response:**
```json
{
  "success": true,
  "menu": {
    "Dry Pancakes": [
      {
        "name": "Treasure Mix (Small)",
        "description": "Small portion...",
        "price": 30
      }
    ]
  }
}
```

## Testing

1. Start your PHP server:
   ```bash
   php -S localhost:8000
   ```

2. Visit http://localhost:8000 in your browser

3. Fill out the order form and submit

4. Check the database to verify the order was saved

## Troubleshooting

**Connection Error:**
- Verify MySQL is running
- Check DB_HOST, DB_USER, DB_PASS in config.php
- Ensure database exists

**Order Not Saving:**
- Check PHP error logs
- Verify all required fields are filled
- Check database permissions

**Menu Not Loading:**
- Verify menu_items table has data
- Check get_menu.php response in browser console

## Security Notes

- Always validate and sanitize user input (done in config.php)
- Use prepared statements to prevent SQL injection (implemented)
- Consider adding HTTPS for production
- Implement user authentication for admin functions
- Never expose database credentials in frontend code
