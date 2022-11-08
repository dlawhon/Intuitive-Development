<?php
require_once('../includes/settings.php');
$success = null;
$error = null;
$data = null;

if(!empty($_POST)) {
  $site_id = $_POST['site_id'];
  $site_name = $_POST['site_name'];
  $site_address = $_POST['site_address'];
  $site_city = $_POST['site_city'];
  $site_state = $_POST['site_state'];
  $site_zip = $_POST['site_zip'];

  if($site_id == "") {
    $error = 'bad data';
  } else {

    $sitePrepare = $conn->prepare("UPDATE sites SET
      site_name = :site_name,
      site_address = :site_address,
      site_city = :site_city,
      site_state = :site_state,
      site_zip = :site_zip
      WHERE site_id = :site_id");

    if($sitePrepare) {
      $success = "success";

      $sitePrepare->execute(array(
        ":site_name" => $site_name,
        ":site_address" => $site_address,
        ":site_city" => $site_city,
        ":site_state" => $site_state,
        ":site_zip" => $site_zip,
        ":site_id" => $site_id));

    } else {
      $error = 'sitePrepare';
    }
  }
} else {
  $error = "No data posted";
}

ob_get_clean();
echo json_encode(array("success" => $success, "errors" => $error));
