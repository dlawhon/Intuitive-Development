<html>
<?php
  ini_set("display_errors", true);
  require_once('../includes/header.php');
?>
<title>Search</title>
  <div class="content">
    <div class="mainPage2">
      <div>
        <h1 class="centered">Search Results</h1>
      </div>
      <?php

      if(isset($_GET['data']) && $_GET['data'] != '' && $_GET['data'] != ' ') {

        $searchPrepare = $conn->prepare("
          SELECT
            rm.receiving_id AS result_id,
            rm.reference_number AS result_data_1,
            rm.creation_date AS result_data_2,
            'Receiving Order' AS result_type
            FROM receiving_master rm
            WHERE (rm.reference_number LIKE ? OR rm.receiving_id LIKE ? OR rm.creation_date LIKE ?)
            AND rm.disabled = 0

        UNION

          SELECT
            sm.shipment_id AS result_id,
            sm.reference_number AS result_data_1,
            sm.creation_date AS result_data_2,
            'Shipping Order' AS result_type
            FROM ship_master sm
            WHERE (sm.reference_number LIKE ? OR sm.shipment_id LIKE ? OR sm.creation_date LIKE ?)
            AND sm.disabled = 0

        UNION

          SELECT
            u.user_id AS result_id,
            u.username AS result_data_1,
            u.creation_date AS result_data_2,
            'User' AS result_type
            FROM users u
            WHERE (u.user_id LIKE ? OR CONCAT(u.first_name, ' ', u.last_name) LIKE ? OR u.username LIKE ? OR u.creation_date LIKE ?)
            AND u.disabled = 0

        UNION

          SELECT
            im.item_id AS result_id,
            im.item_name AS result_data_1,
            im.creation_date AS result_data_2,
            'Item' AS result_type
            FROM item_master im
            WHERE (im.item_id LIKE ? OR im.item_name LIKE ?)
            AND im.disabled = 0

        UNION

          SELECT
            c.customer_id AS result_id,
            c.customer_name AS result_data_1,
            c.creation_date AS result_data_2,
            'Customer' AS result_type
            FROM customers c
            WHERE (c.customer_id LIKE ? OR c.customer_name LIKE ?)
            AND c.disabled = 0

          ");
        $searchPrepare->execute(array
        (
          '%'.$_GET['data'].'%',
          '%'.$_GET['data'].'%',
          '%'.$_GET['data'].'%',
          '%'.$_GET['data'].'%',
          '%'.$_GET['data'].'%',
          '%'.$_GET['data'].'%',
          '%'.$_GET['data'].'%',
          '%'.$_GET['data'].'%',
          '%'.$_GET['data'].'%',
          '%'.$_GET['data'].'%',
          '%'.$_GET['data'].'%',
          '%'.$_GET['data'].'%',
          '%'.$_GET['data'].'%',
          '%'.$_GET['data'].'%'
        ));

        $headers = array(
          "ID" => "result_id",
          "Result Type" => "result_type",
          "Data" => "result_data_1",
          "Creation Date" => "result_data_2"
        );

        displayGrid($searchPrepare, $headers);

      } else { ?>
        <p style="text-align: center;">
          No data to search by
        </p>
      <?php }
      ?>
    </div>
  </div>
</html>
