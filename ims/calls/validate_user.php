<?php
//ini_set("display_errors", true);
require_once('../includes/settings.php');
$success = null;
$error = null;
$data = null;

if(!empty($_POST)) {
  $username = trim($_POST['username']);
  $email = trim($_POST['email']);

  if($username != "") {

    $usernamePrepare = $conn->prepare("SELECT COUNT(*) AS total FROM users WHERE username = :username");

    if($usernamePrepare) {
      $usernamePrepare->execute(array(
        ":username" => $username));

      $data = $usernamePrepare->fetch(PDO::FETCH_OBJ);

        if($data->total == 0) {
          $success = "success";
        } else {
          $error = 'Duplicate Username';
        }

    } else {
      $error = 'usernamePrepare failed';
    }
  } elseif($email == "") {

    $emailPrepare = $conn->prepare("SELECT COUNT(*) AS total FROM users WHERE email = :email");

    if($emailPrepare) {
      $emailPrepare->execute(array(
        ":email" => $email));

      $data = $emailPrepare->fetch(PDO::FETCH_OBJ);

        if($data->total == 0) {
          $success = "success";
        } else {
          $error = 'Duplicate Email';
        }

    } else {
      $error = 'emailPrepare failed';
    }
 }
} else {
  $error = "No data posted";
}

ob_get_clean();
echo json_encode(array("success" => $success, "errors" => $error));
