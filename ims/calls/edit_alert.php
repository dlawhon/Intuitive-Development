<?php
require_once('../includes/settings.php');
$success = null;
$error = null;
$data = null;

if(!empty($_POST)) {
  $alert_id = $_POST['alert_id'];
  $alert_description = $_POST['alert_description'];
  $address = $_POST['address'];
  $schedule = $_POST['schedule'];

  if($alert_id == "") {
    $error = 'bad data';
  } else {

    $alertPrepare = $conn->prepare("UPDATE email_alerts SET
      alert_description = :alert_description,
      address = :address,
      alert_schedule = :schedule
      WHERE alert_id = :alert_id");

    if($alertPrepare) {
      $success = "success";

      $alertPrepare->execute(array(
        ":alert_description" => $alert_description,
        ":address" => $address,
        ":schedule" => $schedule,
        ":alert_id" => $alert_id));

    } else {
      $error = 'alertPrepare';
    }
  }
} else {
  $error = "No data posted";
}

ob_get_clean();
echo json_encode(array("success" => $success, "errors" => $error));
