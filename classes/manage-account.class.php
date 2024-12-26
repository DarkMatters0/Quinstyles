<?php

require_once 'database.class.php';

class ManageAccount
{
    public $id = '';
    public $username = '';
    public $password = '';
    public $role = '';
    public $is_staff = '';
    public $is_admin = '';

    protected $db;

    function __construct()
    {
        $this->db = new Database();
    }

    function showAll() {
        $sql = "SELECT * FROM Account";
        
        $query = $this->db->connect()->prepare($sql);
        
        if ($query->execute()) {
            return $query->fetchAll(PDO::FETCH_ASSOC);
        }
        
        return null;
    }
    
    
    // Method to get an account by ID
    function getAccountById($id) {
        $sql = "SELECT * FROM Account WHERE id = :id";
        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':id', $id);
        $query->execute();
        return $query->fetch(PDO::FETCH_ASSOC);
    }

    // Method to get an account by username (added method)
    function getAccountByUsername($username) {
        $sql = "SELECT * FROM Account WHERE username = :username";
        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':username', $username);
        $query->execute();
        return $query->fetch(PDO::FETCH_ASSOC); // Fetch the account data
    }

    // Method to delete an account
    function deleteAccount($id) {
        $sql = "DELETE FROM Account WHERE id = :id";
        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':id', $id);
        return $query->execute();
    }

    function updateRole() {
        // Determine the values for is_staff and is_admin based on the role
        $is_staff = 0;
        $is_admin = 0;
    
        if ($this->role == 'staff') {
            $is_staff = 1;
        } elseif ($this->role == 'admin') {
            $is_staff = 1;
            $is_admin = 1;
        }
    
        // Update role, is_staff, and is_admin fields in the database
        $sql = "UPDATE Account SET role = :role, is_staff = :is_staff, is_admin = :is_admin WHERE id = :id";
        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':id', $this->id);
        $query->bindParam(':role', $this->role);
        $query->bindParam(':is_staff', $is_staff);
        $query->bindParam(':is_admin', $is_admin);
    
        return $query->execute();
    }

    function updateStatus()
    {
        $sql = "UPDATE orders SET status = :status WHERE order_id = :order_id";
    
        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':status', $status); // Use method parameter
        $query->bindParam(':order_id', $order_id); // Use method parameter
    

    
        return $query->execute();
    }
    

    function fetchAccountById($id) {
        $sql = "SELECT * FROM Account WHERE id = :id";
        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':id', $id);
        $query->execute();
        return $query->fetch(PDO::FETCH_ASSOC);
    }
    
    
}
