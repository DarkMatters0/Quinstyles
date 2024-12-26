<?php

require_once 'database.class.php';
require_once 'stocks.class.php';

class Cart
{
    public $cart_id = '';
    public $customer_id = '';

    protected $db;

    function __construct()
    {
        $this->db = new Database();
    }

    function getProductId($name, $gender, $size)
    {
        $sql = "SELECT product_id 
                FROM product
                WHERE name = :name AND gender = :gender AND size = :size LIMIT 1";
        $query = $this->db->connect()->prepare($sql);
        $query->execute([
            ':name' => $name,
            ':gender' => $gender,
            ':size' => $size,
        ]);
        $result = $query->fetch();
        return $result ? $result['product_id'] : false;
    }

    // Show all cart items for the customer
    function showCart()
    {
        // Ensure customer_id is set
        if (!isset($this->customer_id) || empty($this->customer_id)) {
            throw new Exception("Customer ID is not set or invalid.");
        }
    
        // Fetch the cart_id for the given customer
        $sql = "SELECT cart_id FROM cart WHERE account_id = :account_id LIMIT 1";
        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':account_id', $this->customer_id, PDO::PARAM_INT);
    
        try {
            $query->execute();
            $cart = $query->fetch(PDO::FETCH_ASSOC);
    
            if (!$cart) {
                return []; // No cart found for this customer
            }
    
            $cart_id = $cart['cart_id'];
    
            // Fetch only product items from the identified cart_id
            $sql = "SELECT 
                        ci.cart_item_id as cart_item_id, 
                        ci.cart_id, 
                        ci.product_id, 
                        ci.quantity, 
                        ci.total, 
                        p.name AS Product_Name, 
                        p.price AS Price, 
                        p.size, 
                        p.gender
                    FROM cart_items ci
                    INNER JOIN product p ON ci.product_id = p.product_id
                    WHERE ci.cart_id = :cart_id 
                      AND ci.custom_uniform_id IS NULL"; // Exclude custom uniforms
    
            $query = $this->db->connect()->prepare($sql);
            $query->bindParam(':cart_id', $cart_id, PDO::PARAM_INT);
    
            $query->execute();
            $result = $query->fetchAll(PDO::FETCH_ASSOC);
    
            return $result ?: []; // Return product items or an empty array
        } catch (PDOException $e) {
            throw new Exception("Failed to fetch cart items: " . $e->getMessage());
        }
    }
    
    

// Add product to cart
function addToCart($product_id, $quantity)
{
    // Step 1: Check if the customer already has an active cart
    $sql = "SELECT cart_id FROM cart WHERE account_id = :account_id LIMIT 1";
    $query = $this->db->connect()->prepare($sql);
    $query->execute([':account_id' => $this->customer_id]);
    $cart = $query->fetch();

    // Step 2: If no cart exists, create a new cart
    if (!$cart) {
        $sql = "INSERT INTO cart (account_id) VALUES (:account_id)";
        $query = $this->db->connect()->prepare($sql);
        $query->execute([':account_id' => $this->customer_id]);
        $this->cart_id = $this->db->connect()->lastInsertId(); // Get the ID of the newly created cart
    } else {
        // If the cart already exists, set cart_id from the existing cart
        $this->cart_id = $cart['cart_id'];
    }

    // Step 3: Check if the product already exists in the cart by product_id
    $sql = "SELECT ci.quantity, p.price 
            FROM cart_items ci
            INNER JOIN product p ON ci.product_id = p.product_id
            WHERE ci.cart_id = :cart_id AND ci.product_id = :product_id";
    $query = $this->db->connect()->prepare($sql);
    $query->execute([':cart_id' => $this->cart_id, ':product_id' => $product_id]);
    $existingCartItem = $query->fetch();

    if ($existingCartItem) {
        // Step 4: Update the quantity and total if the product is already in the cart
        $newQuantity = $existingCartItem['quantity'] + $quantity; // Add the new quantity to the existing one
        $newTotal = $existingCartItem['price'] * $newQuantity; // Recalculate the total price

        $sql = "UPDATE cart_items SET quantity = :quantity, total = :total 
                WHERE cart_id = :cart_id AND product_id = :product_id";
        $query = $this->db->connect()->prepare($sql);
        $query->execute([
            ':quantity' => $newQuantity,
            ':total' => $newTotal,
            ':cart_id' => $this->cart_id,
            ':product_id' => $product_id,
        ]);
    } else {
        // Step 5: Add a new item to the cart
        // Fetch the price of the product
        $price = $this->getProductPrice($product_id); // Assuming getProductPrice is a method to fetch the price

        $sql = "INSERT INTO cart_items (cart_id, product_id, quantity, total)
                VALUES (:cart_id, :product_id, :quantity, :total)";
        $query = $this->db->connect()->prepare($sql);
        $query->execute([
            ':cart_id' => $this->cart_id,
            ':product_id' => $product_id,
            ':quantity' => $quantity,
            ':total' => $price * $quantity, // Calculate total price
        ]);
    }
}


    

    
    

    // Method to fetch product price
    function getProductPrice($product_id)
    {
        $sql = "SELECT price FROM product WHERE product_id = :product_id LIMIT 1";
        $query = $this->db->connect()->prepare($sql);
        $query->execute([':product_id' => $product_id]);
        $result = $query->fetch();
        return $result ? $result['price'] : 0;
    }

    // Process Purchases and Insert into stock_out Table
// Process Purchases and Insert into stock_out Table
// Process a Single Cart Item Purchase and Insert into stock_out Table
function processPurchases($cart_item_id, $account_id)
{
    try {
        $this->db->connect()->beginTransaction();

        // Step 1: Get cart item details
        $sql = "SELECT ci.product_id, ci.quantity, (p.price * ci.quantity) AS total 
                FROM cart_items ci 
                INNER JOIN product p ON ci.product_id = p.product_id 
                WHERE ci.cart_item_id = :cart_item_id";
        $query = $this->db->connect()->prepare($sql);
        $query->execute([':cart_item_id' => $cart_item_id]);
        $cartItem = $query->fetch(PDO::FETCH_ASSOC);

        if (!$cartItem) {
            throw new Exception("Cart item not found");
        }

        $product_id = $cartItem['product_id'];
        $quantity = $cartItem['quantity'];
        $total = $cartItem['total'];

        // Step 2: Check stock availability
        $stocks = new Stocks();  // Assuming Stocks class handles stock-related actions
        $availableStock = $stocks->getAvailableStocks($product_id);

        if ($availableStock === null || $availableStock < $quantity) {
            throw new Exception("Not enough stock available for product ID: $product_id");
        }

        // Step 3: Reduce stock
        $stocks->product_id = $product_id;
        $stocks->quantity = $quantity;
        $stocks->status = 'out';
        $stocks->reason = 'purchase';

        if (!$stocks->purchaseStockOut()) {
            throw new Exception("Failed to reduce stock for product ID: $product_id");
        }

        // Step 4: Insert into orders table
        $sql = "INSERT INTO orders (account_id, order_date, status) 
                VALUES (:account_id, NOW(), 'paid')";
        $query = $this->db->connect()->prepare($sql);
        $query->execute([':account_id' => $account_id]);
        $order_id = $this->db->connect()->lastInsertId();

        // Step 5: Insert into receipts table
        $receipt_number = uniqid('REC-', true); // Generate a unique receipt number
        $payment_method = 'Credit Card'; // Customize this as needed
        $sql = "INSERT INTO receipts (order_id, receipt_number, payment_date, payment_method, total_amount) 
                VALUES (:order_id, :receipt_number, NOW(), :payment_method, :total_amount)";
        $query = $this->db->connect()->prepare($sql);
        $query->execute([
            ':order_id' => $order_id,
            ':receipt_number' => $receipt_number,
            ':payment_method' => $payment_method,
            ':total_amount' => $total
        ]);

        // Step 6: Insert into order_items table
        $sql = "INSERT INTO order_items (order_id, product_id, quantity, total) 
                VALUES (:order_id, :product_id, :quantity, :total)";
        $query = $this->db->connect()->prepare($sql);
        $query->execute([
            ':order_id' => $order_id,
            ':product_id' => $product_id,
            ':quantity' => $quantity,
            ':total' => $total
        ]);

        // Step 7: Remove the cart item
        $sql = "DELETE FROM cart_items WHERE cart_item_id = :cart_item_id";
        $query = $this->db->connect()->prepare($sql);
        $query->execute([':cart_item_id' => $cart_item_id]);

        $this->db->connect()->commit();
        return true;

    } catch (Exception $e) {
        $this->db->connect()->rollBack();
        error_log($e->getMessage());
        return false;
    }
}




    function deleteCartItem($cart_item_id)
{
    try {
        $sql = "DELETE FROM cart_items WHERE cart_item_id = :cart_item_id";
        $query = $this->db->connect()->prepare($sql);
        $query->execute([':cart_item_id' => $cart_item_id]);

        // Check if any rows were affected
        if ($query->rowCount() > 0) {
            return true;
        } else {
            return false; // No rows affected, invalid `cart_item_id`
        }
    } catch (PDOException $e) {
        error_log("Failed to delete cart item: " . $e->getMessage());
        return false;
    }
}

}
