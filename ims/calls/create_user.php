<?php
//ini_set("display_errors", true);
require_once('../includes/settings.php');
$success = null;
$error = null;
$data = null;

if(!empty($_POST)) {
  $username = trim($_POST['username']);
  $first_name = trim($_POST['first_name']);
  $last_name = trim($_POST['last_name']);
  $role = trim($_POST['role']);
  $email = trim($_POST['email']);
  $pasword = $_POST['password'];

  if($username == "" || $email == "") {
    $error = 'bad data';
  } else {

    $userPrepare = $conn->prepare("INSERT INTO users
      (
        username,
        first_name,
        last_name,
        role,
        email
      )
      VALUES
      (
        :username,
        :first_name,
        :last_name,
        :role,
        :email
      )
      ");

    if($userPrepare) {
      $success = "success";

      $userPrepare->execute(array(
        ":username" => $username,
        ":first_name" => $first_name,
        ":last_name" => $last_name,
        ":role" => $role,
        ":email" => $email));

      $user_id = $conn->lastInsertId();

      setPassword($user_id, $pasword);

      $headers = "MIME-Version: 1.0" . "\r\n";
      $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

      $to = $email;
      $subject = "Welcome to Intuitive";
      $message = "Dear " . $first_name . " " . $last_name . ",<br><br> Your account has been successfully created, enjoy your time spent with Intuitive Development.";

      mail($to,$subject,$message,$headers);

    } else {
      $error = 'userPrepare';
    }
  }
} else {
  $error = "No data posted";
}

ob_get_clean();
echo json_encode(array("success" => $success, "errors" => $error));
