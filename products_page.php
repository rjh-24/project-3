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

  $sql = "SELECT * FROM products ORDER BY name ASC";
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
      box-sizing: border-box;
    }

    .product-item img {
      max-width: 100%;
      height: auto;
      border-radius: 5px;
    }

    .product-item .details {
      display: flex;
      justify-content: center;
      align-items: center;
      margin: 10px 0;
    }

    .product-item .details > * {
      margin: 0 5px;
    }

    .product-item select {
      margin-top: 5px;
    }

    .product-item .actions {
      display: flex;
      justify-content: space-between;
      margin-top: 10px;
    }

    .product-item button {
      flex: 1;
      margin: 0 5px;
      padding: 8px;
      background-color: green;
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }

    .product-item button.more {
      background-color: blue;
    }

    .product-item button:hover {
      opacity: 0.9;
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
          echo '<img src="' . htmlspecialchars($row['image_url']) . '" alt="' . htmlspecialchars($row['name']) . '">';
          echo '<div class="details">';
          echo '<strong>' . htmlspecialchars($row['name']) . '</strong><br>';
          echo '<span>$' . htmlspecialchars($row['price']) . '</span><br>'; 
          echo '<select name="quantity">';
          for ($i = 1; $i <= 5; $i++) {
            echo '<option value="' . $i . '">' . $i . '</option>';
          }
          echo '</select>';
          echo '</div>';
          echo '<div class="actions">';
          echo '<button type="button" class="add-to-cart">Add to Cart</button>';
          echo '<button type="button" class="more">More</button>';
          echo '</div>';
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