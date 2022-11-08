<html>
<?php
  ini_set("display_errors", true);
  require_once('../includes/header.php');
?>
<title>Picking</title>
  <div class="content">
    <div class="mainPage2">
      <div>
        <h1 class="centered">Shipping Orders</h1>
      </div>
      <?php
      $selectPrepare = $conn->prepare("SELECT
        sm.shipment_id,
        sm.reference_number,
        ss.status_name AS status,
        COALESCE((SELECT SUM(qty) FROM ship_details WHERE master_id = sm.shipment_id),0) AS total_items,
        sm.ship_date
        FROM ship_master sm
        LEFT JOIN shipping_statuses ss ON sm.status = ss.status_id
        WHERE sm.disabled = 0
        AND ss.status_name LIKE '%Available%'");
      $selectPrepare->execute();

      $headers = array(
        "ID" => "shipment_id",
        "Reference Number" => "reference_number",
        "Status" => "status",
        "Total Items" => "total_items",
        "Ship Date" => "ship_date"
      );

      $buttons = array(
        "View" => "view_shipping_master.php?id=**shipment_id**"
      );

      displayGrid($selectPrepare, $headers, $buttons);
      ?>
    </div>
  </div>
</html>
