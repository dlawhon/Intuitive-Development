<?php
//ini_set("display_errors", true);
require_once('../includes/settings.php');
$success = null;
$error = null;
$data = null;

if(!empty($_POST)) {
  $alert_id = $_POST['alert_id'];

  if($alert_id == "") {
    $error = 'bad data';
  } else {
    $alertPrepare = $conn->prepare("SELECT * FROM email_alerts WHERE alert_id = :alert_id");

    if($alertPrepare) {
      $success = "success";

      $alertPrepare->execute(array(":alert_id" => $alert_id));

      $data = $alertPrepare->fetchAll();
    } else {
      $error = 'alertPrepare';
    }
  }
} else {
  $error = "No data posted";
}

ob_get_clean();
echo json_encode(array("success" => $success, "errors" => $error, "data" => $data));
