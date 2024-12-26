<?php

require_once '../classes/database.class.php'; // Adjust path as needed

class Product
{
    protected $db;

    function __construct()
    {
        $this->db = new Database();
    }

    // Fetch all products
    function showAll($gender = '')
    {
        // Corrected SQL query to include total stocks and available stocks
        $sql = "
            SELECT 
                p.product_id,
                p.name,
                p.description,
                p.gender,
                p.size,
                p.price,
                COALESCE(SUM(s.quantity), 0) AS total_stocks,
                COALESCE(SUM(s.quantity) - 
                    (SELECT COALESCE(SUM(s_out.quantity), 0) 
                     FROM stock_out s_out 
                     WHERE s_out.stock_id = s.stock_id), 0) AS available_stocks
            FROM product p
            LEFT JOIN stocks s ON p.product_id = s.product_id
            WHERE p.gender LIKE CONCAT('%', :gender, '%')
            GROUP BY p.product_id
            ORDER BY p.name ASC;
        ";
    
        // Prepare the query
        $query = $this->db->connect()->prepare($sql);
    
        // Bind parameters
        $query->bindParam(':gender', $gender);
    
        // Execute the query and fetch data
        $data = null;
        if ($query->execute()) {
            $data = $query->fetchAll(PDO::FETCH_ASSOC);
        }
    
        // Return the data
        return $data;
    }
    

    
    
    
    
    

    function updatePrice()
{
    // SQL query to update the price of a specific product
    $sql = "UPDATE product SET price = :price WHERE product_id = :product_id";

    // Prepare the query
    $query = $this->db->connect()->prepare($sql);

    // Bind parameters
    $query->bindParam(':price', $this->price); // Ensure 'price' property is set
    $query->bindParam(':product_id', $this->product_id); // Fix the property name to 'product_id'

    // Execute the query
    return $query->execute();
}

    

}
?>
