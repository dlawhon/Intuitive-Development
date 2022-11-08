<html>
<?php
//ini_set("display_errors", true);
  require_once('../includes/header.php');

  //Get the order info
  $itemPrepare = $conn->prepare("SELECT * FROM `on_hand_qty` ohq LEFT JOIN item_master im ON im.item_id = ohq.item_id WHERE ohq.item_id = :item_id");
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
        <input class="formType" type="hidden" name="receivingForm" id="receivingForm" value="receiving"></input>
        <i class="fas fa-expand-alt expandRight"></i><br>
        <h3 class="centered">Receiving</h3>
        <p class="centered"><b>Total: <?=$item->received_qty?></b></p>
        <div class="hiddenDiv centered hidden">
          <?php
          //Get the receiving orders for this item
          $receivingPrepare = $conn->prepare("SELECT
            COALESCE(SUM(rd.qty),0) AS received_qty,
            rm.reference_number
            FROM receiving_details rd
            LEFT JOIN receiving_master rm ON rm.receiving_id = rd.master_id
            LEFT JOIN receiving_statuses rs ON rs.status_id = rm.status
            WHERE rd.item_id = :item_id
            AND rm.disabled = 0
            AND rs.status_name NOT LIKE '%Void%'
            GROUP BY rd.master_id, rd.item_id
            ORDER BY rm.creation_date DESC
            LIMIT 10");
          $receivingPrepare->execute(array(":item_id" => $_GET['id'])); ?>
          <table class='table smallerText'>
            <th>Receiving Order</th><th>Received Qty</th>
          <?php
          while($receiving = $receivingPrepare->fetch(PDO::FETCH_OBJ)) { ?>
            <tr>
              <td><a href="view_inventory_details.php?id=<?=$_GET['id']?>&type=receiving"><?=$receiving->reference_number?></a></td>
              <td><?=$receiving->received_qty?></td>
            </tr>
    <?php } ?>
        </table>
        </div>
        <div class="hiddenDiv2 centered hidden">
        </div>
        <div class="centered downArrow"><i class="fas fa-chevron-down"></i></div>
        <div class="centered upArrow hidden"><i class="fas fa-chevron-up"></i></div>
      </div>
      <div class="subformContent" style="background: #f5ca7d;">
        <input class="formType" type="hidden" name="pickingForm" id="pickingForm" value="picking"></input>
        <i class="fas fa-expand-alt expandRight"></i><br>
        <h3 class="centered">Picked</h3>
        <p class="centered"><b>Total: <?=$item->picked_qty?></b></p>
        <div class="hiddenDiv centered hidden">
          <?php
          //Get the receiving orders for this item
          $shippingPrepare = $conn->prepare("SELECT
            COALESCE(SUM(p.qty),0) AS picked_qty,
            sm.reference_number
            FROM picking p
            LEFT JOIN ship_master sm ON sm.shipment_id = p.master_id
            LEFT JOIN shipping_statuses ss ON ss.status_id = sm.status
            WHERE p.item_id = :item_id
            AND p.disabled = 0
            AND ss.status_name NOT LIKE '%Void%'
            GROUP BY p.master_id, p.item_id
            ORDER BY sm.creation_date DESC
            LIMIT 10");
          $shippingPrepare->execute(array(":item_id" => $_GET['id'])); ?>
          <table class='table smallerText'>
            <th>Shipment Reference Number</th><th>Picked Qty</th>
          <?php
          while($shipping = $shippingPrepare->fetch(PDO::FETCH_OBJ)) { ?>
            <tr>
              <td><a href="view_inventory_details.php?id=<?=$_GET['id']?>&type=shipping"><?=$shipping->reference_number?></a></td>
              <td><?=$shipping->picked_qty?></td>
            </tr>
    <?php } ?>
        </table>
        </div>
        <div class="hiddenDiv2 centered hidden">
        </div>
        <div class="centered downArrow"><i class="fas fa-chevron-down"></i></div>
        <div class="centered upArrow hidden"><i class="fas fa-chevron-up"></i></div>
      </div>
      <div class="subformContent" style="background: #e57070;">
        <input class="formType" type="hidden" name="adjustmentForm" id="adjustmentForm" value="adjustments"></input>
        <i class="fas fa-expand-alt expandRight"></i><br>
        <h3 class="centered">Adjustments</h3>
        <p class="centered"><b>Total: <?=$item->adjustment_qty?></b></p>
        <div class="hiddenDiv centered hidden">
          <?php
          //Get the receiving orders for this item
          $adjustmentPrepare = $conn->prepare("SELECT
            COALESCE(SUM(a.qty),0) AS adjustment_qty,
            a.adjustment_id
            FROM adjustments a
            WHERE a.item_id = :item_id
            GROUP BY a.adjustment_id, a.item_id
            ORDER BY a.creation_date DESC
            LIMIT 10");
          $adjustmentPrepare->execute(array(":item_id" => $_GET['id'])); ?>
          <table class='table smallerText'>
            <th>Adjusment ID</th><th>Adjusted Qty</th>
          <?php
          while($adjustments = $adjustmentPrepare->fetch(PDO::FETCH_OBJ)) { ?>
            <tr>
              <td><a href="view_inventory_details.php?id=<?=$_GET['id']?>&type=adjustments"><?=$adjustments->adjustment_id?></a></td>
              <td><?=$adjustments->adjustment_qty?></td>
            </tr>
    <?php } ?>
        </table>
        </div>
        <div class="hiddenDiv2 centered hidden">
        </div>
        <div class="centered downArrow"><i class="fas fa-chevron-down"></i></div>
        <div class="centered upArrow hidden"><i class="fas fa-chevron-up"></i></div>
      </div>
      <div class="subformContent" style="background: #94c8fd;">
        <i class="fas fa-expand-alt expandRight"></i><br>
        <h3 class="centered">On Hand Qty</h3>
        <p class="centered"><b>Total: <?=$item->on_hand_qty?></b></p>
        <div class="hiddenDiv centered hidden">
          <p><?=$item->received_qty . " - " . $item->picked_qty . " + " . $item->adjustment_qty . " = " . $item->on_hand_qty?></p>
        </div>
        <div class="centered downArrow"><i class="fas fa-chevron-down"></i></div>
        <div class="centered upArrow hidden"><i class="fas fa-chevron-up"></i></div>
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

    $(".expandRight").click(function(){
      if($(this).parent().parent().css("display") == 'flex') {
        let headersArray = [];
        let element = $(this);
        let type = element.parent().find('.formType').val();
        element.parent().css({"width" : "100%", "margin-bottom" : "50px"});
        element.parent().parent().css({"display" : "block"});

        if(type == 'receiving') {
          headersArray = {
            "Order ID": "receiving_id",
            "Reference Number": "reference_number",
            "Received QTY" : "received_qty",
            "Receive Date": "receive_date",
            "Status": "status",
            "Creation Date": "creation_date"
          };
        } else if(type == 'picking') {
          headersArray = {
            "Shipment ID": "shipment_id",
            "Reference Number": "reference_number",
            "Picked QTY" : "picking_qty",
            "Ship Date": "ship_date",
            "Status": "status",
            "Creation Date": "creation_date"
          };
        } else {
          headersArray = {
            "Adjustment ID": "adjustment_id",
            "Adjustment QTY": "adjustment_qty",
            "Creation Date": "creation_date"
          };
        }

        $.ajax({
            url: "../calls/inventory_details_call.php",
            type: "POST",
            data: { item_id: <?=$_GET['id']?>, type: type, headers: headersArray },
        success: function(data)
        {
          let returnData = JSON.parse(data);

          if(returnData["errors"] == null) {

            let html = returnData['data'];

            element.parent().find('.hiddenDiv').css({"display" : "none"});
            element.parent().find('.hiddenDiv2').css({"display" : "block"});
            element.parent().find('.hiddenDiv2').html(html);
            element.parent().find('.downArrow').css({"display" : "none"});
            element.parent().find('.upArrow').css({"display" : "none"});
          } else {
            console.log('ajax error ' + returnData["errors"]);
          }

        }
        });

      } else {
        $(this).parent().css({"width" : "350px", "margin" : "auto"});
        $(this).parent().find('.hiddenDiv').css({"display" : "none"});
        $(this).parent().find('.hiddenDiv2').css({"display" : "none"});
        $(this).parent().parent().css({"display" : "flex"});
        $(this).parent().find('.downArrow').css({"display" : "block"});
      }
    });

  });
</script>
