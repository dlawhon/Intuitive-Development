<html>
<?php
  //ini_set("display_errors", true);
  require_once('../includes/header.php');

  //Save the info
  if($_SERVER['REQUEST_METHOD'] == 'POST') {

    $insertPrepare = $conn->prepare("INSERT INTO receiving_details
      (
        master_id,
        item_id,
        qty,
        location_id,
        user_id
      )
      VALUES
      (
        :master_id,
        :item_id,
        :qty,
        :location_id,
        :user_id
      )");
    $insertPrepare->execute(array(
            ":master_id" => $_POST['master_id'],
            ":item_id" => $_POST['item_id'],
            ":qty" => $_POST['qty'],
            ":location_id" => $_POST['location_id'],
            ":user_id" => $_SESSION['user_id']
          ));
  }

  $receivePrepare = $conn->prepare("SELECT
    rm.*
    FROM receiving_master rm
    LEFT JOIN receiving_details rd ON rd.master_id = rm.receiving_id
    WHERE rm.receiving_id = :receiving_id");
  $receivePrepare->execute(array(":receiving_id" => $_GET['id']));

  $masterData = $receivePrepare->fetch(PDO::FETCH_OBJ);
?>
<title>Receiving</title>
  <div class="content">
    <div class="mainPage2">
      <div>
        <h1 class="centered">Order# <?=$masterData->reference_number?></h1>
      </div>
      <div class="formContent" style="width: 80%; margin: auto; display: flex;">
        <form action="" method="post" enctype="multipart/form-data" style="padding-left: 30px; border: solid gray; border-radius: 25px; margin-bottom: 0px;">
            <input type="hidden" id="master_id" name="master_id" value="<?=$_GET['id']?>">
            <label for="item_id">Item:</label>
            <select name="item_id" id="item_id">
              <option value=""> </option>
            <?php
              //Get the items
              $itemPrepare = $conn->prepare("SELECT
                im.*
                FROM item_master im
                WHERE im.disabled = 0
                AND im.customer_id = :customer_id");
              $itemPrepare->execute(array(":customer_id" => $masterData->customer_id));

              while ($row = $itemPrepare->fetch())
              {
                ?>
                <option value="<?=$row['item_id']?>"><?=$row['item_name']?></option>
        <?php }
            ?>
            </select><br><br>
            <label for="qty">Quantity:</label>
            <input type="number" id="qty" name="qty" value=""><br><br>
            <label for="location_id">Location:</label>
            <select name="location_id" id="location_id">
              <option value=""> </option>
            <?php
              //Get the warehouse locations
              $locationPrepare = $conn->prepare("SELECT
                wl.*
                FROM warehouse_locations wl
                WHERE wl.disabled = 0");
              $locationPrepare->execute();

              while ($row = $locationPrepare->fetch())
              {
                ?>
                <option value="<?=$row['location_id']?>"><?=$row['location_name']?></option>
        <?php }
            ?>
            </select><br><br>
            <button type="submit" class="btn btn-success" id="receiveItem" name="receiveItem">Receive Item</button>
            <a href="index.php"><input type="button" class="btn btn-secondary" value="Back"/></a>
        </form>
        <div style="width: 800px;">
          <?php
            $selectPrepare = $conn->prepare("SELECT
              im.item_name,
              rd.qty,
              wl.location_name,
              u.username,
              rd.creation_date
              FROM receiving_details rd
              LEFT JOIN item_master im ON im.item_id = rd.item_id
              LEFT JOIN users u ON u.user_id = rd.user_id
              LEFT JOIN warehouse_locations wl ON wl.location_id = rd.location_id
              WHERE rd.disabled = 0
              AND rd.master_id = :master_id");
            $selectPrepare->execute(array(":master_id" => $masterData->receiving_id));

            $headers = array(
              "Item" => "item_name",
              "Qty" => "qty",
              "Location" => "location_name",
              "Scanned By" => "username",
              "Scanned At" => "creation_date"
            );

            displayGrid($selectPrepare, $headers, null, null, null);
          ?>
        </div>
      </div>
    </div>
  </div>
</html>
<script>
</script>
