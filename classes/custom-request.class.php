<?php

require_once '../classes/database.class.php';

class CustomRequest
{
    protected $db;

    function __construct()
    {
        $this->db = new Database();
    }

    // Fetch all custom uniforms ordered, grouped by customer
    function getCustomMadeItems()
{
    $sql = "SELECT 
                cu.custom_uniform_id,
                cu.name AS custom_name,
                cu.gender, 
                cu.price, 
                cu.production_time_days,
                ca.username AS customer_name, 
                ci.quantity
            FROM custom_uniform cu
            INNER JOIN cart_items ci ON cu.custom_uniform_id = ci.custom_uniform_id
            INNER JOIN cart c ON ci.cart_id = c.cart_id
            INNER JOIN account ca ON c.account_id = ca.id
            ORDER BY ca.username"; // Orders by customer name

    $query = $this->db->connect()->prepare($sql);
    $query->execute();
    return $query->fetchAll(PDO::FETCH_ASSOC);
}


    // Fetch details of a specific custom uniform
    function getCustomUniformDetails($customUniformId)
    {
        $sql = "SELECT 
                    custom_uniform_id,
                    name,
                    gender,
                    chest_measurement,
                    waist_measurement,
                    hip_measurement,
                    shoulder_width,
                    sleeve_length,
                    pant_length,
                    custom_features,
                    price
                FROM custom_uniform
                WHERE custom_uniform_id = :custom_uniform_id";

        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':custom_uniform_id', $customUniformId, PDO::PARAM_INT);
        $query->execute();
        return $query->fetch(PDO::FETCH_ASSOC);
    }

    function updateCustomUniformPrice($customUniformId, $price)
{
    try {
        $sql = "UPDATE custom_uniform SET price = :price WHERE custom_uniform_id = :custom_uniform_id";
        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':price', $price, PDO::PARAM_STR);
        $query->bindParam(':custom_uniform_id', $customUniformId, PDO::PARAM_INT);
        return $query->execute();
    } catch (PDOException $e) {
        error_log($e->getMessage());
        return false;
    }
}

}
