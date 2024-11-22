<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Thank You Page</title>
</head>
<body>
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

    $currentCart = isset($_COOKIE['currentCart']) ? $_COOKIE['currentCart'] : null;

    if ($currentCart) {
      $cartData = json_decode($currentCart, true); 
      $totalCost = 0;

      if (is_array($cartData)) {
        $dateOrdered = date('Y-m-d H:i:s'); 
        $sqlInsertOrder = "INSERT INTO orders (date_ordered) VALUES ('$dateOrdered')";

        if ($conn->query($sqlInsertOrder) === TRUE) {
          $orderId = $conn->insert_id; 

          foreach ($cartData as $productName => $quantity) {
            // Fetch the product ID based on product name
            $sqlFetchProduct = "SELECT id, price FROM products WHERE name = ?";
            $stmt = $conn->prepare($sqlFetchProduct);
            $stmt->bind_param("s", $productName);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
              $product = $result->fetch_assoc();
              $productId = $product['id'];
              $productPrice = $product['price'];
              $totalCost += $productPrice * $quantity;

              $sqlInsertItem = "INSERT INTO orderItems (order_id, product_id, quantity) VALUES (?, ?, ?)";
              $stmtInsertItem = $conn->prepare($sqlInsertItem);
              $stmtInsertItem->bind_param("iii", $orderId, $productId, $quantity);
              $stmtInsertItem->execute();
            } else {
              echo "Product '$productName' not found in the database.<br>";
            }
          }

          $totalCost = number_format($totalCost, 2);

          $sqlInsertTotalCost = "UPDATE orders SET total_cost = '$totalCost' WHERE id = $orderId";
          $conn->query($sqlInsertTotalCost);

          echo "<h2>Thank you for your order!</h2>";
          echo "<p>Your order has been successfully stored with ID: $orderId.</p>";
          echo "<p><strong>Total Cost:</strong> $" . $totalCost . "</p>";

          $date = new DateTime(); 
          $date->modify('+2 days'); 
          $expectedShipDate = $date->format('Y-m-d');

          echo "<p><strong>Expected Ship Date:</strong> $expectedShipDate</p>";

          // Manually expire cookie to delete
          setcookie('currentCart', '', time() - 3600, '/'); 
          
        } else {
          echo "Error: " . $sqlInsertOrder . "<br>" . $conn->error;
        }
      } else {
        echo "Invalid cart data.";
      }
    } else {
      echo "No cart data found.";
    }
  ?>  
</body>
</html>

<?php
  $conn->close();
?>