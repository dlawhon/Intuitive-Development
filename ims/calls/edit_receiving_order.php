<?php
//ini_set("display_errors", true);
require_once('../includes/settings.php');
$success = null;
$error = null;
$data = null;

if(!empty($_POST)) {
  $order_id = $_POST['order_id'];
  $reference_number = $_POST['reference_number'];
  $receive_date = $_POST['receive_date'];
  $status = $_POST['status'];

  if($reference_number == "" || $order_id == "") {
    $error = 'bad data';
  } else {

    $receivePrepare = $conn->prepare("UPDATE receiving_master SET
      reference_number = :reference_number,
      receive_date = :receive_date,
      status = :status
      WHERE receiving_id = :receiving_id");

    if($receivePrepare) {
      $success = "success";

      $receivePrepare->execute(array(
        ":reference_number" => $reference_number,
        ":receive_date" => date("Y-m-d", strtotime($receive_date)),
        ":status" => $status,
        ":receiving_id" => $order_id));

    } else {
      $error = 'receivePrepare';
    }
  }
} else {
  $error = "No data posted";
}

ob_get_clean();
echo json_encode(array("success" => $success, "errors" => $error));
