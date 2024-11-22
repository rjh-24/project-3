<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link
    href="style.css"
    rel="stylesheet"
  />
  <script
    src="product_page_script.js"
    defer
  ></script>
  <title>Products Page</title>
</head>
<body>
  <div class="navbar-container">
    <div class="flex-empty"></div>
    <h1 class="flex-middle">Art Of Eight Gear</h1>
    <div class="nav-links-container flex-right">
      <ul>
        <li>
          <a
            class="html-link disabled-link"
            href="http://localhost:8080/products_page.php"
          >
            <img width="30" height="30" src="https://img.icons8.com/ios/50/small-business.png" alt="small-business"/>
          </a>
        </li>
        <li>
          <a
            class="html-link"
            href="http://localhost:8080/cart_page.php"
          >
            <div class="cart-container">
              <img width="30" height="30" class="cart-icon" src="https://img.icons8.com/ios-glyphs/30/shopping-cart--v1.png" alt="shopping-cart--v1"/ >
              <div class="cart-count" id="cartCount"></div>
            </div>
          </a>
        </li>
        <li>
          <a
            class="html-link"
            href="http://localhost:8080/orders_page.php"
          >
            <img width="30" height="30" src="https://img.icons8.com/ios-glyphs/30/receipt.png" alt="receipt"/>
            </a>
        </li>
      </ul>
    </div>
  </div>
  <div class="main-page-container product-container">
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