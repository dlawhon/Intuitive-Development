<html>
<?php
  //ini_set("display_errors", true);
  require_once('../includes/header.php');
?>
<title>Dashboard</title>
  <div class="content">
    <div class="mainPage2">
      <div>
        <h1 class="centered">Equipment</h1>
      </div>
      <table class="table">
        <tr><th>ID</th><th>Username</th><th>Name</th><th>Email</th><th>System Date</th></tr>
      <?php
        $stmt = $conn->query('SELECT * FROM users WHERE disabled = 0');

        while ($row = $stmt->fetch())
        {
          echo "<tr><td>" . $row['id'] . "</td><td>" . $row['username']  . "</td><td>" . $row['first_name'] . " " . $row['last_name'] .
               "</td><td>" . $row['email'] . "</td><td>" . $row['creation_date'] . "</td></tr>";
        }
      ?>
      </table>

    </div>
  </div>
</html>
