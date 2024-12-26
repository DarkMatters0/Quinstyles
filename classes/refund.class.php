<?php

require_once 'database.class.php';

class Refund
{
    public $cart_bin_id = '';
    public $cart_id = '';
    public $account_id = '';

    protected $db;

    function __construct()
    {
        $this->db = new Database();
    }

    function showAll() {
        // SQL query to fetch refund data
        $sql = "SELECT r.refund_id, 
                       o.order_id, 
                       r.created_at, 
                       SUM(oi.total) AS amount, 
                       r.description
                FROM refund r
                INNER JOIN orders o ON r.order_id = o.order_id
                LEFT JOIN order_items oi ON o.order_id = oi.order_id
                GROUP BY r.refund_id, o.order_id, r.created_at, r.description
                ORDER BY r.created_at DESC;";
    
        // Prepare the query
        $query = $this->db->connect()->prepare($sql);
    
        // Initialize data variable
        $data = null;
    
        // Execute the query and fetch results
        if ($query->execute()) {
            $data = $query->fetchAll(PDO::FETCH_ASSOC);
        }
    
        // Return the data
        return $data;
    }
    


}
