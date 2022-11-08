<html>
<?php
  ini_set("display_errors", true);
  require_once('../includes/header.php');

  //Save the order info
  if($_SERVER['REQUEST_METHOD'] == 'POST'){

    $itemPrepare = $conn->prepare("UPDATE ship_master SET
      reference_number = :reference_number,
      ship_date = :ship_date,
      status = :status
      WHERE shipment_id = :shipment_id");
    $itemPrepare->execute(array(
      ":reference_number" => $_POST['reference_number'],
      ":ship_date" => date("Y-m-d", strtotime($_POST['ship_date'])),
      ":status" => $_POST['status'],
      ":shipment_id" => $_POST['master_order_id']));

    header('location: ../shipping/');
    die();
  }

  //Get the order info
  $orderPrepare = $conn->prepare("SELECT
    sm.*,
    sd.*,
    sm.status AS status_id,
    ss.status_name AS status,
    sm.creation_date AS master_creation_date
    FROM ship_master sm
    LEFT JOIN ship_details sd ON sm.shipment_id = sd.master_id
    LEFT JOIN shipping_statuses ss ON sm.status = ss.status_id
    WHERE sm.disabled = 0
    AND sm.shipment_id = :shipment_id");
  $orderPrepare->execute(array(":shipment_id" => $_GET['id']));
  if($orderPrepare->rowCount() == 0)
  {
    echo 'Order Not Found';
    die();
  }
  $order = $orderPrepare->fetch(PDO::FETCH_OBJ);
?>
<title>Shipping</title>
  <div class="content">
    <div class="mainPage2">
      <div>
        <h1 class="centered">Order <?=$order->reference_number?></h1>
      </div>
      <div class="formContent" style="width: 400px;">
        <form action="" method="post" enctype="multipart/form-data">
          <input type="hidden" id="master_order_id" name="master_order_id" value="<?=$_GET['id']?>">
          <label for="reference_number">Reference Number:</label>
          <input type="text" id="reference_number" name="reference_number" value="<?=$order->reference_number?>"><br><br>
          <label for="ship_date">Ship Date:</label>
          <input type="text" id="ship_date" name="ship_date" value="<?=date("m/d/Y", strtotime($order->ship_date))?>"><br><br>
          <label for="status">Order Status:</label>
          <select name="status" id="status">
          <?php
            //Get the order statuses
            $statusPrepare = $conn->prepare("SELECT
              ss.*
              FROM shipping_statuses ss
              WHERE ss.disabled = 0");
            $statusPrepare->execute();

            while ($row = $statusPrepare->fetch())
            {
              if($row['status_id'] == $order->status_id) {
                $selected = "selected";
              } else {
                $selected = "";
              }
              ?>
              <option value="<?=$row['status_id']?>" <?=$selected?>><?=$row['status_name']?></option>
      <?php }
          ?>
          </select>
          <label for="status">Creation Date:</label>
          <input type="text" id="status" name="status" value="<?=date("m/d/Y h:m:s", strtotime($order->master_creation_date))?>" disabled><br><br>
          <input class="saveButton" type="submit" value="Save">
        </form>
      </div>
    </div>
  </div>
</html>
