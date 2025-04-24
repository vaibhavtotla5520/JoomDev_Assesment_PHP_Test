<?php
require_once __DIR__ . "/../CommonFunctions/db_config.php";
date_default_timezone_set('Asia/Kolkata');

class UserModel extends db
{
    private $table = 'users U';

    public function selectUser($select, $conditions, $where_type)
    {
        $this->create_conn();
        $where_arr = [];
        foreach ($conditions as $col => $val) {
            $where_arr[] = $col . "='" . $val . "'";
        }

        $where = implode($where_type, $where_arr);
        $query = "SELECT $select FROM $this->table WHERE $where AND U.user_active = 1";
        // die($query);
        $cust_data = $this->conn->query($query);
        if ($cust_data->num_rows > 0) {
            return $cust_data->fetch_assoc();
        } else {
            return 0;
        }
        return ["Error" => mysqli_error($this->conn)];
    }

    public function CreateUser($user_arr)
    {
        $col_arr = [];
        $val_arr = [];
        if (!empty($user_arr)) {
            foreach ($user_arr as $col => $val) {
                $col_arr[] = $col;
                $val_arr[] = gettype($val) === 'string' ? "'" . $val . "'" : $val;
            }
        }
        $query = "INSERT INTO users (" . implode(',', $col_arr) . ") VALUES (" . implode(',', $val_arr) . ")";
        // var_dump($this->conn->query($query));die;
        if ($this->conn->query($query) === TRUE) {
            return $this->conn->insert_id;
        } else {
            return $this->conn->error;
        }
        return 0;
    }

    public function checkUserExist($user_arr)
    {
        $select = " U.user_id ";
        // echo __DIR__;
        // var_dump( $this->selectUser($select, $user_arr, " OR ")); die;
        $getUser = $this->selectUser($select, $user_arr, " OR ");
        if (!empty($getUser) && count($getUser) > 0) {
            return 1;
        } else {
            return 0;
        }
    }

    public function listUser($select)
    {
        $this->create_conn();
        $query = "SELECT $select FROM $this->table;";
        // echo $query;
        $cust_data = $this->conn->query($query);
        $list_arr = [];
        if ($cust_data->num_rows > 0) {
            while ($row = $cust_data->fetch_assoc()) {
                $list_arr[] = $row;
            }
            return $list_arr;
        } else {
            return ["Error" => mysqli_error($this->conn)];
        }
        return 0;
    }

    public function CreateTask($task_arr)
    {
        $col_arr = [];
        $val_arr = [];
        if (!empty($task_arr)) {
            foreach ($task_arr as $col => $val) {
                $col_arr[] = $col;
                $val_arr[] = gettype($val) === 'string' ? "'" . $val . "'" : $val;
            }
        }
        $query = "INSERT INTO tasks (" . implode(',', $col_arr) . ") VALUES (" . implode(',', $val_arr) . ")";
        // echo $query;
        // die;
        $this->create_conn();
        if ($this->conn->query($query) === TRUE) {
            return $this->conn->insert_id;
        } else {
            return $this->conn->error;
        }
        // var_dump($this->conn->query($query));
        // echo $this->conn->error;
        // die;
        return 0;
    }

    public function listTask($select)
    {
        $this->create_conn();
        $where = "";
        if (!in_array('SA', explode(',', $_SESSION['User']['roles']))) {
            $where = "WHERE T.task_user_id = ".$_SESSION['User']['id']." ";
        }
        $query = "SELECT $select FROM tasks T INNER JOIN users U ON T.task_user_id = U.user_id $where;";
        $task_data = $this->conn->query($query);
        $list_arr = [];
        if ($task_data->num_rows > 0) {
            while ($row = $task_data->fetch_assoc()) {
                $list_arr[] = $row;
            }
            return $list_arr;
        } else {
            return ["Error" => mysqli_error($this->conn)];
        }
        return 0;
    }

    public function update_task($update_arr, $task_id) {
        $this->create_conn();
        $update_fields = [];
        foreach ($update_arr as $col => $val) {
            $update_fields[] = $col . "='" . $val . "'";
        }
        $update_fields = implode( ", ", $update_fields);
        $query = "UPDATE tasks T SET $update_fields WHERE T.task_id = $task_id";
    
        if ($this->conn->query($query) === TRUE) {
            return $this->conn->affected_rows;
        } else {
            return ["Error" => mysqli_error($this->conn)];
        }
        return 0;
    }

    public function selectTask($select, $conditions, $where_type) {
        $this->create_conn();
        $where_arr = [];
        foreach ($conditions as $col => $val) {
            $where_arr[] = $col . "='" . $val . "'";
        }
        
        $where = implode($where_type, $where_arr);
        $query = "SELECT $select FROM tasks T WHERE $where";
        // echo $query;
        $task_data = $this->conn->query($query);
        if ($task_data->num_rows > 0) {
            return $task_data->fetch_assoc();
        } else {
            return 0;
        }
        return ["Error" => mysqli_error($this->conn)];
    }
}
