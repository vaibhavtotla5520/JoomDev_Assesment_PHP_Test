<?php
class db
{
  public $conn;

  public function create_conn()
  {
    if (!$this->conn) {
      $this->conn = new mysqli('localhost', 'root', '1234', 'test');
      if ($this->conn->connect_error) {
        die("Connection failed: " . $this->conn->connect_error);
      }
      // echo "Connection Successfull";
    }
  }

  public function create_schema()
  {
    $this->create_conn();
    $sql = "CREATE TABLE IF NOT EXISTS users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    user_firstname VARCHAR(25) NOT NULL,
    user_lastname VARCHAR(25) NOT NULL,
    user_email VARCHAR(75) NOT NULL UNIQUE,
    user_password VARCHAR(100) NOT NULL,
    user_role VARCHAR(10) COMMENT 'AD=admin,SA=superadmin,US=user',
    user_phone VARCHAR(13),
    user_created_timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    user_last_login TIMESTAMP NULL DEFAULT NULL,
    user_last_password_change_timestamp TIMESTAMP NULL DEFAULT NULL,
    user_active TINYINT DEFAULT 1
    );";
    if ($this->conn->query($sql) === TRUE) {
      echo "USERS TABLE CREATED<br/>";
    } else {
      echo "ERROR CREATING USERS:" . $this->conn->error;
    }

    $insert_user = "INSERT INTO `users` (`user_firstname`, `user_lastname`, `user_email`, `user_password`, `user_role`, `user_phone`, `user_created_timestamp`, `user_last_login`, `user_last_password_change_timestamp`, `user_active`) VALUES ('Super', 'Admin', 'super@admin.com', 'e6e061838856bf47e1de730719fb2609', 'SA,AD,US', '0000000000', NOW(), NULL, NULL, '1')";
    if ($this->conn->query($insert_user) === TRUE) {
      echo "Admin User Created: email->super@admin.com password->admin@123<br/>";
    } else {
      echo "ERROR CREATING USERS:" . $this->conn->error;
    }

    $sql = "CREATE TABLE IF NOT EXISTS tasks (
      task_id INT AUTO_INCREMENT PRIMARY KEY,
      task_user_id INT NOT NULL,
      task_name VARCHAR(25) NOT NULL,
      task_start_time DATETIME NOT NULL,
      task_stop_time DATETIME NOT NULL,
      task_description TEXT NOT NULL,
      task_notes TEXT NOT NULL,
      task_created_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );";
    if ($this->conn->query($sql) === TRUE) {
      echo "TASKS TABLE CREATED<br/>";
    } else {
      echo "ERROR CREATING TASKS:" . $this->conn->error;
    }
  }
}
