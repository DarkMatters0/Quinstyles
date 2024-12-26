<?php

require_once 'database.class.php';

class Orders
{
    private $db;

    function __construct()
    {
        $this->db = new Database();
    }

    function getOrdersByCustomer($customer_id)
    {
        $sql = "SELECT 
                    o.order_id, 
                    o.order_date, 
                    o.status, 
                    SUM(oi.total) AS total_amount
                FROM orders o
                INNER JOIN order_items oi ON o.order_id = oi.order_id
                WHERE o.account_id = :customer_id
                GROUP BY o.order_id, o.order_date, o.status
                ORDER BY o.order_date DESC";
    
        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':customer_id', $customer_id, PDO::PARAM_INT);
        $query->execute();
    
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
    


    function refund($order_id, $description)
    {
        try {
            $db = $this->db->connect();
            $db->beginTransaction();
    
            // Insert order into `refund` table
            $insertSql = "INSERT INTO refund (order_id, description, created_at) VALUES (:order_id, :description, :created_at)";
            $insertQuery = $db->prepare($insertSql);
            $insertQuery->bindParam(':order_id', $order_id, PDO::PARAM_INT);
            $insertQuery->bindParam(':description', $description, PDO::PARAM_STR);
            $insertQuery->bindParam(':created_at', date('Y-m-d H:i:s'), PDO::PARAM_STR);
            $insertQuery->execute();
    
            $db->commit();
            return true;
        } catch (Exception $e) {
            if ($db->inTransaction()) {
                $db->rollBack();
            }
            error_log($e->getMessage());
            return false;
        }
    }
    

    function totalOrder(){
    $sql  = " SELECT COUNT(*) as total from orders";
    $query = $this->db->connect()->prepare($sql);
    $data = NULL;

    if ($query->execute()) {
        $data = $query->fetch();
    }
    return $data['total'] ?? 0;

    }
    

       




    function getReceiptDetails($order_id)
    {
        $sql = "
            SELECT 
                r.receipt_id,
                r.receipt_number,
                r.payment_date,
                r.payment_method,
                r.total_amount,
                o.order_id,
                o.order_date,
                o.status
            FROM 
                receipts r
            INNER JOIN 
                orders o ON r.order_id = o.order_id
            WHERE 
                o.order_id = :order_id
        ";

        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':order_id', $order_id, PDO::PARAM_INT);
        $query->execute();

        return $query->fetch(PDO::FETCH_ASSOC);
    }

    function getOrderDetails($order_id)
{
    $sql_order = "
        SELECT 
            o.order_id,
            o.order_date,
            o.status,
            a.username AS customer_name, 
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
            o.order_id, o.order_date, o.status, a.username
    ";

    $query_order = $this->db->connect()->prepare($sql_order);
    $query_order->bindParam(':order_id', $order_id, PDO::PARAM_INT);
    $query_order->execute();

    $order = $query_order->fetch(PDO::FETCH_ASSOC);

    if (!$order) {
        return null;
    }

    $sql_items = "
        SELECT 
            oi.order_item_id,
            COALESCE(p.name, cu.name) AS product_name,
            CASE 
                WHEN oi.custom_uniform_id IS NOT NULL THEN 'Custom Made'
                ELSE p.size
            END AS size,
            oi.quantity,
            COALESCE(p.price, cu.price) AS price,
            oi.total
        FROM 
            order_items oi
        LEFT JOIN 
            product p ON oi.product_id = p.product_id
        LEFT JOIN 
            custom_uniform cu ON oi.custom_uniform_id = cu.custom_uniform_id
        WHERE 
            oi.order_id = :order_id
    ";

    $query_items = $this->db->connect()->prepare($sql_items);
    $query_items->bindParam(':order_id', $order_id, PDO::PARAM_INT);
    $query_items->execute();

    $items = $query_items->fetchAll(PDO::FETCH_ASSOC);

    $order['items'] = $items;

    return $order;
}

    
    

}
