<?php
//ini_set("display_errors", true);
require_once('../includes/settings.php');
$success = null;
$error = null;
$data = null;

if(!empty($_POST)) {
  $type = $_POST['type'];
  $item_id = $_POST['item_id'];
  $headers = $_POST['headers'];

  if($type == 'receiving') {
    $receivePrepare = $conn->prepare("SELECT
      rm.receiving_id,
      rm.reference_number,
      rm.receive_date,
      rs.status_name AS status,
      rm.creation_date,
      SUM(rd.qty) AS received_qty
      FROM receiving_master rm
      LEFT JOIN receiving_details rd ON rd.master_id = rm.receiving_id
      LEFT JOIN receiving_statuses rs ON rs.status_id = rm.status
      WHERE rd.item_id = :item_id
      GROUP BY rm.receiving_id, rd.item_id");

    if($receivePrepare) {
      $success = "success";

      $receivePrepare->execute(array(":item_id" => $item_id));

      $data = $receivePrepare->fetchAll();
    } else {
      $error = 'receivePrepare';
    }

  } elseif($type == 'picking') {
    $pickingPrepare = $conn->prepare("SELECT
      COALESCE(SUM(p.qty),0) AS picking_qty,
      sm.shipment_id,
      sm.reference_number,
      sm.creation_date,
      sm.ship_date,
      ss.status_name AS status
      FROM ship_master sm
      LEFT JOIN ship_details sd ON sd.master_id = sm.shipment_id
      LEFT JOIN picking p ON p.master_id = sm.shipment_id
      LEFT JOIN shipping_statuses ss ON ss.status_id = sm.status
      WHERE p.item_id = :item_id
      GROUP BY sm.shipment_id, p.item_id");
    if($pickingPrepare) {
      $success = "success";

      $pickingPrepare->execute(array(":item_id" => $item_id));

      $data = $pickingPrepare->fetchAll();
    } else {
      $error = 'pickingPrepare';
    }

  } elseif($type == 'adjustments') {
    $adjustmentPrepare = $conn->prepare("SELECT
      COALESCE(SUM(a.qty),0) AS adjustment_qty,
      a.adjustment_id,
      a.creation_date
      FROM adjustments a
      WHERE a.item_id = :item_id
      GROUP BY a.adjustment_id, a.item_id");
    if($adjustmentPrepare) {
      $success = "success";

      $adjustmentPrepare->execute(array(":item_id" => $item_id));

      $data = $adjustmentPrepare->fetchAll();
    } else {
      $error = 'adjustmentPrepare';
    }

  }

} else {
  $error = "No data posted";
}

ob_get_clean();
echo json_encode(array("success" => $success, "errors" => $error, "data" => displayGrid(null,$headers,null,null,$data)));
