<?php

require_once 'database.class.php';

class CustomItem
{
    public $customer_id = '';
    public $custom_uniform_id = '';
    public $quantity = '';

    protected $db;

    function __construct()
    {
        $this->db = new Database();
    }

    // Insert a new custom uniform into the custom_uniform table
    function createCustomUniform($data)
    {
        $sql = "INSERT INTO custom_uniform (
                    name, gender, chest_measurement, waist_measurement, hip_measurement,
                    shoulder_width, sleeve_length, pant_length, custom_features, price
                ) VALUES (
                    :name, :gender, :chest_measurement, :waist_measurement, :hip_measurement,
                    :shoulder_width, :sleeve_length, :pant_length, :custom_features, :price
                )";
        $query = $this->db->connect()->prepare($sql);
        $params = [
            ':name' => $data['name'],
            ':gender' => $data['gender'],
            ':chest_measurement' => $data['chest_measurement'] ?? null,
            ':waist_measurement' => $data['waist_measurement'] ?? null,
            ':hip_measurement' => $data['hip_measurement'] ?? null,
            ':shoulder_width' => $data['shoulder_width'] ?? null,
            ':sleeve_length' => $data['sleeve_length'] ?? null,
            ':pant_length' => $data['pant_length'] ?? null,
            ':custom_features' => $data['custom_features'] ?? null,
            ':price' => $data['price'] ?? null, // Optional price field
        ];
        if ($query->execute($params)) {
            return $this->db->connect()->lastInsertId(); // Return the ID of the newly inserted custom uniform
        }
        return false;
    }

// Add a custom uniform to the cart_items table
function addToCartCustom($custom_uniform_id, $quantity)
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
        $cart_id = $this->db->connect()->lastInsertId();
    } else {
        $cart_id = $cart['cart_id'];
    }

    // Step 3: Check if the custom uniform already exists in the cart
    $sql = "SELECT quantity FROM cart_items 
            WHERE cart_id = :cart_id AND custom_uniform_id = :custom_uniform_id";
    $query = $this->db->connect()->prepare($sql);
    $query->execute([
        ':cart_id' => $cart_id,
        ':custom_uniform_id' => $custom_uniform_id,
    ]);
    $existingCartItem = $query->fetch();

    if ($existingCartItem) {
        // Step 4: Update the quantity if the custom item is already in the cart
        $newQuantity = $existingCartItem['quantity'] + $quantity;

        $sql = "UPDATE cart_items SET quantity = :quantity 
                WHERE cart_id = :cart_id AND custom_uniform_id = :custom_uniform_id";
        $query = $this->db->connect()->prepare($sql);
        $query->execute([
            ':quantity' => $newQuantity,
            ':cart_id' => $cart_id,
            ':custom_uniform_id' => $custom_uniform_id,
        ]);
    } else {
        // Step 5: Add a new custom item to the cart with a total set to 0 if price is not available
        $sql = "INSERT INTO cart_items (cart_id, custom_uniform_id, quantity, total)
                VALUES (:cart_id, :custom_uniform_id, :quantity, 0)";
        $query = $this->db->connect()->prepare($sql);
        $query->execute([
            ':cart_id' => $cart_id,
            ':custom_uniform_id' => $custom_uniform_id,
            ':quantity' => $quantity,
        ]);
    }

    return true; // Operation completed successfully
}



    // Show custom items in the cart (from cart_items table)
    function showCustomItems()
    {
        // Query to fetch only custom items from the cart (excluding regular products)
        $sql = "SELECT ci.cart_item_id, 
                       cu.custom_uniform_id as custom_uniform_id, 
                       cu.name AS Custom_Name, 
                       cu.gender, 
                       cu.price, 
                       ci.quantity, 
                       ci.total
                FROM cart_items ci
                LEFT JOIN custom_uniform cu ON ci.custom_uniform_id = cu.custom_uniform_id
                WHERE ci.custom_uniform_id IS NOT NULL
                  AND ci.cart_id = (
                      SELECT cart_id 
                      FROM cart 
                      WHERE account_id = :account_id
                      LIMIT 1
                  )";
        
        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':account_id', $this->customer_id, PDO::PARAM_INT);
    
        try {
            $query->execute();
            $result = $query->fetchAll(PDO::FETCH_ASSOC);
    
            // Optional: Debugging output to log results
            error_log(print_r($result, true));
    
            return $result ?: []; // Return results or an empty array if no matches
        } catch (PDOException $e) {
            // Log the error for debugging and rethrow it for further handling
            error_log("Error in showCustomItems: " . $e->getMessage());
            throw new Exception("Failed to fetch custom items: " . $e->getMessage());
        }
    }
    
    

    function processPurchaseCustom($cart_item_id, $account_id)
    {
        try {
            $this->db->connect()->beginTransaction();
    
            // Step 1: Get custom item details from cart_items and custom_uniform
            $sql = "SELECT cu.custom_uniform_id, cu.price, ci.quantity 
                    FROM cart_items ci
                    JOIN custom_uniform cu ON ci.custom_uniform_id = cu.custom_uniform_id
                    WHERE ci.cart_item_id = :cart_item_id";
            $query = $this->db->connect()->prepare($sql);
            $query->execute([':cart_item_id' => $cart_item_id]);
            $cartItem = $query->fetch(PDO::FETCH_ASSOC);
    
            if (!$cartItem) {
                throw new Exception("Cart item not found");
            }
    
            // Extract data
            $custom_uniform_id = $cartItem['custom_uniform_id'];
            $price = $cartItem['price'];
            $quantity = $cartItem['quantity'];
    
            // Check if price is 0 or NULL
            if (is_null($price) || $price == 0) {
                $this->db->connect()->rollBack();
                error_log("Invalid price for custom_uniform_id: $custom_uniform_id");
                return false;
            }
    
            // Calculate total
            $total = $price * $quantity;
    
            // Step 2: Insert into orders table
            $sql = "INSERT INTO orders (account_id, order_date, status) 
                    VALUES (:account_id, NOW(), 'paid')";
            $query = $this->db->connect()->prepare($sql);
            $query->execute([':account_id' => $account_id]);
            $order_id = $this->db->connect()->lastInsertId();
    
            // Step 3: Insert into receipts table
            $receipt_number = uniqid('REC-', true); // Generate a unique receipt number
            $payment_method = 'Credit Card'; // Customize as needed
            $sql = "INSERT INTO receipts (order_id, receipt_number, payment_date, payment_method, total_amount) 
                    VALUES (:order_id, :receipt_number, NOW(), :payment_method, :total_amount)";
            $query = $this->db->connect()->prepare($sql);
            $query->execute([
                ':order_id' => $order_id,
                ':receipt_number' => $receipt_number,
                ':payment_method' => $payment_method,
                ':total_amount' => $total
            ]);
    
            // Step 4: Insert into order_items table
            $sql = "INSERT INTO order_items (order_id, custom_uniform_id, quantity, total) 
                    VALUES (:order_id, :custom_uniform_id, :quantity, :total)";
            $query = $this->db->connect()->prepare($sql);
            $query->execute([
                ':order_id' => $order_id,
                ':custom_uniform_id' => $custom_uniform_id,
                ':quantity' => $quantity,
                ':total' => $total
            ]);
    
            // Step 5: Remove the custom item from the cart
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
    
    

    // Other methods remain unchanged
}
