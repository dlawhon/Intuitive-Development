<?php
//ini_set("display_errors", true);
require_once('../includes/settings.php');
$success = null;
$error = null;
$data = null;

if(!empty($_POST)) {
  $alert_description = trim($_POST['alert_description']);
  $address = trim($_POST['address']);
  $alert_schedule = trim($_POST['schedule']);

  if($alert_description == "" || $address == "" || $alert_schedule == "") {
    $error = 'bad data';
  } else {

    $alertPrepare = $conn->prepare("INSERT INTO email_alerts
      (
        alert_description,
        address,
        alert_schedule
      )
      VALUES
      (
        :alert_description,
        :address,
        :alert_schedule
      )
      ");

    if($alertPrepare) {
      $success = "success";

      $alertPrepare->execute(array(
        ":alert_description" => $alert_description,
        ":address" => $address,
        ":alert_schedule" => $alert_schedule));

      $alert_id = $conn->lastInsertId();

    } else {
      $error = 'alertPrepare';
    }
  }
} else {
  $error = "No data posted";
}

ob_get_clean();
echo json_encode(array("success" => $success, "errors" => $error));
