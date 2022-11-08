<?php
//ini_set("display_errors", true);
require_once('../includes/settings.php');
$success = null;
$error = null;
$data = null;

if(!empty($_POST)) {
  $site_id = $_POST['site_id'];

  if($site_id == "") {
    $error = 'bad data';
  } else {
    $sitePrepare = $conn->prepare("UPDATE sites SET disabled = 1 WHERE site_id = :site_id");

    if($sitePrepare) {
      $success = "success";

      $sitePrepare->execute(array(":site_id" => $site_id));

    } else {
      $error = 'sitePrepare';
    }
  }
} else {
  $error = "No data posted";
}

ob_get_clean();
echo json_encode(array("success" => $success, "errors" => $error));
