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
  <link
      href="product_page_styles.css"
      rel="stylesheet"
    />
    <script
      src="script.js"
      defer
    ></script>
  <title>Select Products</title>
</head>
<body>

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
          echo '<div class="quantity-container">';
          echo '<p>Quantity: </p>';
          echo '<select class="quantity-selector" name="quantity">';
          for ($i = 1; $i <= 5; $i++) {
            echo '<option value="' . $i . '">' . $i . '</option>';
          }
          echo '</select>';
          echo '</div>';
          echo '</div>';
          echo '<div class="actions">';
          echo '<button type="button" class="add-to-cart" data-name="' . htmlspecialchars($row['name']) . '">Add To Cart</button>';
          echo '<button type="button" class="more" data-name="' . htmlspecialchars($row['name']) . '" data-description="' . htmlspecialchars($row['description']) . '">More</button>';
          echo '</div>';
          echo '</div>';
        }
      } else {
        echo "No results found";
      }
    ?>
    </div>
    <br>
  </form>
  <div id="product-modal" class="modal">
    <div class="modal-content">
      <span class="close">&times;</span>
      <h2 id="modal-title"></h2>
      <p id="modal-description"></p>
    </div>
  </div>
</body>
</html>

<?php
  $conn->close();
?>