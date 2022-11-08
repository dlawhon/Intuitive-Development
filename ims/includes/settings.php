<?php
require_once('grid.php');

  if(isset($is_login_page)) {
    //mysql connection
    $servername = "localhost";
    $username = "root";
    $password = "HannaH$$10L";
    //define("BASE_URL", "http://159.203.127.162/ims/");
    define("BASE_URL", "https://intuitivedevelopment.io/ims/");

    try {
      $conn = new PDO("mysql:host=$servername;dbname=ims", $username, $password);
      // set the PDO error mode to exception
      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    } catch(PDOException $e) {
      die("Connection failed: " . $e->getMessage());
    }
  } else {

      session_start();
      //define("BASE_URL", "http://159.203.127.162/ims/");
      define("BASE_URL", "https://intuitivedevelopment.io/ims/");

      if(empty($_SESSION['user']) or empty($_SESSION['role']))
      {
        $_SESSION['login'] = 'true';
        header("Location: ".BASE_URL."login.php");
      } else {

        $servername = "localhost";
        $username = "root";
        $password = "HannaH$$10L";

        try {
          $conn = new PDO("mysql:host=$servername;dbname=ims", $username, $password);
          // set the PDO error mode to exception
          $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        } catch(PDOException $e) {
          die("Connection failed: " . $e->getMessage());
        }
      }
  }

require_once('functions.php');
?>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=STIX+Two+Text&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Nanum+Gothic&display=swap" rel="stylesheet">
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
<link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>
<link rel="stylesheet" href="<?= BASE_URL ?>css/styles.css">
<link rel="stylesheet" href="http://code.jquery.com/ui/1.9.2/themes/base/jquery-ui.css">
<script src="http://code.jquery.com/jquery-1.8.3.js"></script>
<script src="http://code.jquery.com/ui/1.9.2/jquery-ui.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.4.2/chosen.jquery.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.4.2/chosen.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
<script type="text/javascript">
  $(function(){
    $("select").chosen();
  });
</script>
