<?php
    session_start();
    if (!isset($_SESSION['user_id']) && basename($_SERVER['PHP_SELF']) != 'views\auth\login.php' && basename($_SERVER['PHP_SELF']) != 'views\auth\register.php') {
        header('location: views\auth\login.php');
        exit();
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
