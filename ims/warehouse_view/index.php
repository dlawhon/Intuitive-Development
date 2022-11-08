<html>
<?php
  ini_set("display_errors", true);
  require_once('../includes/header.php');
?>
<style>
.grid-container {
  display: grid;
  grid-template-columns: auto auto auto;
  background-color: #2196F3;
  padding: 10px;
}
.grid-item {
  background-color: rgba(255, 255, 255, 0.8);
  border: 1px solid rgba(0, 0, 0, 0.8);
  padding: 20px;
  font-size: 18px;
  text-align: center;
}
</style>
<title>Warehouse View</title>
  <div class="content">
    <div class="mainPage2">
      <div>
        <h1 class="centered">Warehouse View</h1>
      </div>
      <div class="grid-container" style="margin-left: 100px;">
        <?php
        $selectPrepare = $conn->prepare("SELECT
          wl.location_name,
          wl.location_id,
          SUM(rd.qty) AS total,
          im.item_name
          FROM warehouse_locations wl
          LEFT JOIN receiving_details rd ON rd.location_id = wl.location_id
          LEFT JOIN item_master im ON im.item_id = rd.item_id
          WHERE wl.disabled = 0
          AND rd.disabled = 0
          GROUP BY rd.item_id, wl.location_id");
        $selectPrepare->execute();

        while($row = $selectPrepare->fetch(PDO::FETCH_OBJ)) { ?>
          <div class="grid-item">
              <div style="font-size: 24px;"><?=$row->location_name?><br></div>
              Item: <?=$row->item_name?><br>
              Qty: <?=$row->total?>
          </div>
        <?php } ?>
      </div>
    </div>
  </div>
</html>
