<?php

// function getUserIP()
// {
//   // Get real visitor IP behind CloudFlare network
//   if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
//             $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
//             $_SERVER['HTTP_CLIENT_IP'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
//   }
//   $client  = @$_SERVER['HTTP_CLIENT_IP'];
//   $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
//   $remote  = $_SERVER['REMOTE_ADDR'];
//
//   if(filter_var($client, FILTER_VALIDATE_IP))
//   {
//       $ip = $client;
//   }
//   elseif(filter_var($forward, FILTER_VALIDATE_IP))
//   {
//       $ip = $forward;
//   }
//   else
//   {
//       $ip = $remote;
//   }
//
//   return $ip;
// }
//
// $user_ip = getUserIP();
//
// $allowed_addresses = array('8.2.12.46', '97.114.204.95', '167.224.220.217');


$is_login_page = true;
require_once('includes/settings.php');

if(!empty($_POST)) {

  $username = $_POST['username'];
  $password = $_POST['password'];

  $selectPrepare = $conn->prepare("SELECT
    u.user_id,
    u.username,
    u.hash,
    u.role,
    r.role_name
    FROM users u
    LEFT JOIN roles r ON r.role_id = u.role
    WHERE u.username = :username");
  $selectPrepare->execute(array(":username" => $username));

  $selectResult = $selectPrepare->fetch(PDO::FETCH_OBJ);

  $hashed_password = $selectResult->hash;

  if(password_verify($password, $hashed_password)) {
    session_start();
    $_SESSION['login'] = 'true';
    $_SESSION['user_id'] = $selectResult->user_id;
    $_SESSION['user'] = $selectResult->username;
    $_SESSION['role'] = $selectResult->role;
    $_SESSION['role_name'] = $selectResult->role_name;

    //die(var_dump($_SESSION));
    session_write_close();

    header("Location: dashboard.php");
    exit();
  } else {
    $_SESSION['login'] = 'false';
  }
}
?>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=STIX+Two+Text&display=swap" rel="stylesheet">
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<style>
  .mainPage {
    position: absolute;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
    //background: #736d6d;
    background: black;
    width: 100%;
    height: 100%;
    display: flex;
  }
  .loginBox {
    width: 600px;
    height: 400px;
    margin: auto;
    border: 1px solid #525151;
    border-radius: 25px;
    display: flex;
    box-shadow: 5px 10px 18px #424040;
  }
  .loginBoxLeft {
    background: green;
    height: 100%;
    width: 50%;
    border-radius: 25px 0px 0px 25px;
    animation-name: loginAnimation;
    animation-duration: 60s;
    animation-iteration-count: infinite;
    text-align: center;
  }
  .loginBoxRight {
    background: #eaeaea;
    height: 100%;
    width: 50%;
    border-radius: 0px 25px 25px 0px;
  }
  p {
    font-family: 'Roboto', sans-serif;
  }
  h1 {
    font-family: 'STIX Two Text', serif;
    font-size: 40px;
  }
  .btn {
    font-family: 'Roboto', sans-serif;
    font-size: 15px;
    width: 70px;
    background: #2e8b57;
    border: 1px solid #424040;
    border-radius: 5px;
    text-align: center;
    padding: 3px 0;
    height: 25px;
  }
  @keyframes loginAnimation {
  0%   {background-color:#92a8d1;}
  25%  {background-color:#034f84;}
  50%  {background-color:#f7cac9;}
  75%  {background-color:#f7786b;}
  100%  {background-color:#b1cbbb;}
}
</style>
<html>
<title>Intuitive Inventory Management</title>
  <div class="mainPage">
    <div class="loginBox">
      <div class="loginBoxLeft">
        <div style="margin-top: 150px;">
          <h1>IMS</h1>
          <h3>Intuitive Development</h3>
        </div>
      </div>
      <div class="loginBoxRight">
        <form action="" method="post">
          <div style="margin-top: 100px; margin-left: 40px;">
            <?php if($_SESSION['login'] == 'false') { ?>
              <p style="background: #ff4e4e; width: 235px;">Incorrect Username or Password</p>
            <?php } ?>
            <p>Username</p>
            <input type="text" name="username" id="username"><br>
            <p>Password</p>
            <input type="password" name="password" id="password"><br><br>
            <button type="submit" class="btn btn-success" id="login">Submit</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</html>
