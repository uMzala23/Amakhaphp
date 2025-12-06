<?php
include "db.php";

if (isset($_GET["order"]) && isset($_GET["email"])) {
    $order = $_GET["order"];
    $email = $_GET["email"];

    $query = $db->prepare("SELECT * FROM orders WHERE order_number=? AND email=?");
    $query->execute([$order, $email]);
    $orderData = $query->fetch(PDO::FETCH_ASSOC);

    if (!$orderData) {
        die("Order not found");
    }

    $track = $db->prepare("SELECT * FROM delivery_tracking WHERE order_id=? ORDER BY id DESC LIMIT 1");
    $track->execute([$orderData['id']]);
    $location = $track->fetch(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Track My Order</title>
</head>
<body>
<h2>Order Status: <?= $orderData['status'] ?></h2>

<?php if ($location): ?>
<h3>Live Location</h3>
<iframe
  width="100%" height="400"
  src="https://maps.google.com/maps?q=<?= $location['latitude'] ?>,<?= $location['longitude'] ?>&z=15&output=embed">
</iframe>
<?php endif; ?>

</body>
</html>
