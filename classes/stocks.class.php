<?php

require_once 'database.class.php';

class Stocks
{
    public $product_id = '';
    public $quantity = '';
    public $reason = '';

    protected $db;

    function __construct()
    {
        $this->db = new Database();
    }

    // Add new stock (stock-in)
    function addStockIn()
    {
        // Check if stock already exists for the given product
        $sqlCheckStock = "SELECT stock_id, quantity FROM stocks WHERE product_id = :product_id LIMIT 1";
        $queryCheckStock = $this->db->connect()->prepare($sqlCheckStock);
        $queryCheckStock->bindParam(':product_id', $this->product_id);
        $queryCheckStock->execute();
    
        $stock = $queryCheckStock->fetch(PDO::FETCH_ASSOC);
    
        if ($stock) {
            // If stock exists, update the quantity
            $newQuantity = $stock['quantity'] + $this->quantity;
    
            $sqlUpdateStock = "UPDATE stocks SET quantity = :quantity, updated_at = CURRENT_TIMESTAMP WHERE stock_id = :stock_id";
            $queryUpdateStock = $this->db->connect()->prepare($sqlUpdateStock);
            $queryUpdateStock->bindParam(':quantity', $newQuantity);
            $queryUpdateStock->bindParam(':stock_id', $stock['stock_id']);
    
            if ($queryUpdateStock->execute()) {
                // Record the stock-in details
                $sqlStockIn = "INSERT INTO stock_in (stock_id, quantity, reason) VALUES (:stock_id, :quantity, :reason)";
                $queryStockIn = $this->db->connect()->prepare($sqlStockIn);
                $queryStockIn->bindParam(':stock_id', $stock['stock_id']);
                $queryStockIn->bindParam(':quantity', $this->quantity);
                $queryStockIn->bindParam(':reason', $this->reason);
    
                return $queryStockIn->execute();
            }
        } else {
            // If stock does not exist, create a new entry
            $sqlStocks = "INSERT INTO stocks (product_id, quantity) VALUES (:product_id, :quantity)";
            $queryStocks = $this->db->connect()->prepare($sqlStocks);
            $queryStocks->bindParam(':product_id', $this->product_id);
            $queryStocks->bindParam(':quantity', $this->quantity);
    
            if ($queryStocks->execute()) {
                $stock_id = $this->db->connect()->lastInsertId();
    
                // Record the stock-in details
                $sqlStockIn = "INSERT INTO stock_in (stock_id, quantity, reason) VALUES (:stock_id, :quantity, :reason)";
                $queryStockIn = $this->db->connect()->prepare($sqlStockIn);
                $queryStockIn->bindParam(':stock_id', $stock_id);
                $queryStockIn->bindParam(':quantity', $this->quantity);
                $queryStockIn->bindParam(':reason', $this->reason);
    
                return $queryStockIn->execute();
            }
        }
    
        return false;
    }
    

    // Add stock-out
// Add stock-out
function addStockOut()
{
    // Check available stock first (calculated from stock_in and stock_out)
    $availableStock = $this->getAvailableStocks($this->product_id);
    if ($availableStock < $this->quantity) {
        throw new Exception("Not enough stock available to fulfill this request.");
    }

    // Get the stock_id of the existing stock
    $sqlCheckStock = "SELECT stock_id, quantity FROM stocks WHERE product_id = :product_id LIMIT 1";
    $queryCheckStock = $this->db->connect()->prepare($sqlCheckStock);
    $queryCheckStock->bindParam(':product_id', $this->product_id);
    $queryCheckStock->execute();

    $stock = $queryCheckStock->fetch(PDO::FETCH_ASSOC);

    if ($stock) {
        // Add stock-out entry
        $sqlStockOut = "INSERT INTO stock_out (stock_id, quantity, reason) VALUES (:stock_id, :quantity, :reason)";
        $queryStockOut = $this->db->connect()->prepare($sqlStockOut);
        $queryStockOut->bindParam(':stock_id', $stock['stock_id']);
        $queryStockOut->bindParam(':quantity', $this->quantity);
        $queryStockOut->bindParam(':reason', $this->reason);

        if ($queryStockOut->execute()) {
            // Do not modify stock in the stocks table, just leave it unchanged
            // The available stock will be tracked based on stock_in and stock_out

            return true; // Successfully added stock-out entry
        }
    }

    return false; // If no stock found or stock-out not added
}

function purchaseStockOut()
{
    // Check available stock first (calculated from stock_in and stock_out)


    // Get the stock_id of the existing stock
    $sqlCheckStock = "SELECT stock_id, quantity FROM stocks WHERE product_id = :product_id LIMIT 1";
    $queryCheckStock = $this->db->connect()->prepare($sqlCheckStock);
    $queryCheckStock->bindParam(':product_id', $this->product_id);
    $queryCheckStock->execute();

    $stock = $queryCheckStock->fetch(PDO::FETCH_ASSOC);

    if ($stock) {
        // Add stock-out entry
        $sqlStockOut = "INSERT INTO stock_out (stock_id, quantity, reason) VALUES (:stock_id, :quantity, :reason)";
        $queryStockOut = $this->db->connect()->prepare($sqlStockOut);
        $queryStockOut->bindParam(':stock_id', $stock['stock_id']);
        $queryStockOut->bindParam(':quantity', $this->quantity);
        $queryStockOut->bindParam(':reason', $this->reason);

        if ($queryStockOut->execute()) {
            // Do not modify stock in the stocks table, just leave it unchanged
            // The available stock will be tracked based on stock_in and stock_out

            return true; // Successfully added stock-out entry
        }
    }

    return false; // If no stock found or stock-out not added
}


    // Get available stock
    function getAvailableStocks($product_id)
    {
        // SQL query to calculate the available stock by subtracting stock-out quantity from the stock quantity in the stocks table
        $sql = "
SELECT 
    GREATEST(COALESCE(s.quantity, 0) - COALESCE(SUM(so.quantity), 0), 0) AS available_stock
FROM stocks s
LEFT JOIN stock_out so ON s.stock_id = so.stock_id
WHERE s.product_id = :product_id
GROUP BY s.product_id"
;
        
        // Prepare the SQL statement
        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':product_id', $product_id);
        
        // Execute the query and fetch the result
        $data = null;
        if ($query->execute()) {
            $data = $query->fetchColumn();
        }
        
        // Return available stock, or 0 if no stock is available
        return $data ? $data : 0;
    }
    
    
}
