<?php
  //ini_set("display_errors", true);
  require_once('../includes/header.php');

  //Save the account info
  if($_SERVER['REQUEST_METHOD'] == 'POST') {

    $usernameValidatePrepare = $conn->prepare("SELECT * FROM users WHERE username = :username");
    $usernameValidatePrepare->execute(array(
      ":username" => $_POST['username']));
    if($usernameValidatePrepare->rowCount() == 0)
    {
      $userPrepare = $conn->prepare("UPDATE users SET
        username = :username,
        first_name = :first_name,
        last_name = :last_name,
        role = :role,
        email = :email
        WHERE user_id = :user_id");
      $userPrepare->execute(array(":username" => $_POST['username'],
       ":first_name" => $_POST['first_name'],
       ":last_name" => $_POST['last_name'],
       ":role" => $_POST['role'],
       ":email" => $_POST['email'],
       ":user_id" => $_POST['user_id']));
    } else {
      $userPrepare = $conn->prepare("UPDATE users SET
        first_name = :first_name,
        last_name = :last_name,
        role = :role,
        email = :email
        WHERE user_id = :user_id");
      $userPrepare->execute(array(
       ":first_name" => $_POST['first_name'],
       ":last_name" => $_POST['last_name'],
       ":role" => $_POST['role'],
       ":email" => $_POST['email'],
       ":user_id" => $_POST['user_id']));
    }

    if($_POST['password'] != "" && $_POST['password'] != " ") {
      setPassword($_POST['user_id'], $_POST['password']);

      header("Location: ".BASE_URL."login.php");
      die();
    } else {
      header("Location: ".BASE_URL."dashboard.php");
      die();
    }


  }

  if($_GET['id']) {
    //Get the user's info
    $userPrepare = $conn->prepare("SELECT
    *
    FROM users
    WHERE user_id = :user_id");
    $userPrepare->execute(array(":user_id" => $_GET['id']));
    if($userPrepare->rowCount() == 0)
    {
      echo 'User Not Found';
      die();
    }
    $user = $userPrepare->fetch(PDO::FETCH_OBJ);
  }
?>
<html>
<title>My Account</title>
  <div class="content">
    <div class="mainPage2">
      <div>
        <h1 class="centered">My Account</h1>
      </div>
      <div class="formContent" style="width: 800px;">
        <form action="" method="post" enctype="multipart/form-data" style="display: flex;">
          <div>
            <input type="hidden" id="user_id" name="user_id" value="<?=$_GET['id']?>">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" value="<?=$user->username?>"><br><br>
            <label for="first_name">First Name:</label>
            <input type="text" id="first_name" name="first_name" value="<?=$user->first_name?>"><br><br>
            <label for="last_name">Last Name:</label>
            <input type="text" id="last_name" name="last_name" value="<?=$user->last_name?>"><br><br>
          </div>
          <div style="padding-left: 10px;">
            <label for="role">Role:</label>
            <select name="role" id="role">
            <?php
              //Get the user's role
              $rolePrepare = $conn->prepare("SELECT
                r.*
                FROM roles r
                WHERE r.disabled = 0");
              $rolePrepare->execute();

              while($row = $rolePrepare->fetch(PDO::FETCH_OBJ))
              {
                if($row->role_id == $user->role) {
                  $selected = "selected";
                } else {
                  $selected = "";
                }
                ?>
                <option value="<?=$row->role_id?>"<?=$selected?>><?=$row->role_name?></option>
        <?php }
            ?>
            </select><br><br>
            <label for="email">Email:</label>
            <input type="text" id="email" name="email" value="<?=$user->email?>"><br><br>
            <label for="password">Change Password:</label>
            <input type="password" id="password" name="password" value=""><br><br>
            <input class="btn btn-success" type="submit" value="Save">
          </div>
        </form>
      </div>
    </div>
  </div>
</html>
