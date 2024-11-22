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
    src="orders_page_script.js"
    defer
  ></script>
  <title>Orders Page</title>
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
            class="html-link disabled-link"
            href="http://localhost:8080/orders_page.php"
          >
            <img width="30" height="30" src="https://img.icons8.com/ios-glyphs/30/receipt.png" alt="receipt"/>
            </a>
        </li>
      </ul>
    </div>
  </div>
  <div class="main-page-container">
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

    $sql = "
    SELECT o.id AS order_id, o.date_ordered, o.total_cost, 
           oi.quantity, p.name AS product_name, p.price AS product_price
    FROM orders o
    JOIN orderItems oi ON o.id = oi.order_id
    JOIN products p ON oi.product_id = p.id
    ORDER BY o.date_ordered DESC
    ";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
      $currentOrderId = null;
      while ($row = $result->fetch_assoc()) {
        if ($currentOrderId != $row['order_id']) {
          // close table for previous order
          if ($currentOrderId !== null) {
            echo "</table><br>";
          }

          $currentOrderId = $row['order_id'];
          $dateOrdered = $row['date_ordered'];
          $totalCost = number_format($row['total_cost'], 2);

          echo "<h3>Order ID: $currentOrderId</h3>";
          echo "<p><strong>Date Ordered:</strong> $dateOrdered</p>";
          echo "<p><strong>Total Cost:</strong> $$totalCost</p>";
          echo "<table border='1'>";
          echo "<tr><th>Item</th><th>Quantity</th><th>Cost</th></tr>";
        }

        $productName = $row['product_name'];
        $quantity = $row['quantity'];
        $itemCost = number_format($row['product_price'] * $quantity, 2);

        echo "<tr>
                <td>$productName</td>
                <td>$quantity</td>
                <td>$$itemCost</td>
              </tr>";
      }
      echo "</table>";
    } else {
      echo "No orders found.";
    }
  ?>
  </div>
</body>
</html>

<?php
  $conn->close();
?>
