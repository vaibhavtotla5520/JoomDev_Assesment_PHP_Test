<?php
require_once __DIR__ . '/../Models/UserModel.php';

class UserController extends UserModel
{

    public function __construct()
    {
        require_once __DIR__ . '/../CommonFunctions/Functions.php';
    }

    public function usersList()
    {
        $select = "U.user_id, U.user_firstname, U.user_lastname, U.user_email, U.user_phone, U.user_last_login, U.user_last_password_change_timestamp, U.user_active";
        $userList = $this->listUser($select);
        if ($userList != 0) {
            echo json_encode($userList);
            return;
        }
        echo json_encode(['No Users Found']);
        return;
    }

    public function usersAdd()
    {
        if (empty($_POST)) {
            echo json_encode(['error' => 'Values Can Not Be Empty']);
            return;
        }

        if (empty($_POST['firstname'])) {
            echo json_encode(['error' => 'First Name Can Not Be Empty']);
        } else if (empty($_POST['lastname'])) {
            echo json_encode(['error' => 'Last Name Can Not Be Empty']);
        } else if (empty($_POST['email'])) {
            echo json_encode(['error' => 'Email Can Not Be Empty']);
        } else if (empty($_POST['password'])) {
            echo json_encode(['error' => 'Password Can Not Be Empty']);
        } else if (empty($_POST['phone'])) {
            echo json_encode(['error' => 'Phone Can Not Be Empty']);
        } else {
            $firstname = $_POST['firstname'];
            $lastname = $_POST['lastname'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $phone = $_POST['phone'];
        }
        $checkUser = $this->checkUserExist([
            'U.user_email' => $email,
            'U.user_phone' => $phone
        ]);
        // var_dump($checkUser);
        // echo __LINE__;
        // die;
        if ($checkUser == 0) {
            $insert_arr = [
                'user_firstname' => $firstname,
                'user_lastname' => $lastname,
                'user_email' => $email,
                'user_password' => md5($password),
                'user_phone' => $phone,
                'user_role' => "US",
                'user_active' => 1

            ];
            if ($this->CreateUser($insert_arr) !== 0) {
                echo json_encode(['success' => 'Successfully Added, Go To Login']);
            } else {
                echo json_encode(['error' => 'Failed To Add User']);
            }
        } else {
            // echo __LINE__;
            echo json_encode(['error' => 'Already Added With This Email or Phone, Try Login']);
        }
        // die (__LINE__);
        return;
    }

    public function tasksAdd()
    {
        if (empty($_POST)) {
            echo json_encode(['error' => 'Values Can Not Be Empty']);
            return;
        }

        if (empty($_POST['taskName'])) {
            echo json_encode(['error' => 'Task Name Can Not Be Empty']);
        } else if (empty($_POST['startTime'])) {
            echo json_encode(['error' => 'Start Time Can Not Be Empty']);
        } else {
            $taskName = $_POST['taskName'];
            $startTime = $_POST['startTime'];
            $stopTime = $_POST['stopTime'] ?? null;
            $description = $_POST['description'] ?? null;
            $notes = $_POST['notes'] ?? null;
        }

        $insert_arr = [
            'task_user_id' => $_SESSION['User']['id'],
            'task_name' => $taskName,
            'task_start_time' => $startTime,
            'task_stop_time' => $stopTime,
            'task_description' => $description,
            'task_notes' => $notes
        ];

        if ($this->CreateTask($insert_arr) !== 0) {
            echo json_encode(['success' => 'Task Successfully Added']);
        } else {
            echo json_encode(['error' => 'Failed To Add Task']);
        }
        return;
    }

    public function tasksList()
    {
        $select = "U.user_firstname, T.task_id, T.task_name, T.task_start_time, T.task_stop_time, T.task_description, T.task_notes";
        $taskList = $this->listTask($select);
        if ($taskList != 0) {
            echo json_encode($taskList);
            return;
        }
        echo json_encode(['No Tasks Found']);
        return;
    }

    public function getTask()
    {
        if (empty($_POST['task_id'])) {
            echo json_encode(['error' => 'Task ID is required']);
            return;
        }

        $select = "T.task_id, T.task_name, T.task_start_time, T.task_stop_time, T.task_description, T.task_notes";
        $task = $this->selectTask($select, ['T.task_id' => $_POST['task_id']], " AND ");

        if ($task != 0) {
            echo json_encode($task);
        } else {
            echo json_encode(['error' => 'Task not found']);
        }
        return;
    }

    public function updateTask()
    {
        if (empty($_POST['task_id'])) {
            echo json_encode(['error' => 'Task ID is required']);
            return;
        }

        $update_arr = [
            'task_name' => $_POST['taskName'],
            'task_start_time' => $_POST['startTime'],
            'task_stop_time' => $_POST['stopTime'] ?? null,
            'task_description' => $_POST['description'] ?? null,
            'task_notes' => $_POST['notes'] ?? null
        ];

        $result = $this->update_task($update_arr, $_POST['task_id']);

        if ($result > 0) {
            echo json_encode(['success' => 'Task updated successfully']);
        } else {
            echo json_encode(['error' => 'Failed to update task']);
        }
        return;
    }

    public function downloanTaskReportCSV() {
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="tasks_report_' . date('Y-m-d') . '.csv"');
        
        $output = fopen('php://output', 'w');
        fwrite($output, "\xEF\xBB\xBF");
        fputcsv($output, [
            'User Name',
            'Task ID',
            'Task Name', 
            'Start Time',
            'Stop Time',
            'Description',
            'Notes'
        ]);
        $query = "SELECT U.user_firstname, T.task_id, T.task_name, T.task_start_time, 
                         T.task_stop_time, T.task_description, T.task_notes 
                  FROM tasks T 
                  INNER JOIN users U ON T.task_user_id = U.user_id";
        
        $this->create_conn();
        $result = $this->conn->query($query);
        while ($row = $result->fetch_assoc()) {
            $startTime = $row['task_start_time'] ? date('m/d/Y H:i', strtotime($row['task_start_time'])) : '';
            $stopTime = $row['task_stop_time'] ? date('m/d/Y H:i', strtotime($row['task_stop_time'])) : '';
            
            fputcsv($output, [
                $row['user_firstname'],
                $row['task_id'],
                $row['task_name'],
                $startTime,
                $stopTime,
                $row['task_description'],
                $row['task_notes']
            ]);
        }
        
        fclose($output);
        exit;
    }
}
