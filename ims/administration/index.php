<html>
<?php
  //Comment in to show errors
  //ini_set("display_errors", true);
  require_once('../includes/header.php');
?>
<title>Administration</title>
  <div class="content">
    <div class="mainPage2" style="margin-left: 150px;">
      <div>
        <h1 class="centered">Administration</h1>
      </div>
      <ul style="font-size: 18px;">
        <li><a href="<?= BASE_URL ?>administration/items.php">Items</a></li>
        <li><a href="<?= BASE_URL ?>administration/locations.php">Locations</a></li>
        <li><a href="<?= BASE_URL ?>administration/sites.php">Sites</a></li>
        <li><a href="<?= BASE_URL ?>administration/statuses.php">Statuses</a></li>
        <li><a href="<?= BASE_URL ?>administration/warehouses.php">Warehouses</a></li>
        <li><a href="<?= BASE_URL ?>administration/users.php">Users</a></li>
        <li><a href="<?= BASE_URL ?>administration/roles.php">Roles</a></li>
        <li><a href="<?= BASE_URL ?>administration/disabled.php">Disabled **support only**</a></li>
      </ul>
    </div>
  </div>
</html>
