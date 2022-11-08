<html>
<?php
  //ini_set("display_errors", true);
  require_once('../includes/header.php');
?>
<title>Dashboard</title>
  <div class="content">
    <div class="mainPage2">
      <div>
        <h1 class="centered">Users</h1>
      </div>
      <div class="pageControls">
        <div class="btn btn-success" id="createUser">Create User</div>
        <div class="btn btn-warning" id="viewHistory">View History</div>
      </div>
      <?php
        $userPrepare = $conn->prepare("SELECT
          u.*,
          r.role_name
          FROM users u
          LEFT JOIN roles r ON r.role_id = u.role
          WHERE u.disabled = 0");
        $userPrepare->execute();

        $headers = array(
          "ID" => "user_id",
          "Username" => "username",
          "First Name" => "first_name",
          "Last Name" => "last_name",
          "Role" => "role_name",
          "Email" => "email",
          "Creation Date" => "creation_date"
        );

        $leftButtons = array(
          "Edit" => ""
        );

        $rightButtons = array(
          "Disable" => ""
        );

        displayGrid($userPrepare, $headers, $leftButtons, $rightButtons, null);
      ?>
    </div>
  </div>
  <div class="creationTemplate" style="display: none; height: 400px; margin-top: 10px;">
    <div class="formContent" style="width: 400px;">
      <div class="formDiv">
        <input type="hidden" id="user_id" name="user_id" value="">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" value=""><br><br>
        <label for="first_name">First Name:</label>
        <input type="text" id="first_name" name="first_name" value=""><br><br>
        <label for="last_name">Last Name:</label>
        <input type="text" id="last_name" name="last_name" value=""><br><br>
        <label for="role">Role:</label>
        <select name="role" id="role">
        <?php
          //Get the user's role
          $rolePrepare = $conn->prepare("SELECT
            r.*
            FROM roles r
            WHERE r.disabled = 0");
          $rolePrepare->execute();

          while ($row = $rolePrepare->fetch())
          {
            ?>
            <option value="<?=$row['role_id']?>" ><?=$row['role_name']?></option>
    <?php }
        ?>
        </select>
        <label for="email">Email:</label>
        <input type="text" id="email" name="email" value=""><br><br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" value=""><br><br>
      </div>
    </div>
  </div>
  <div class="viewTemplate" style="display: none; height: 500px; margin-top: 10px;">
    <div class="formContent" style="width: 400px;">
      <div class="formDiv">
        <input type="hidden" id="user_id" name="user_id" value="">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" value=""><br><br>
        <label for="first_name">First Name:</label>
        <input type="text" id="first_name" name="first_name" value=""><br><br>
        <label for="last_name">Last Name:</label>
        <input type="text" id="last_name" name="last_name" value=""><br><br>
        <label for="role">Role:</label>
        <select name="role" id="role">
        <?php
          //Get the user's role
          $rolePrepare = $conn->prepare("SELECT
            r.*
            FROM roles r
            WHERE r.disabled = 0");
          $rolePrepare->execute();

          while ($row = $rolePrepare->fetch())
          {
            ?>
            <option value="<?=$row['role_id']?>" ><?=$row['role_name']?></option>
    <?php }
        ?>
        </select>
        <label for="email">Email:</label>
        <input type="text" id="email" name="email" value=""><br><br>
        <label for="change_password">Change Password:</label>
        <input type="password" id="change_password" name="change_password" value=""><br><br>
        <label for="creation_date">Creation Date:</label>
        <input type="text" id="creation_date" name="creation_date" value="" disabled><br><br>
      </div>
    </div>
  </div>
</html>
<script>
$('#createUser').click(function() {

  var template = $('.creationTemplate:hidden').clone().css({"display" : "block"});
      template.find('#role_chosen').remove();
      template.find('select').css({"display" : "block"});
      template.find('select').chosen();
      template.find('.chosen-container-single:first').css({"width" : "220px"});
      template.find('select').chosen({width: "100%"});

      template.find('#username').change(function() {
        $.ajax({
         url: "../calls/validate_user.php",
         type: "POST",
         data: {
           "username" : $(this).val()
         },
         success: function(data){

           let returnData = JSON.parse(data);

           if(returnData["errors"] != null) {
             template.find('#username').val('');
             template.find('#username').focus();
             alert(returnData["errors"]);
           }
         }
       });;
      });

  $.confirm({
       title: "Create User",
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

             let username = this.$content.find('#username').val().trim(),
                 email = this.$content.find('#email').val().trim(),
                 password = this.$content.find('#password').val();

             if(username == '') {
               this.$content.find('#username').focus();
               this.$content.find('#username').css({"background-color" : "rgb(243 136 136)"});
               exit();
             } else if(email == '') {
               this.$content.find('#email').focus();
               this.$content.find('#email').css({"background-color" : "rgb(243 136 136)"});
               exit();
             } else if(password == '') {
               this.$content.find('#password').focus();
               this.$content.find('#password').css({"background-color" : "rgb(243 136 136)"});
               exit();
             } else if (password == ' ') {
               this.$content.find('#password').focus();
               this.$content.find('#password').css({"background-color" : "rgb(243 136 136)"});
               exit();
             }

             $.ajax({
              url: "../calls/create_user.php",
              type: "POST",
              data: {
                "username" : this.$content.find('#username').val(),
                "first_name" : this.$content.find('#first_name').val(),
                "last_name": this.$content.find('#last_name').val(),
                "role": this.$content.find('#role').val(),
                "email": this.$content.find('#email').val(),
                "password": this.$content.find('#password').val()
              },
              success: function(data){

                let returnData = JSON.parse(data);

                if(returnData["errors"] == null) {
                  $.confirm({
                      title: "Success!",
                      content: 'The user was created',
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
                      content: "The user was not created",
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
        user_id = $(this).parent().parent().find(".leftGridButton:last").parent().next('td').text();
        template.find('#role_chosen').remove();
        template.find('select').css({"display" : "block"});
        template.find('select').chosen();
        template.find('.chosen-container-single:first').css({"width" : "220px"});
        template.find('select').chosen({width: "100%"});

        template.find('#username').change(function() {
          $.ajax({
           url: "../calls/validate_user.php",
           type: "POST",
           data: {
             "username" : $(this).val()
           },
           success: function(data){

             let returnData = JSON.parse(data);

             if(returnData["errors"] != null) {
               template.find('#username').val('');
               template.find('#username').focus();
               alert(returnData["errors"]);
             }
           }
         });;
        });

        $.ajax({
         url: "../calls/get_user_data.php",
         type: "POST",
         data: {
           "user_id" : user_id
         },
         success: function(data){

           let returnData = JSON.parse(data);

           if(returnData["errors"] == null) {
             template.find('#user_id').val(user_id);
             template.find('#username').val(returnData["data"][0]["username"]);
             template.find('#first_name').val(returnData["data"][0]["first_name"]);
             template.find('#last_name').val(returnData["data"][0]["last_name"]);
             template.find('#role').val(returnData["data"][0]["role"]);
             template.find('#email').val(returnData["data"][0]["email"]);
             template.find('#creation_date').val(returnData["data"][0]["creation_date"]);
             template.find('#role').trigger("chosen:updated");
           } else {
             console.log(returnData["errors"]);
           }

         }

       });

    $.confirm({
         title: "Edit User",
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
               url: "../calls/edit_user.php",
               type: "POST",
               data: {
                 "user_id" : template.find('#user_id').val(),
                 "username" : template.find('#username').val(),
                 "first_name" : template.find('#first_name').val(),
                 "last_name" : template.find('#last_name').val(),
                 "role" : template.find('#role').val(),
                 "email" : template.find('#email').val(),
                 "change_password" : template.find('#change_password').val()
               },
               success: function(data){

                 let returnData = JSON.parse(data);

                 if(returnData["errors"] == null) {
                   $.confirm({
                       title: "Success!",
                       content: 'The user was saved',
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
                       content: "The user was not saved",
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

    var user_id = $(this).parent().parent().find(".leftGridButton:last").parent().next('td').text();
    $.confirm({
         title: "Disable User",
         content: "Are you sure you want to disable this user?",
         backgroundDismiss: true,
         type: 'red',
         smoothContent: true,
         buttons: {
           create: {
             text: 'Yes',
             btnClass: 'btn-orange',
             action: function(){

               $.ajax({
                url: "../calls/disable_user.php",
                type: "POST",
                data: {
                  "user_id" : user_id,
                },
                success: function(data){

                  let returnData = JSON.parse(data);

                  if(returnData["errors"] == null) {
                    $.confirm({
                        title: "Success!",
                        content: 'The user was disabled',
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
                        content: "The user was not disabled",
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
