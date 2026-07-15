<?php include('includes/header.php'); $err = "";
if (isset($_GET['remove_item'])) { $cart_id = $conn->real_escape_string($_GET['remove_item']); $conn->query("DELETE FROM cart WHERE id='$cart_id' AND buyer_id='$b_id'"); header("Location: cart.php"); exit(); }
if (isset($_POST['process_checkout'])) {
    $pay_method = $_POST['payment_gateway_method']; $txn_id = !empty($_POST['txn_id_input']) ? $conn->real_escape_string($_POST['txn_id_input']) : NULL;
    $cart_items = $conn->query("SELECT c.quantity, p.price, p.id as prod_id, p.stock FROM cart c JOIN products p ON c.product_id = p.id WHERE c.buyer_id='$b_id'");
    if ($cart_items->num_rows > 0) {
        $total_bill = 0; $items = []; $violation = false;
        while($item = $cart_items->fetch_assoc()) { if($item['quantity'] > $item['stock']) { $violation = true; $err = "Requested transaction units overflow stack tracking metrics data parameters."; break; } $total_bill += ($item['price'] * $item['quantity']); $items[] = $item; }
        if(!$violation) {
            $commission = $total_bill * 0.05; $p_status = ($pay_method == 'COD') ? 'Pending' : 'Completed';
            $conn->query("INSERT INTO orders (buyer_id, total_amount, commission_earned, payment_method, transaction_id, payment_status) VALUES ('$b_id', '$total_bill', '$commission', '$pay_method', '$txn_id', '$p_status')");
            $order_id = $conn->insert_id;
            foreach($items as $v) { $p_id = $v['prod_id']; $qty = $v['quantity']; $prc = $v['price']; $conn->query("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES ('$order_id', '$p_id', '$qty', '$prc')"); $conn->query("UPDATE products SET stock = stock - $qty WHERE id='$p_id'"); }
            $conn->query("DELETE FROM cart WHERE buyer_id='$b_id'"); header("Location: order-success.php?id=".$order_id); exit();
        }
    } else { $err = "Cart tracking selection buffer contains no entities."; }
}
?>
<!DOCTYPE html><html><head><title>Secure Order Cart Ledger</title><link rel="stylesheet" href="css/style.css"></head><body>
<?php if($err) echo "<div class='error-msg'>$err</div>"; ?>
<div class="container"><div class="section-title">Selected Pipeline Order Item Logs</div><div class="payment-wrapper">
<div><table class="table-style"><thead><tr><th>Ecosystem Item Identity</th><th>Unit Rate Valuation</th><th>Requested Units</th><th>Subtotal Accumulation</th><th>Control Options</th></tr></thead><tbody>
<?php $subtotal = 0; $res = $conn->query("SELECT c.id as cid, c.quantity, p.name, p.price FROM cart c JOIN products p ON c.product_id = p.id WHERE c.buyer_id = '$b_id'");
if($res->num_rows > 0){ while($r = $res->fetch_assoc()): $lt = $r['price'] * $r['quantity']; $subtotal += $lt; ?>
<tr><td><strong><?php echo htmlspecialchars($r['name']); ?></strong></td><td>৳ <?php echo $r['price']; ?> BDT</td><td><?php echo $r['quantity']; ?> units</td><td>৳ <?php echo $lt; ?> BDT</td><td><a href="cart.php?remove_item=<?php echo $r['cid']; ?>" style="color:#e11d48; font-weight:800; text-decoration:none;">Purge/Drop</a></td></tr><?php endwhile; } else { echo "<tr><td colspan='5' style='text-align:center;'>Shopping registration buffer block contains zero items data lines.</td></tr>"; } ?>
</tbody></table></div>
<div class="premium-card" style="padding:30px; height:fit-content;"><h3>Secure Settlement Statement</h3><div style="margin:25px 0; display:flex; justify-content:space-between; font-size:16px;"><span>Aggregate Cost Matrix Total:</span><strong style="color:#db2777; font-size:22px;">৳ <?php echo number_format($subtotal,2); ?></strong></div>
<?php if($subtotal > 0): ?><form action="cart.php" method="POST">
<div style="margin-bottom:15px; color:#4c0519; font-weight:700;">Select Billing Gateway Protocol Channel:</div>
<label><input type="radio" name="payment_gateway_method" value="bKash" checked> bKash Instant Node</label><br><label><input type="radio" name="payment_gateway_method" value="Nagad"> Nagad API Secure Hub</label><br><label><input type="radio" name="payment_gateway_method" value="COD"> Hand Cash On Delivery Systems</label><br>
<input type="text" name="txn_id_input" placeholder="Enter Channel Transaction Reference ID Key String" style="width:100%; padding:12px; margin:15px 0; border:1px solid #fbcfe8; border-radius:8px; color:#000;">
<button type="submit" name="process_checkout" class="btn btn-primary" style="width:100%; justify-content:center; border-radius:8px; padding:14px;">Verify & Commit Financial Lock Settlement</button></form><?php endif; ?></div></div></div></body></html>