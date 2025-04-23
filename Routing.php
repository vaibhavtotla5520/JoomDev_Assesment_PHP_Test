<?php
session_start();
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

if (isset($_GET['debug']) && $_GET['debug'] == 1) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}
// print_r($_FILES);
require "CommonFunctions/Functions.php";

$route = isset($_POST["route"]) && !empty($_POST["route"]) ? $_POST["route"] : "";
if (empty($route)) {
    $route = isset($_GET["route"]) && !empty($_GET["route"]) ? $_GET["route"] : "";
}
$route = explode("-", $route);
if (count($route) < 2) {
    return 0;
}

if (in_array("db", $route)) {
    require_once "CommonFunctions/db_config.php";
    $db = new db;
} else if (in_array("admins", $route)) {
    require_once "Controllers/AdminsController.php";
    $Admins = new AdminsController;
} else if (in_array("users", $route)) {
    require_once "Controllers/UserController.php";
    $User = new UserController;
}
$route = implode("-", $route);

if (!empty($route)) {
    switch ($route) {
        case "admins-login":
            $Admins->admin_login();
            break;
        case "admins-resetPassword":
            $Admins->resetPassword();
            break;
        case "admins-logout":
            $Admins->logout();
        case "users-list":
            $User->usersList();
            break;
        case "users-add":
            $User->usersAdd();
            break;
        case "users-taskList":
            $User->tasksList();
            break;
        case "users-addTask":
            $User->tasksAdd();
            break;
        case "users-getTask":
            $User->getTask();
            break;
        case "users-updateTask":
            $User->updateTask();
            break;
        case "users-downloanTaskReportCSV":
            $User->downloanTaskReportCSV();
            break;
        case "db-create_schema": //Only To Run DB Migration URL:'http://localhost/Routing.php?route=db-create_schema'
            $db->create_schema();
            break;
        default:
            echo json_encode(['error' => 'Route Not Defined']);
    }
} else {
    echo json_encode(['error' => 'Route is Empty']);
}
