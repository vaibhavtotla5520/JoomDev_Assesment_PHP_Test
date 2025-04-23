<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


function redirect($file_name, $alert_message)
{
  if (!headers_sent()) {
    header("Location: " . htmlspecialchars( $file_name . "?msg=" . $alert_message, ENT_QUOTES, 'UTF-8'));
    exit();
  }
}
