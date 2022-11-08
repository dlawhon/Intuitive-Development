<html>
<?php
  //ini_set("display_errors", true);
  require_once('../includes/header.php');

  //Save the info
  if($_SERVER['REQUEST_METHOD'] == 'POST') {

    $insertPrepare = $conn->prepare("INSERT INTO picking
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

  $shipmentPrepare = $conn->prepare("SELECT
    sm.*
    FROM ship_master sm
    LEFT JOIN ship_details sd ON sd.master_id = sm.shipment_id
    WHERE sm.shipment_id = :shipment_id");
  $shipmentPrepare->execute(array(":shipment_id" => $_GET['id']));

  $masterData = $shipmentPrepare->fetch(PDO::FETCH_OBJ);
?>
<title>Shipping</title>
  <div class="content">
    <div class="mainPage2" style="height: 700px;">
      <div>
        <h1 class="centered">Order# <?=$masterData->reference_number?></h1>
      </div>
      <div class="formContent" style="width: 80%; height: 100%; display:flex;">
       <div style="padding-top: 150px;">
         <h3 style="text-align: center;">Picking</h3>
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
            <button type="submit" class="btn btn-success" id="pickItem" name="pickItem">Pick Item</button>
            <a href="index.php"><input type="button" class="btn btn-secondary" value="Back"/></a>
        </form>
        </div>
        <div>
        <h3 style="text-align: center;">Ordered Items</h3>
          <?php
            $selectPrepare = $conn->prepare("SELECT
              im.item_name,
              sd.qty
              FROM ship_details sd
              LEFT JOIN item_master im ON im.item_id = sd.item_id
              WHERE sd.disabled = 0
              AND sd.master_id = :master_id");
            $selectPrepare->execute(array(":master_id" => $masterData->shipment_id));

            $headers = array(
              "Item" => "item_name",
              "Qty" => "qty"
            );

            displayGrid($selectPrepare, $headers, null, null, null);
          ?>
          <br><br>
          <h3 style="text-align: center;">Picked Items</h3>
          <?php
            $selectPrepare = $conn->prepare("SELECT
              im.item_name,
              p.qty,
              wl.location_name,
              u.username,
              p.creation_date
              FROM picking p
              LEFT JOIN item_master im ON im.item_id = p.item_id
              LEFT JOIN users u ON u.user_id = p.user_id
              LEFT JOIN warehouse_locations wl ON wl.location_id = p.location_id
              WHERE p.disabled = 0
              AND p.master_id = :master_id");
            $selectPrepare->execute(array(":master_id" => $masterData->shipment_id));

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
</html>
<script>
</script>
