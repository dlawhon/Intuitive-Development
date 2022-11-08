<?php
//ini_set("display_errors", true);
require_once('../includes/settings.php');
$success = null;
$error = null;
$data = null;

$today = date('w');

if($today > 1) {

  for($i = 1; $i <= 7; $i++) {

    if($i <= $today) {

      $dayDifference = $today - $i;

      $dayDifference = "-".$dayDifference." days";

      $date = date('Y-m-d', strtotime($dayDifference));
      echo $date .PHP_EOL;

      $shipmentPrepare = $conn->prepare("SELECT
        COUNT(sm.shipment_id) AS shipment_count
        FROM ship_master sm
        LEFT JOIN shipping_statuses ss ON ss.status_id = sm.status
        WHERE sm.ship_date = :ship_date
        AND ss.status_name = 'Shipped'");

      if($shipmentPrepare) {
        $success = "success";

        $shipmentPrepare->execute(array(":ship_date" => $date));

        $shipmentData = $shipmentPrepare->fetch();
        $data[] = (int)$shipmentData['shipment_count'];

      } else {
        $error = 'shipmentPrepare';
        $data[] = 0;
      }

    } else {
      $data[] = 0;
    }

  }

} else {
  $data = array("");
}

//For random data
for($i = 0; $i < 8; $i++) {
  $data[$i] = rand(10,100);
}

ob_get_clean();
echo json_encode(array("success" => $success, "errors" => $error, "data" => $data));
