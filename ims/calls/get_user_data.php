<?php
require_once('../includes/settings.php');
$success = null;
$error = null;
$data = null;

if(!empty($_POST)) {
  $user_id = $_POST['user_id'];

  if($user_id == "") {
    $error = 'bad data';
  } else {
    $userPrepare = $conn->prepare("SELECT * FROM users WHERE user_id = :user_id");

    if($userPrepare) {
      $success = "success";

      $userPrepare->execute(array(":user_id" => $user_id));

      $data = $userPrepare->fetchAll();
    } else {
      $error = 'userPrepare';
    }
  }
} else {
  $error = "No data posted";
}

ob_get_clean();
echo json_encode(array("success" => $success, "errors" => $error, "data" => $data));
