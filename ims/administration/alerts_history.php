<html>
<?php
  //Comment in to show errors
  //ini_set("display_errors", true);
  require_once('../includes/header.php');
?>
<title>Alerts</title>
  <div class="content">
    <div class="mainPage2">
      <div>
        <h1 class="centered">Email Alerts</h1>
      </div>
      <div class="pageControls">
        <a href="alerts.php"><div class="btn btn-warning" id="viewActive">View Active Alerts</div></a>
      </div>
      <?php
        $alertsPrepare = $conn->prepare('SELECT
           ea.alert_id,
           ea.alert_description,
           ea.address,
           al.schedule_description,
           ea.creation_date
           FROM email_alerts ea
           LEFT JOIN alert_schedules al ON al.schedule_id = ea.alert_schedule
           WHERE ea.disabled = 1');
        $alertsPrepare->execute();

        $headers = array(
          "ID" => "alert_id",
          "Description" => "alert_description",
          "Address" => "address",
          "Schedule" => "schedule_description",
          "Creation Date" => "creation_date"
        );

        $leftButtons = array(
          "View" => "",
        );

        $rightButtons = array(
          "Activate" => ""
        );


        displayGrid($alertsPrepare, $headers, $leftButtons, $rightButtons, null);
      ?>
    </div>
  </div>
  <div class="viewTemplate" style="display: none; height: 400px; margin-top: 10px;">
    <div class="formContentView" style="width: 400px;">
      <div class="formDiv">
        <input type="hidden" id="alert_id" name="alert_id" value="">
        <label for="alert_description">Alert Description:</label>
        <input type="text" id="alert_description" name="alert_description" value=""><br><br>
        <label for="address">Address:</label>
        <input type="text" id="address" name="address" value=""><br><br>
        <label for="schedule">Schedule:</label>
        <select name="schedule" id="schedule">
        <?php
          //Get the alert schedules
          $schedulePrepare = $conn->prepare("SELECT
            al.*
            FROM alert_schedules al
            WHERE al.disabled = 0");
          $schedulePrepare->execute();

          while ($row = $schedulePrepare->fetch())
          {
            ?>
            <option value="<?=$row['schedule_id']?>" ><?=$row['schedule_description']?></option>
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
$('.leftGridButton, .rightGridButton').click(function() {

  if($(this).text() == 'View') {

    var template = $('.viewTemplate:hidden').clone().css({"display" : "block"}),
        alert_id = $(this).parent().parent().attr("data-rowId");
        template.find('#status_chosen').remove();
        template.find('select').css({"display" : "block"});
        template.find('select').chosen();
        template.find('.chosen-container-single:first').css({"width" : "220px"});
        template.find('select').chosen({width: "100%"});

        $.ajax({
         url: "../calls/get_alert_info.php",
         type: "POST",
         data: {
           "alert_id" : alert_id
         },
         success: function(data){

           let returnData = JSON.parse(data);

           if(returnData["errors"] == null) {
             template.find('#alert_id').val(alert_id);
             template.find('#alert_description').val(returnData["data"][0]["alert_description"]);
             template.find('#address').val(returnData["data"][0]["address"]);
             template.find('#schedule').val(returnData["data"][0]["alert_schedule"]);
             template.find('#creation_date').val(returnData["data"][0]["creation_date"]);
             template.find('#schedule').trigger("chosen:updated");
           } else {
             console.log(returnData["errors"]);
           }

         }

       });

    $.confirm({
         title: "View Alert",
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
  } else if($(this).text() == 'Activate') {

    var alert_id = $(this).parent().parent().attr("data-rowId");
    $.confirm({
         title: "Activate Alert",
         content: "Are you sure you want to activate this alert?",
         backgroundDismiss: true,
         type: 'red',
         smoothContent: true,
         buttons: {
           create: {
             text: 'Yes',
             btnClass: 'btn-orange',
             action: function(){

               $.ajax({
                url: "../calls/activate_alert.php",
                type: "POST",
                data: {
                  "alert_id" : alert_id,
                },
                success: function(data){

                  let returnData = JSON.parse(data);

                  if(returnData["errors"] == null) {
                    $.confirm({
                        title: "Success!",
                        content: 'The alert was activated',
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
                        content: "The alert was not activated",
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
