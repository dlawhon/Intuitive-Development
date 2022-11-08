<html>
<?php
  //ini_set("display_errors", true);
  require_once('../includes/header.php');
?>
<title>EDI</title>
  <div class="content">
    <div class="mainPage2">
      <div>
        <h1 class="centered">EDI</h1>
      </div>
      <?php
        // $selectPrepare = $conn->prepare("SELECT
        //   im.*,
        //   COALESCE((SELECT SUM(qty) FROM receiving_details WHERE item_id = im.item_id),0) AS received_qty,
        //   '?' AS picked_qty,
        //   '?' AS on_hand_qty
        //   FROM item_master im
        //   WHERE im.disabled = 0");
        // $selectPrepare->execute();
        //
        // $headers = array(
        //   "Item ID" => "item_id",
        //   "Item Name" => "item_name",
        //   "Recieved Qty" => "received_qty",
        //   "Picked Qty" => "picked_qty",
        //   "On Hand Qty" => "on_hand_qty"
        // );
        //
        // $buttons = array(
        //   "View" => "view_inventory.php?id=**item_id**"
        // );
        //
        // displayGrid($selectPrepare, $headers, $buttons);
      ?>
      <p style="text-align: center;">
        This page is a work in progress
      </p>
    </div>
  </div>
</html>
