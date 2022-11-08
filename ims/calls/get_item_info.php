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
    $receivePrepare = $conn->prepare("SELECT * FROM receiving_master WHERE receiving_id = :receiving_id");

    if($receivePrepare) {
      $success = "success";

      $receivePrepare->execute(array(":receiving_id" => $order_id));

      $data = $receivePrepare->fetchAll();
    } else {
      $error = 'receivePrepare';
    }
  }
} else {
  $error = "No data posted";
}

ob_get_clean();
echo json_encode(array("success" => $success, "errors" => $error, "data" => $data));
