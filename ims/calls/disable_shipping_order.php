<?php
//ini_set("display_errors", true);
require_once('../includes/settings.php');
$success = null;
$error = null;
$data = null;

if(!empty($_POST)) {
  $order_id = $_POST['order_id'];

  if($order_id == "") {
    $error = 'bad data';
  } else {
    $shipPrepare = $conn->prepare("UPDATE ship_master SET disabled = 1 WHERE shipment_id = :shipment_id");

    if($shipPrepare) {
      $success = "success";

      $shipPrepare->execute(array(":shipment_id" => $order_id));

    } else {
      $error = 'receivePrepare';
    }
  }
} else {
  $error = "No data posted";
}

ob_get_clean();
echo json_encode(array("success" => $success, "errors" => $error));
