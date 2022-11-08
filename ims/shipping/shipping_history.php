<html>
<?php
  //ini_set("display_errors", true);
  require_once('../includes/header.php');
?>
<title>Shipping</title>
  <div class="content">
    <div class="mainPage2">
      <div>
        <h1 class="centered">Shipping Orders History</h1>
      </div>
      <div class="pageControls">
        <a href="index.php"><div class="btn btn-success" id="viewHistory">View Active Orders</div></a>
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
        AND ss.status_name = 'Shipped'");
      $selectPrepare->execute();

      $headers = array(
        "ID" => "shipment_id",
        "Reference Number" => "reference_number",
        "Status" => "status",
        "Total Items" => "total_items",
        "Ship Date" => "ship_date"
      );

      $leftButtons = array(
        "View" => "",
      );

      displayGrid($selectPrepare, $headers, $leftButtons, null, null);
      ?>
    </div>
  </div>
  <div class="viewTemplate" style="display: none; height: 800px; margin-top: 10px;">
    <div class="formContentView" style="width: 400px;">
      <div class="formDiv">
        <input type="hidden" id="master_order_id" name="master_order_id" value="" disabled>
        <label for="reference_number">Reference Number:</label>
        <input type="text" id="reference_number" name="reference_number" value="" disabled><br><br>
        <label for="ship_date">Ship Date:</label>
        <input type="text" id="ship_date" name="ship_date" value="" disabled><br><br>
        <label for="status">Order Status:</label>
        <select name="status" id="status" disabled>
        <?php
          //Get the order statuses
          $statusPrepare = $conn->prepare("SELECT
            ss.*
            FROM shipping_statuses ss
            WHERE ss.disabled = 0");
          $statusPrepare->execute();

          while ($row = $statusPrepare->fetch())
          {
            ?>
            <option value="<?=$row['status_id']?>"><?=$row['status_name']?></option>
    <?php }
        ?>
        </select>
        <label for="creation_date">Creation Date:</label>
        <input type="text" id="creation_date" name="creation_date" value="" disabled><br><br>
      </div>
    </div>
    <div><br>
      <div>
        <iframe src="" title="map" id="mapiframe" style="height: 100%; width: 100%"></iframe>
      </div>
    </div>
  </div>
</html>
<script>
$('.leftGridButton').click(function() {

       var template = $('.viewTemplate:hidden').clone().css({"display" : "block"}),
           order_id = $(this).parent().parent().attr("data-rowId");
           template.find('#status_chosen').remove();
           template.find('select').css({"display" : "block"});
           template.find('select').chosen();
           template.find('.chosen-container-single:first').css({"width" : "220px"});
           template.find('select').chosen({width: "100%"});

           $.ajax({
            url: "../calls/get_shipping_order.php",
            type: "POST",
            data: {
              "order_id" : order_id
            },
            success: function(data){

              let returnData = JSON.parse(data);

              if(returnData["errors"] == null) {

                mapURL = '../api/open_layers/map.php?ship_to_x=' + returnData["data"][0]["ship_to_coordinates"].latitude + '&ship_to_y=' + returnData["data"][0]["ship_to_coordinates"].longitude
                  + '&ship_from_x=' + returnData["data"][0]["ship_from_coordinates"].latitude + '&ship_from_y=' + returnData["data"][0]["ship_from_coordinates"].longitude;
                template.find('#master_order_id').val(order_id);
                template.find('#reference_number').val(returnData["data"][0]["reference_number"]);
                template.find('#ship_date').val(returnData["data"][0]["ship_date"]);
                template.find('#creation_date').val(returnData["data"][0]["creation_date"]);
                template.find('#status').val(returnData["data"][0]["status"]);
                template.find('#status').trigger("chosen:updated");
                template.find('#mapiframe').attr('src', mapURL);

              } else {
                console.log(returnData["errors"]);
              }

            }

          });

       $.confirm({
            title: "Edit Order",
            content: template,
            columnClass: 'medium',
            backgroundDismiss: true,
            type: 'blue',
            smoothContent: true,
            columnClass: 'col-md-12',
            buttons: {
              close: function () {

              }
            }
        });
   });
</script>
