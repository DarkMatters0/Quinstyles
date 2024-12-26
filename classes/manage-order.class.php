<?php

require_once 'database.class.php';

class Order
{
    public $order_id = '';
    public $account_id = '';
    public $order_date = '';
    public $total_amount = '';

    protected $db;

    function __construct()
    {
        $this->db = new Database();
    }

    // Fetch all orders with associated user details and total amounts
    function showAll()
    {
        $sql = "
            SELECT 
                o.order_id,
                a.username,
                a.email,
                o.order_date AS date_created,
                o.status, -- Include status column
                SUM(oi.total) AS total_amount
            FROM 
                orders o
            INNER JOIN 
                account a ON o.account_id = a.id
            INNER JOIN 
                order_items oi ON o.order_id = oi.order_id
            GROUP BY 
                o.order_id, a.username, a.email, o.order_date, o.status -- Add status to GROUP BY
            ORDER BY 
                o.order_date DESC
        ";
    
        $query = $this->db->connect()->prepare($sql);
    
        $data = null;
    
        if ($query->execute()) {
            $data = $query->fetchAll(PDO::FETCH_ASSOC);
        }
    
        return $data;
    }
    

    function getStatusById($order_id)
    {
        $sql = "SELECT status FROM orders WHERE order_id = :order_id";

        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':order_id', $order_id);

        if ($query->execute()) {
            return $query->fetch(PDO::FETCH_ASSOC);
        }

        return null;
    }

    function updateStatus($order_id, $status) {
        $sql = "UPDATE orders SET status = :status WHERE order_id = :order_id";
        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':status', $status);
        $query->bindParam(':order_id', $order_id);
        
        if ($query->execute()) {
            return true;
        } else {
            error_log("Update failed: " . print_r($query->errorInfo(), true));
            return false;
        }
    }
    
    


    // Fetch a single order by ID with associated details
    public function showOrder($order_id)
    {
        $sql = "
            SELECT 
                o.order_id,
                a.username,
                a.email,
                o.order_date AS date_created,
                SUM(oi.total) AS total_amount
            FROM 
                orders o
            INNER JOIN 
                account a ON o.account_id = a.id
            INNER JOIN 
                order_items oi ON o.order_id = oi.order_id
            WHERE 
                o.order_id = :order_id
            GROUP BY 
                o.order_id, a.username, a.email, o.order_date
        ";

        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':order_id', $order_id);

        $data = null;

        if ($query->execute()) {
            $data = $query->fetch(PDO::FETCH_ASSOC);
        }

        return $data;
    }

    // Add a new order
    public function add()
    {
        $sql = "INSERT INTO orders (account_id, order_date) VALUES (:account_id, :order_date);";

        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':account_id', $this->account_id);
        $query->bindParam(':order_date', $this->order_date);

        return $query->execute();
    }

    function fetchRecord($order_id)
{
    // Query to get order details
    $sqlOrderDetails = "
        SELECT 
            o.order_id,
            o.order_date,
            a.username,
            a.email,
            SUM(oi.total) AS total_amount
        FROM 
            orders o
        INNER JOIN 
            account a ON o.account_id = a.id
        INNER JOIN 
            order_items oi ON o.order_id = oi.order_id
        WHERE 
            o.order_id = :order_id
        GROUP BY 
            o.order_id, o.order_date, a.username, a.email
    ";

    // Query to get purchased items
    $sqlOrderItems = "
        SELECT 
            p.name AS product_name,
            oi.quantity,
            oi.price,
            oi.total
        FROM 
            order_items oi
        INNER JOIN 
            products p ON oi.product_id = p.id
        WHERE 
            oi.order_id = :order_id
    ";

    // Prepare and execute order details query
    $queryOrderDetails = $this->db->connect()->prepare($sqlOrderDetails);
    $queryOrderDetails->bindParam(':order_id', $order_id);
    $queryOrderDetails->execute();
    $orderDetails = $queryOrderDetails->fetch(PDO::FETCH_ASSOC);

    // Prepare and execute purchased items query
    $queryOrderItems = $this->db->connect()->prepare($sqlOrderItems);
    $queryOrderItems->bindParam(':order_id', $order_id);
    $queryOrderItems->execute();
    $orderItems = $queryOrderItems->fetchAll(PDO::FETCH_ASSOC);

    // Combine order details and items
    return [
        'order_id' => $orderDetails['order_id'],
        'order_date' => $orderDetails['order_date'],
        'username' => $orderDetails['username'],
        'email' => $orderDetails['email'],
        'total_amount' => $orderDetails['total_amount'],
        'items' => $orderItems
    ];
}
}
