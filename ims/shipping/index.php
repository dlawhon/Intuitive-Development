<html>
<?php
  //ini_set("display_errors", true);
  require_once('../includes/header.php');
?>
<title>Shipping</title>
  <div class="content">
    <div class="mainPage2">
      <div>
        <h1 class="centered">Shipping Orders</h1>
      </div>
      <div class="pageControls">
        <div class="btn btn-success" id="createShppingOrder">Create Order</div>
        <a href="shipping_history.php"><div class="btn btn-warning" id="viewHistory">View History</div></a>
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
        AND ss.status_name != 'Shipped'");
      $selectPrepare->execute();

      $headers = array(
        "ID" => "shipment_id",
        "Reference Number" => "reference_number",
        "Status" => "status",
        "Total Items" => "total_items",
        "Ship Date" => "ship_date"
      );

      $leftButtons = array(
        "Edit" => "",
        "Pick" => "picking.php?id=**shipment_id**"
      );

      $rightButtons = array(
        "Disable" => ""
      );

      displayGrid($selectPrepare, $headers, $leftButtons, $rightButtons, null);
      ?>
    </div>
  </div>
  <div class="creationTemplate" style="display: none; height: 300px; margin-top: 10px;">
    <div class="formContentView" style="width: 400px;">
      <div class="formDiv">
        <label for="reference_number">Reference Number:</label>
        <input type="text" id="reference_number" name="reference_number" value=""><br><br>
        <label for="ship_date">Ship Date:</label>
        <input type="text" id="ship_date" name="ship_date" value=""><br><br>
        <label for="status">Order Status:</label>
        <select name="status" id="status">
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
            <option value="<?=$row['status_id']?>" ><?=$row['status_name']?></option>
    <?php }
        ?>
        </select>
      </div>
    </div>
  </div>
  <div class="viewTemplate" style="display: none; height: 800px; margin-top: 10px;">
    <div class="formContentView" style="width: 400px;">
      <div class="formDiv">
        <input type="hidden" id="master_order_id" name="master_order_id" value="">
        <label for="reference_number">Reference Number:</label>
        <input type="text" id="reference_number" name="reference_number" value=""><br><br>
        <label for="ship_date">Ship Date:</label>
        <input type="text" id="ship_date" name="ship_date" value=""><br><br>
        <label for="status">Order Status:</label>
        <select name="status" id="status">
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
  $('#createShppingOrder').click(function() {

    var template = $('.creationTemplate:hidden').clone().css({"display" : "block"});
        template.find('#status_chosen').remove();
  			template.find('select').css({"display" : "block"});
  			template.find('select').chosen();
  			template.find('.chosen-container-single:first').css({"width" : "220px"});
        template.find('select').chosen({width: "100%"});

    $.confirm({
         title: "Create Shipping Order",
         content: template,
         columnClass: 'medium',
         backgroundDismiss: true,
         type: 'green',
         smoothContent: true,
         buttons: {
           create: {
             text: 'Create',
             btnClass: 'btn-green',
             action: function(){

               $.ajax({
                url: "../calls/create_shipping_order.php",
                type: "POST",
                data: {
                  "reference_number" : this.$content.find('#reference_number').val(),
                  "ship_date" : this.$content.find('#ship_date').val(),
                  "status": this.$content.find('#status').val()
                },
                success: function(data){

                  let returnData = JSON.parse(data);

                  if(returnData["errors"] == null) {
                    $.confirm({
                        title: "Success!",
                        content: 'The order was created',
                        boxWidth: '40%',
                        backgroundDismiss: true,
                        type: 'green',
                        icon: 'fa fa-check-circle',
                        buttons: {
                            close: function () {
                              location.reload();
                            }
                        }
                    });
                  } else {
                    $.confirm({
                        title: "Failure",
                        content: "The order was not created",
                        boxWidth: '40%',
                        backgroundDismiss: true,
                        type: 'red',
                        icon: 'fa fa-frown',
                        buttons: {
                            close: function () {
                              location.reload();
                            }
                        }
                    });
                  }

                }

              });

             }
           },
             close: function () {

             }
         }
     });
   });

   $('.leftGridButton, .rightGridButton').click(function() {

     if($(this).text() == 'Edit') {

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
              pull: {
                 text: 'Save',
                 btnClass: 'btn-green',
                 action: function(){

                   $.ajax({
                    url: "../calls/edit_shipping_order.php",
                    type: "POST",
                    data: {
                      "order_id" : this.$content.find('#master_order_id').val(),
                      "reference_number" : this.$content.find('#reference_number').val(),
                      "ship_date" : this.$content.find('#ship_date').val(),
                      "status": this.$content.find('#status').val()
                    },
                    success: function(data){

                      let returnData = JSON.parse(data);

                      if(returnData["errors"] == null) {
                        $.confirm({
                            title: "Success!",
                            content: 'The order was saved',
                            boxWidth: '40%',
                            backgroundDismiss: true,
                            type: 'green',
                            icon: 'fa fa-check-circle',
                            buttons: {
                                close: function () {
                                  location.reload();
                                }
                            }
                        });
                      } else {
                        $.confirm({
                            title: "Failure",
                            content: "The order was not saved",
                            boxWidth: '40%',
                            backgroundDismiss: true,
                            type: 'red',
                            icon: 'fa fa-frown',
                            buttons: {
                                close: function () {
                                  location.reload();
                                }
                            }
                        });
                      }
                    }
                  });
                 }
             },
              close: function () {

              }
            }
        });
     } else if($(this).text() == 'Disable') {

       var order_id = $(this).parent().parent().attr("data-rowId");
       $.confirm({
            title: "Disable Shipping Order",
            content: "Are you sure you want to disable this order?",
            backgroundDismiss: true,
            type: 'red',
            smoothContent: true,
            buttons: {
              create: {
                text: 'Yes',
                btnClass: 'btn-orange',
                action: function(){

                  $.ajax({
                   url: "../calls/disable_shipping_order.php",
                   type: "POST",
                   data: {
                     "order_id" : order_id,
                   },
                   success: function(data){

                     let returnData = JSON.parse(data);

                     if(returnData["errors"] == null) {
                       $.confirm({
                           title: "Success!",
                           content: 'The order was disabled',
                           boxWidth: '40%',
                           backgroundDismiss: true,
                           type: 'green',
                           icon: 'fa fa-check-circle',
                           buttons: {
                               close: function () {
                                 location.reload();
                               }
                           }
                       });
                     } else {
                       $.confirm({
                           title: "Failure",
                           content: "The order was not disabled",
                           boxWidth: '40%',
                           backgroundDismiss: true,
                           type: 'red',
                           icon: 'fa fa-frown',
                           buttons: {
                               close: function () {
                                 location.reload();
                               }
                           }
                       });
                     }

                   }

                 });

                }
              },
                no: function () {

                }
            }
        });
     }
   });
</script>
