<?php
require_once('../includes/settings.php');
$success = null;
$error = null;
$data = null;

if(!empty($_POST)) {
  $order_id = $_POST['order_id'];

  if($order_id == "") {
    $error = 'bad data';
  } else {
    $shipPrepare = $conn->prepare("SELECT
      sm.*,
      ship_to.site_address AS ship_to_address,
      ship_to.site_city AS ship_to_city,
      ship_to.site_state AS ship_to_state,
      ship_to.site_zip AS ship_to_zip,
      ship_from.site_address AS ship_from_address,
      ship_from.site_city AS ship_from_city,
      ship_from.site_state AS ship_from_state,
      ship_from.site_zip AS ship_from_zip
      FROM ship_master sm
      LEFT JOIN sites ship_to ON ship_to.site_id = sm.ship_to_site
      LEFT JOIN sites ship_from ON ship_from.site_id = sm.ship_from_site
      WHERE sm.shipment_id = :shipment_id");

    if($shipPrepare) {
      $success = "success";

      $shipPrepare->execute(array(":shipment_id" => $order_id));

      $data = $shipPrepare->fetchAll();

      $ship_to_coordinates = getCoordinates($data[0]['ship_to_address'], $data[0]['ship_to_city'], $data[0]['ship_to_state'], $data[0]['ship_to_zip']);
      $ship_from_coordinates = getCoordinates($data[0]['ship_from_address'], $data[0]['ship_from_city'], $data[0]['ship_from_state'], $data[0]['ship_from_zip']);

      $data[0]['ship_to_coordinates'] = $ship_to_coordinates;
      $data[0]['ship_from_coordinates'] = $ship_from_coordinates;
    } else {
      $error = 'shipPrepare';
    }
  }
} else {
  $error = "No data posted";
}

ob_get_clean();
echo json_encode(array("success" => $success, "errors" => $error, "data" => $data));
