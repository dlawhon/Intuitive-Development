<?php
require_once('../includes/settings.php');
$success = null;
$error = null;
$data = null;

if(!empty($_POST)) {
  $alert_id = $_POST['alert_id'];
  $username = $_POST['username'];
  $first_name = $_POST['first_name'];
  $last_name = $_POST['last_name'];
  $role = $_POST['role'];
  $email = $_POST['email'];
  $change_password = $_POST['change_password'];

  if($alert_id == "") {
    $error = 'bad data';
  } else {

    if($change_password && $change_password != '' && $change_password != ' ') {
      setPassword($alert_id, $change_password);

      $headers = "MIME-Version: 1.0" . "\r\n";
      $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

      $to = $email;
      $subject = "Your password has been reset";
      $message = "Dear " . $first_name . " " . $last_name . ",<br><br> Your Intuitive IMS account password has been reset, please contact your system administrator for more information.";

      mail($to,$subject,$message,$headers);
    }

    $alertPrepare = $conn->prepare("UPDATE users SET
      username = :username,
      first_name = :first_name,
      last_name = :last_name,
      role = :role,
      email = :email
      WHERE alert_id = :alert_id");

    if($alertPrepare) {
      $success = "success";

      $alertPrepare->execute(array(
        ":username" => $username,
        ":first_name" => $first_name,
        ":last_name" => $last_name,
        ":role" => $role,
        ":email" => $email,
        ":alert_id" => $alert_id));

    } else {
      $error = 'userPrepare';
    }
  }
} else {
  $error = "No data posted";
}

ob_get_clean();
echo json_encode(array("success" => $success, "errors" => $error));
