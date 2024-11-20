<?php
  $config = include 'config.php';

  $server = $config['db_host'];
  $userid = $config['db_user'];
  $pw = $config['db_pwd'];
  $db = $config['db_name'];

  $conn = new mysqli($server, $userid, $pw);

  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  $conn->select_db($db);

  $sql = "SELECT * FROM products";
  $result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Select Products</title>
</head>
<body>
   <form action="db2.php" method="POST">
    <h2>Select Products:</h2>
    <?php
      if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
          echo '<input type="checkbox" name="products[]" value="' . htmlspecialchars($row['name']) . '"> ' . htmlspecialchars($row['name']) . '<br>';
        }
      } else {
        echo "no results";
      }
    ?>
    <br>
    <input type="submit" value="Submit">
  </form>
</body>
</html>

<?php
  $conn->close();
?>