<html>
<?php
  require_once('../includes/header.php');
?>
<title>Inventory</title>
  <div class="content">
    <div class="mainPage2">
      <div>
        <h1 class="centered">Inventory</h1>
      </div>
      <?php
        $selectPrepare = $conn->prepare("SELECT
          im.*,
          ohq.received_qty AS received_qty,
          ohq.picked_qty AS picked_qty,
          ohq.adjustment_qty AS adjustment_qty,
          ohq.on_hand_qty AS on_hand_qty
          FROM item_master im
          LEFT JOIN on_hand_qty ohq ON ohq.item_id = im.item_id
          WHERE im.disabled = 0");
        $selectPrepare->execute();

        $headers = array(
          "Item ID" => "item_id",
          "Item Name" => "item_name",
          "Recieved Qty" => "received_qty",
          "Picked Qty" => "picked_qty",
          "Adjustment Qty" => "adjustment_qty",
          "On Hand Qty" => "on_hand_qty"
        );

        $buttons = array(
          "View" => "view_inventory.php?id=**item_id**"
        );

        displayGrid($selectPrepare, $headers, $buttons);
      ?>
    </div>
  </div>
</html>
