<h2>Customer Orders</h2>
<table>
    <tr>
        <th>Order ID</th>
        <th>Client</th>
        <th>Order items</th>
        <th>Total t Points</th>
        <th>Total paid</th>
        <th>Status</th>
    </tr>
    <?php
    include_once BASE_PATH . '/controllers/ToyOrderController.php';

    $orders = ToyOrderController::getAllOrders();
    foreach ($orders as $order) {
        echo "<tr>";
        echo "<td>" . $order->getOrderId() . "</td>";
        echo "<td>" . $order->getClientDetails()->get('firstName'). " ".$order->getClientDetails()->get('lastName')."</td>";
        echo "<td>";
            // Fetch order items
            $orderItems = $order->getOrderItems(); // This is an array
            $totalTpoints = 0;
            foreach ($orderItems as $item) {
                echo "Toy Name: " . $item->getToy()->get('name') . "<br>";
                $totalTpoints += $item->getToy()->get('tPoints');
            }
        echo "</td>";
        echo "<td>".$totalTpoints."</td>";

    }

    ?>
</table>