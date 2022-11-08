<?php
//ini_set("display_errors", true);
require_once('../includes/settings.php');
$success = null;
$error = null;
$data = null;

if(!empty($_POST)) {
  $reference_number = $_POST['reference_number'];
  $ship_date = $_POST['ship_date'];
  $status = $_POST['status'];

  if($reference_number == "" || $ship_date == "" || $status == "") {
    $error = 'bad data';
  } else {
    $receivePrepare = $conn->prepare("INSERT INTO ship_master
      (
        reference_number,
        ship_date,
        status
      )
      VALUES
      (
        :reference_number,
        :ship_date,
        :status
      )");

    if($receivePrepare) {
      $success = "success";

      $receivePrepare->execute(array(":reference_number" => $reference_number, ":ship_date" => $ship_date, ":status" => $status));

      $data = $receivePrepare->fetchAll();
    } else {
      $error = 'receivePrepare';
    }
  }
} else {
  $error = "No data posted";
}

ob_get_clean();
echo json_encode(array("success" => $success, "errors" => $error));
