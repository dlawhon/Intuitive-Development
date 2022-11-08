<html>
<?php
  //ini_set("display_errors", true);
  require_once('../includes/header.php');
?>
<title>Locations</title>
  <div class="content">
    <div class="mainPage2">
      <div>
        <h1 class="centered">Locations</h1>
      </div>
      <?php
        $stmt = $conn->query('SELECT * FROM warehouse_locations WHERE disabled = 0');

        $headers = array(
          "ID" => "location_id",
          "Name" => "location_name",
          "Creation Date" => "creation_date"
        );

        $buttons = array(
          "Edit" => "edit_item.php?id=**location_id**"
        );

        displayGrid($stmt, $headers, $buttons);
      ?>
    </div>
  </div>
</html>
