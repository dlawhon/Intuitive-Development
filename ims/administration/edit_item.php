<html>
<?php
  //ini_set("display_errors", true);
  require_once('../includes/header.php');

  //Save the item from the form
  if($_SERVER['REQUEST_METHOD'] == 'POST'){

    $itemPrepare = $conn->prepare("UPDATE item_master SET item_name = :item_name, cost = :cost WHERE item_id = :item_id");
    $itemPrepare->execute(array(":item_name" => $_POST['item_name'], ":cost" => $_POST['cost'], ":item_id" => $_POST['item_id']));

    header('location: items.php');
    die();
  }

  //Get the item infor for the edit page
  $itemPrepare = $conn->prepare("SELECT *, FORMAT(cost, 2) AS cost FROM item_master WHERE item_id = :item_id");
  $itemPrepare->execute(array(":item_id" => $_GET['id']));
  if($itemPrepare->rowCount() == 0)
  {
    echo 'Item Not Found';
    die();
  }
  $item = $itemPrepare->fetch(PDO::FETCH_OBJ);
?>
<style>
  label {
    width: 80px;
  }
</style>
<title>Edit Item</title>
  <div class="content">
    <div class="mainPage2">
      <div>
        <h1 class="centered">Edit Item</h1>
      </div>
      <div class="formContent" style="width: 300px;">
        <form action="" method="post" enctype="multipart/form-data">
          <input type="hidden" id="item_id" name="item_id" value="<?=$_GET['id']?>">
          <label for="item_name">Item Name:</label>
          <input type="text" id="item_name" name="item_name" value="<?=$item->item_name?>"><br><br>
          <label for="cost">Cost:</label>
          <input type="text" id="cost" name="cost" value="<?=$item->cost?>"><br><br>
          <input class="saveButton" type="submit" value="Save">
        </form>
      </div>
    </div>
  </div>
</html>
