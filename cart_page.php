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
      src="cart_page_script.js"
      defer
    ></script>
  <title>Cart Page</title>
</head>
<body>
  <div class="navbar-container">
    <div class="flex-empty"></div>
    <h1 class="flex-middle">Art Of Eight Gear</h1>
    <div class="nav-links-container flex-right">
      <ul>
        <li>
          <a
            class="html-link"
            href="http://localhost:8080/products_page.php"
          >
            <img width="30" height="30" src="https://img.icons8.com/ios/50/small-business.png" alt="small-business"/>
          </a>
        </li>
        <li>
          <a
            class="html-link disabled-link"
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
            href="https://rjh-24.github.io/cs-120-project-1/contact.html"
            target="_blank"
          >
            <img width="30" height="30" src="https://img.icons8.com/ios-glyphs/30/receipt.png" alt="receipt"/>
            </a>
        </li>
      </ul>
    </div>
  </div>
  <div class="product-container">
  </div>
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

      $cart = isset($_COOKIE['currentCart']) ? $_COOKIE['currentCart'] : null;

      $totalCost = 0;

      if ($cart) {
        $cartData = json_decode($cart, true);

        if ($cartData !== null) {
          $productNames = array_keys($cartData);

          $placeholders = implode(',', array_fill(0, count($productNames), '?'));

          $sql = "SELECT name, price FROM products WHERE name IN ($placeholders)";

          // Prepare the statement and bind
          if ($stmt = $conn->prepare($sql)) {
            $types = str_repeat('s', count($productNames)); // 's' for string
            $stmt->bind_param($types, ...$productNames);

            $stmt->execute();
            $result = $stmt->get_result();

            // Loop through the result and calculate the total cost
            while ($row = $result->fetch_assoc()) {
              $productName = $row['name'];
              $price = $row['price'];
              $quantity = $cartData[$productName];
              $totalCost += $price * $quantity;

              echo "Product: " . $row['name'] . " | Price: " . $row['price'] . " | Quantity: " . $quantity . " | Total of " . $row['price'] . " * " . $quantity . " = $" . number_format($row['price'] * $quantity, 2) . "<br>";
            }

            $stmt->close();
          } else {
            echo "Error preparing the SQL query.";
          }
        } else {
          echo "Failed to decode cart JSON data.";
        }
      } else {
        echo "No cart data found.";
      }
     echo "Total Cost: $" . number_format($totalCost, 2);
  ?>
</body>
</html>

<?php
  $conn->close();
?>