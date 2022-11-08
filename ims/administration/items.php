<html>
<?php
  //ini_set("display_errors", true);
  require_once('../includes/header.php');
?>
<title>Items</title>
  <div class="content">
    <div class="mainPage2">
      <div>
        <h1 class="centered">Items</h1>
      </div>
      <?php
        $stmt = $conn->query('SELECT *, CONCAT("$",FORMAT(cost, 2)) AS cost FROM item_master WHERE disabled = 0');

        $headers = array(
          "ID" => "item_id",
          "Name" => "item_name",
          "Cost" => "cost",
          "Creation Date" => "creation_date"
        );

        $buttons = array(
          "Edit" => "edit_item.php?id=**item_id**"
        );

        displayGrid($stmt, $headers, $buttons);
      ?>
    </div>
  </div>
</html>
