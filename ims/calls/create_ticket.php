<?php
require_once('includes/settings.php');

if(!empty($_POST)) {

  $ticket_description = $_POST['ticket_description'];
  $ticket_issue_type = $_POST['ticket_issue_type'];

  $selectPrepare = $conn->prepare("INSERT INTO tickets username, hash, role FROM users WHERE username = :username");
  $selectPrepare->execute(array(":username" => $username));

  $selectResult = $selectPrepare->fetch(PDO::FETCH_OBJ);

  $hashed_password = $selectResult->hash;

  if(password_verify($password, $hashed_password)) {
    session_start();
    $_SESSION['login'] = 'true';
    $_SESSION['user'] = $selectResult->username;
    $_SESSION['role'] = $selectResult->role;

    header("Location: dashboard.php");
  } else {
    $_SESSION['login'] = 'false';
  }


}
