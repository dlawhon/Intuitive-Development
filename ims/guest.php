<?php

session_start();
$_SESSION['login'] = 'true';
$_SESSION['user_id'] = 13;
$_SESSION['user'] = "Guest";
$_SESSION['role'] = 1;
$_SESSION['role_name'] = "admin";

session_write_close();

header("Location: dashboard.php");
exit();
