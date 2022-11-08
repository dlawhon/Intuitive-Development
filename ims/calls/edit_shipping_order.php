<?php
require_once('../includes/settings.php');
$success = null;
$error = null;
$data = null;

if(!empty($_POST)) {
  $order_id = $_POST['order_id'];
  $reference_number = $_POST['reference_number'];
  $ship_date = $_POST['ship_date'];
  $status = $_POST['status'];

  if($reference_number == "" || $order_id == "") {
    $error = 'bad data';
  } else {

    $shippingPrepare = $conn->prepare("UPDATE ship_master SET
      reference_number = :reference_number,
      ship_date = :ship_date,
      status = :status
      WHERE shipment_id = :shipment_id");

    if($shippingPrepare) {
      $success = "success";

      $shippingPrepare->execute(array(
        ":reference_number" => $reference_number,
        ":ship_date" => date("Y-m-d", strtotime($ship_date)),
        ":status" =>$status,
        ":shipment_id" => $order_id));

    } else {
      $error = 'shippingPrepare';
    }
  }
} else {
  $error = "No data posted";
}

ob_get_clean();
echo json_encode(array("success" => $success, "errors" => $error));
