<html>
<?php
  //Comment in to show errors
  //ini_set("display_errors", true);
  require_once('../includes/header.php');
?>
<title>Sites</title>
  <div class="content">
    <div class="mainPage2">
      <div>
        <h1 class="centered">Sites</h1>
      </div>
      <?php
        $sitesPrepare = $conn->prepare('SELECT * FROM sites WHERE disabled = 0');
        $sitesPrepare->execute();

        $headers = array(
          "ID" => "site_id",
          "Name" => "site_name",
          "Address" => "site_address",
          "City" => "site_city",
          "State" => "site_state",
          "Zip" => "site_zip",
          "Creation Date" => "creation_date"
        );

        $leftButtons = array(
          "Edit" => "",
        );

        $rightButtons = array(
          "Disable" => ""
        );


        displayGrid($sitesPrepare, $headers, $leftButtons, $rightButtons, null);
      ?>
    </div>
  </div>
  <div class="viewTemplate" style="display: none; height: 400px; margin-top: 10px;">
    <div class="formContentView" style="width: 400px;">
      <div class="formDiv">
        <input type="hidden" id="site_id" name="site_id" value="">
        <label for="item_name">Site Name:</label>
        <input type="text" id="site_name" name="site_name" value=""><br><br>
        <label for="receive_date">Address:</label>
        <input type="text" id="site_address" name="site_address" value=""><br><br>
        <label for="receive_date">City:</label>
        <input type="text" id="site_city" name="site_city" value=""><br><br>
        <label for="receive_date">State:</label>
        <input type="text" id="site_state" name="site_state" value=""><br><br>
        <label for="receive_date">Zip:</label>
        <input type="text" id="site_zip" name="site_zip" value=""><br><br>
        <label for="creation_date">Creation Date:</label>
        <input type="text" id="creation_date" name="creation_date" value="" disabled><br><br>
      </div>
    </div>
  </div>
</html>
<script>
$('.leftGridButton, .rightGridButton').click(function() {

  if($(this).text() == 'Edit') {

    var template = $('.viewTemplate:hidden').clone().css({"display" : "block"}),
        site_id = $(this).parent().parent().attr("data-rowId");
        template.find('#status_chosen').remove();
        template.find('select').css({"display" : "block"});
        template.find('select').chosen();
        template.find('.chosen-container-single:first').css({"width" : "220px"});
        template.find('select').chosen({width: "100%"});

        $.ajax({
         url: "../calls/get_site_info.php",
         type: "POST",
         data: {
           "site_id" : site_id
         },
         success: function(data){

           let returnData = JSON.parse(data);

           if(returnData["errors"] == null) {
             template.find('#site_id').val(site_id);
             template.find('#site_name').val(returnData["data"][0]["site_name"]);
             template.find('#site_address').val(returnData["data"][0]["site_address"]);
             template.find('#site_city').val(returnData["data"][0]["site_city"]);
             template.find('#site_state').val(returnData["data"][0]["site_state"]);
             template.find('#site_zip').val(returnData["data"][0]["site_zip"]);
             template.find('#creation_date').val(returnData["data"][0]["creation_date"]);
           } else {
             console.log(returnData["errors"]);
           }

         }

       });

    $.confirm({
         title: "Edit Site",
         content: template,
         columnClass: 'medium',
         backgroundDismiss: true,
         type: 'blue',
         smoothContent: true,
         buttons: {
           pull: {
            text: 'Save',
            btnClass: 'btn-green',
            action: function(){

              $.ajax({
               url: "../calls/edit_site.php",
               type: "POST",
               data: {
                 "site_id" : template.find('#site_id').val(),
                 "site_name" : template.find('#site_name').val(),
                 "site_address" : template.find('#site_address').val(),
                 "site_city" : template.find('#site_city').val(),
                 "site_state" : template.find('#site_state').val(),
                 "site_zip" : template.find('#site_zip').val()
               },
               success: function(data){

                 let returnData = JSON.parse(data);

                 if(returnData["errors"] == null) {
                   $.confirm({
                       title: "Success!",
                       content: 'The site was saved',
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
                       content: "The site was not saved",
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

    var site_id = $(this).parent().parent().attr("data-rowId");
    $.confirm({
         title: "Disable Site",
         content: "Are you sure you want to disable this site?",
         backgroundDismiss: true,
         type: 'red',
         smoothContent: true,
         buttons: {
           create: {
             text: 'Yes',
             btnClass: 'btn-orange',
             action: function(){

               $.ajax({
                url: "../calls/disable_site.php",
                type: "POST",
                data: {
                  "site_id" : site_id,
                },
                success: function(data){

                  let returnData = JSON.parse(data);

                  if(returnData["errors"] == null) {
                    $.confirm({
                        title: "Success!",
                        content: 'The site was disabled',
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
                        content: "The site was not disabled",
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
