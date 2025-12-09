<?php
class Log
{
    private $conn;
    private $table_name = "activity_logs";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function create($user_id, $nama_user, $role, $action)
    {
        $query = "INSERT INTO " . $this->table_name . " 
                  (user_id, nama_user, role, action, ip_address, device_info) 
                  VALUES (:user_id, :nama_user, :role, :action, :ip, :device)";

        $stmt = $this->conn->prepare($query);

        $ip = $_SERVER['REMOTE_ADDR'];
        $device = $_SERVER['HTTP_USER_AGENT'];

        $stmt->bindParam(":user_id", $user_id);
        $stmt->bindParam(":nama_user", $nama_user);
        $stmt->bindParam(":role", $role);
        $stmt->bindParam(":action", $action);
        $stmt->bindParam(":ip", $ip);
        $stmt->bindParam(":device", $device);

        return $stmt->execute();
    }

    public function getAll()
    {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
