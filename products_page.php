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
  <style>
    .product-container {
      display: grid;
      grid-template-columns: repeat(3, 1fr); 
      gap: 15px; 
    }
    .product-item {
      border: 1px solid #ccc;
      border-radius: 5px;
      padding: 10px;
      text-align: center;
    }
    .product-item img {
      max-width: 100%;
      height: auto;
      border-radius: 5px;
    }
    .product-item strong {
      display: block;
      margin-bottom: 5px;
      font-size: 1.2em;
    }
    .product-item .price {
      font-weight: bold;
      color: green;
    }
</style>
</head>
<body>
  <form action="db2.php" method="POST">
    <h2>Select Products:</h2>
    <div class="product-container">
      <?php
        if ($result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {
            echo '<div class="product-item">';
            echo '<input type="checkbox" name="products[]" value="' . htmlspecialchars($row['image_url']) . '"> Select<br>';
            echo '<strong>' . htmlspecialchars($row['name']) . '</strong>';
            echo '<div class="price">$' . htmlspecialchars($row['price']) . '</div>';
            echo '<p>' . htmlspecialchars($row['description']) . '</p>';
            echo '<img src="' . htmlspecialchars($row['image_url']) . '" alt="' . htmlspecialchars($row['name']) . '">'; 
            echo '</div>';
          }
        } else {
          echo "No results found";
        }
      ?>
    </div>
    <br>
    <input type="submit" value="Submit">
  </form>
</body>
</html>

<?php
  $conn->close();
?>