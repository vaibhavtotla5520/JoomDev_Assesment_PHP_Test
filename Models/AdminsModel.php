<?php
require_once __DIR__ . "/../CommonFunctions/db_config.php";
date_default_timezone_set('Asia/Kolkata');

class AdminModel extends db
{
    private $table = 'users U';

    public function selectUser($select, $conditions, $where_type)
    {
        $this->create_conn();
        $where_arr = [];
        foreach ($conditions as $col => $val) {
            $where_arr[] = $col . "='" . $val . "'";
        }
        $where_arr[] = "U.user_active = 1";
        $where = implode($where_type, $where_arr);
        $query = "SELECT $select FROM $this->table WHERE $where";
        // echo $query;
        $cust_data = $this->conn->query($query);
        if ($cust_data->num_rows > 0) {
            return $cust_data->fetch_assoc();
        } else {
            return 0;
        }
        return 0;
    }

    public function reset_password($password, $user_id) {
        $update_arr = [
            'U.user_password' => md5($password),
            'U.user_last_password_change_timestamp' => date('Y-m-d H:i:s')
        ];
        $result = $this->updateUser($update_arr, (int)$user_id);
        $last_password_change = $this->selectUser(" TIMESTAMPDIFF(DAY, U.user_last_password_change_timestamp, NOW()) AS last_password_change_days", ["U.user_id" => $user_id, "U.user_active" => 1], " AND ");
        if ($result == 1) {
            return $last_password_change; 
        }
        return 0;
    }

    public function Authenticate($email, $password) {
        $password = md5($password);
        $cust_data = $this->selectUser("U.user_id, U.user_firstname, TIMESTAMPDIFF(DAY, U.user_last_password_change_timestamp, NOW()) AS last_password_change_days, U.user_role, U.user_last_password_change_timestamp", [
            "U.user_email" => $email,
            "U.user_password" => $password
        ], " AND ");
        // print_r($cust_data);
        // die;
        if (!empty($cust_data) && $cust_data != 0) {
            $checkUpdate = $this->updateUser([
                "U.user_last_login" => date('Y-m-d H:i:s')
            ], $cust_data['user_id']);
            $cust_data['last_password_change_days'] = $cust_data['last_password_change_days'] ?? 0;
            if ($checkUpdate > 0) {
                return $cust_data;
            }
        } else {
            return -1;
        }
        return 0;
    }

    public function updateUser($update_arr, $user_id) {
        $this->create_conn();
        $update_fields = [];
        foreach ($update_arr as $col => $val) {
            $update_fields[] = $col . "='" . $val . "'";
        }
        $update_fields = implode( ", ", $update_fields);
        $query = "UPDATE $this->table SET $update_fields WHERE U.user_id = $user_id";

        if ($this->conn->query($query) === TRUE) {
            return $this->conn->affected_rows;
        } else {
            return ["Error" => mysqli_error($this->conn)];
        }
        return 0;
    }

}
