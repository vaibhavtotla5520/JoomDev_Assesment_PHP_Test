<?php
require_once __DIR__ . '/../Models/AdminsModel.php';

class AdminsController extends AdminModel
{

    public function __construct()
    {
        require_once __DIR__ . '/../CommonFunctions/Functions.php';
    }

    public function resetPassword() {
        if (empty($_POST)) {
            redirect('reset_password.php', 'Values Can Not Be Empty');
            return;
        }

        if (empty($_POST['password'])) {
            redirect('reset_password.php', 'Password Can Not Be Empty');
        } else if (empty($_POST['cnf_password'])) {
            redirect('reset_password.php', 'Confirm Password Can Not Be Empty');
        }else if (empty($_POST['user_id'])) {
            redirect('reset_password.php', 'User ID Can Not Be Empty');
        } else {
            $password = $_POST['password'];
            $cnf_password = $_POST['cnf_password'];
            $user_id = $_POST['user_id'];
        }
        if ($cnf_password !== $password) {
            echo json_encode(['error' => 'Password Doesnt Match, Try Again']);
            return;
        }
        $resetPassword = $this->reset_password($password, $user_id);
        // var_dump($resetPassword);
        if ($resetPassword !== 0) {
            $_SESSION['User']['last_password_change_days'] = $resetPassword['last_password_change_days']+1;
            // print_r($_SESSION);
            // die;
            echo json_encode(['success' => 1, 'redirect' => 'index.php']);
        } else {
            echo json_encode(['error' => 'Unable To Reset Password, Try Again Later']);
        }
        return;
    }

    public function logout() {
        session_destroy();
        redirect('login.php', 'Logged Out');
    }
    public function admin_login()
    {
        if (empty($_POST)) {
            redirect('login.php', 'Values Can Not Be Empty');
            return;
        }

        if (empty($_POST['email'])) {
            redirect('login.php', 'Email Can Not Be Empty');
        } else if (empty($_POST['password'])) {
            redirect('login.php', 'Password Can Not Be Empty');
        } else {
            $email = $_POST['email'];
            $password = $_POST['password'];
        }
        $login = $this->Authenticate($email, $password);
        if ($login !== 0) {
            $last_password_change_days = 0;
            if (!empty($login['user_last_password_change_timestamp'])) {
                $last_password_change_days = $login['last_password_change_days'];
            } else {
                $last_password_change_days = "NEW";
            }
            $_SESSION['User'] = [
                'id' => $login['user_id'],
                'name' => $login['user_firstname'],
                'roles' => $login['user_role'],
                'last_password_change_days' => $last_password_change_days
            ];
            echo json_encode(['success' => 1, 'redirect' => 'index.php']);
        } else {
            echo json_encode(['error' => 'Unable To Login, Try Again Later']);
        }
        return;
    }

}
