<?php
//ini_set("display_errors", true);
require_once('../includes/settings.php');
$success = null;
$error = null;
$data = null;

if(!empty($_REQUEST)) {
  $alert_id = $_REQUEST['alert_id'];

  if($alert_id == "") {
    $error = 'bad data';
  } else {
    $alertPrepare = $conn->prepare("SELECT * FROM email_alerts WHERE alert_id = :alert_id");

    if($alertPrepare) {

      $alertPrepare->execute(array(":alert_id" => $alert_id));

      $data = $alertPrepare->fetch(PDO::FETCH_OBJ);

      $header = "From: noreply@intuitivedevelopment.io" . "\r\n";
      $headers .= "MIME-Version: 1.0" . "\r\n";
      $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

      $to = $data->address;

      $subject = "Intuitive Email";
      $message = "This is an Email Alert test for the alert " . $data->alert_description . ".<br><br>Thank you from Intuitive Development.";


      if(mail($to,$subject,$message,$headers)) {
        $success = "success";
      } else {
        $error = 'emailFailed';
      }

    } else {
      $error = 'alertPrepare';
    }
  }
} else {
  $error = "No data posted";
}

ob_get_clean();

header("Location: ../administration/alerts.php");
//echo json_encode(array("success" => $success, "errors" => $error));
