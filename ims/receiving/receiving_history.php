<html>
<?php
  require_once('../includes/header.php');
?>
<title>Receiving</title>
  <div class="content">
    <div class="mainPage2">
      <div>
        <h1 class="centered">Receiving Orders History</h1>
      </div>
      <div class="pageControls">
        <a href="index.php"><div class="btn btn-success" id="viewHistory">View Active Orders</div></a>
      </div>
      <?php
        $selectPrepare = $conn->prepare("SELECT
          r.receiving_id,
          r.reference_number,
          rs.status_name AS status,
          COALESCE((SELECT SUM(qty) FROM receiving_details WHERE master_id = r.receiving_id),0) AS total_items,
          r.receive_date
          FROM receiving_master r
          LEFT JOIN receiving_statuses rs ON r.status = rs.status_id
          WHERE r.disabled = 0
          AND rs.status_name = 'Received'");
        $selectPrepare->execute();

        $headers = array(
          "ID" => "receiving_id",
          "Reference Number" => "reference_number",
          "Status" => "status",
          "Total Items" => "total_items",
          "Receive Date" => "receive_date"
        );

        $leftButtons = array(
          "View" => ""
        );

        displayGrid($selectPrepare, $headers, $leftButtons, null, null);
      ?>
    </div>
  </div>
  <div class="viewTemplate" style="display: none; height: 400px; margin-top: 10px;">
    <div class="formContentView" style="width: 400px;">
      <div class="formDiv">
        <input type="hidden" id="master_order_id" name="master_order_id" value="" disabled>
        <label for="item_name">Reference Number:</label>
        <input type="text" id="reference_number" name="reference_number" value="" disabled><br><br>
        <label for="receive_date">Receive Date:</label>
        <input type="text" id="receive_date" name="receive_date" value="" disabled><br><br>
        <label for="status">Order Status:</label>
        <select name="status" id="status" disabled>
        <?php
          //Get the order statuses
          $statusPrepare = $conn->prepare("SELECT
            rs.*
            FROM receiving_statuses rs
            WHERE rs.disabled = 0");
          $statusPrepare->execute();

          while ($row = $statusPrepare->fetch())
          {
            ?>
            <option value="<?=$row['status_id']?>" ><?=$row['status_name']?></option>
    <?php }
        ?>
        </select>
        <label for="creation_date">Creation Date:</label>
        <input type="text" id="creation_date" name="creation_date" value="" disabled><br><br>
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
            url: "../calls/get_receiving_order.php",
            type: "POST",
            data: {
              "order_id" : order_id
            },
            success: function(data){

              let returnData = JSON.parse(data);

              if(returnData["errors"] == null) {
                template.find('#master_order_id').val(order_id);
                template.find('#reference_number').val(returnData["data"][0]["reference_number"]);
                template.find('#receive_date').val(returnData["data"][0]["receive_date"]);
                template.find('#creation_date').val(returnData["data"][0]["creation_date"]);
                template.find('#status').val(returnData["data"][0]["status"]);
                template.find('#status').trigger("chosen:updated");
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
            buttons: {
              close: function () {

              }
            }
        });
   });
</script>
