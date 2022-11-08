<html>
<?php
  require_once('../includes/header.php');

  //Get the order info
  $itemPrepare = $conn->prepare("SELECT
    *,
    (received_qty - shipped_qty + adjustment_qty) AS on_hand_qty
    FROM (
  SELECT
    im.*,
    COALESCE((SELECT SUM(qty) FROM receiving_details WHERE item_id = im.item_id),0) AS received_qty,
    COALESCE(('0'),0) AS shipped_qty,
    COALESCE(('0'),0) AS adjustment_qty
    FROM item_master im
    WHERE im.item_id = :item_id)tmp");
  $itemPrepare->execute(array(":item_id" => $_GET['id']));
  if($itemPrepare->rowCount() == 0)
  {
    echo 'Item Not Found';
    die();
  }
  $item = $itemPrepare->fetch(PDO::FETCH_OBJ);
?>
<title>Inventory</title>
  <div class="content">
    <div>
      <h1 class="centered" style="padding-top: 40px;">Item: <?=$item->item_name?></h1>
    </div>
    <div class="mainPage2" style="display: flex;">
      <div class="subformContent" style="background: #a4fda4;">
        <h3 class="centered">Receiving</h3>
        <p class="centered"><b>Total Qty: <?=$item->received_qty?></b></p>
        <div class="hiddenDiv centered hidden">
          <?php
          //Get the receiving orders for this item
          $receivingPrepare = $conn->prepare("SELECT
            SUM(rd.qty) AS received_qty,
            rm.reference_number
            FROM receiving_details rd
            LEFT JOIN receiving_master rm ON rm.receiving_id = rd.master_id
            LEFT JOIN receiving_statuses rs ON rs.status_id = rm.status
            WHERE rd.item_id = :item_id
            AND rm.disabled = 0
            AND rs.status_name NOT LIKE '%Void%'
            GROUP BY rd.master_id, rd.item_id");
          $receivingPrepare->execute(array(":item_id" => $_GET['id']));

          while($receiving = $receivingPrepare->fetch(PDO::FETCH_OBJ)) { ?>
            <a href=""><p class="smallerText">Order: <?=$receiving->reference_number?> Qty Received: <?=$receiving->received_qty?></p></a>
    <?php } ?>
        </div>
        <div class="centered downArrow"><i class="fas fa-chevron-down"></i></div>
        <div class="centered upArrow hidden"><i class="fas fa-chevron-up"></i></div>
      </div>
      <div class="subformContent" style="background: #f5ca7d;">
        <h3 class="centered">Shipping</h3>
        <p class="centered"><b>Total Qty: <?=$item->shipped_qty?></b></p>
        <div class="centered downArrow"><i class="fas fa-chevron-down"></i></div>
      </div>
      <div class="subformContent" style="background: #e57070;">
        <h3 class="centered">Adjustments</h3>
        <p class="centered"><b>Total Qty: <?=$item->adjustment_qty?></b></p>
        <div class="centered downArrow"><i class="fas fa-chevron-down"></i></div>
      </div>
      <div class="subformContent" style="background: #94c8fd;">
        <h3 class="centered">On Hand Qty</h3>
        <p class="centered"><b>Total Qty: <?=$item->on_hand_qty?></b></p>
        <div class="centered downArrow"><i class="fas fa-chevron-down"></i></div>
      </div>
    </div>
  </div>
</html>
<script type="text/javascript">
  $(document).ready(function(){
    $(".downArrow").click(function(){
      $(this).parent().find('.hiddenDiv').css({"display" : "block"});
      $(this).parent().find('.upArrow').css({"display" : "block"});
      $(this).css({"display" : "none"});
    });
    $(".upArrow").click(function(){
      $(this).parent().find('.hiddenDiv').css({"display" : "none"});
      $(this).parent().find('.downArrow').css({"display" : "block"});
      $(this).css({"display" : "none"});
    });
  });
</script>
