<?php include('includes/header.php'); if($_SESSION['role'] !== 'seller') { header("Location: home.php"); exit(); } $seller_id = $_SESSION['user_id']; $msg = "";
if (isset($_POST['broadcast_asset'])) {
    $name = $conn->real_escape_string($_POST['p_name']); $category_id = intval($_POST['p_category_id']); $price = $_POST['p_price']; $stock = intval($_POST['p_stock']); $desc = $conn->real_escape_string($_POST['p_description']);
    
    // Core Local Filesystem Multipart Upload Processing Logic Handler Configuration Map
    $img_filename = "default.png";
    if (isset($_FILES['p_file_uploaded']) && $_FILES['p_file_uploaded']['error'] == 0) {
        $ext = pathinfo($_FILES['p_file_uploaded']['name'], PATHINFO_EXTENSION);
        $img_filename = "img_" . time() . "_" . rand(1000,9999) . "." . $ext;
        move_uploaded_file($_FILES['p_file_uploaded']['tmp_name'], "uploads/" . $img_filename);
    }
    
    $conn->query("INSERT INTO products (seller_id, name, category_id, price, stock, description, image, is_approved) VALUES ('$seller_id', '$name', '$category_id', '$price', '$stock', '$desc', '$img_filename', 'pending')");
    $msg = "Store item successfully dispatched payload to review pipeline configuration channel.";
}
$metrics = $conn->query("SELECT SUM(oi.price * oi.quantity) as total_rev, COUNT(DISTINCT oi.order_id) as dc FROM order_items oi JOIN products p ON oi.product_id = p.id WHERE p.seller_id = '$seller_id'")->fetch_assoc();
?>
<!DOCTYPE html><html><head><title>Merchant Operations Console</title><link rel="stylesheet" href="css/style.css"></head><body>
<?php if($msg) echo "<div class='alert-msg'>$msg</div>"; ?>
<div class="container"><div class="dashboard-layout"><div class="sidebar"><h3>Merchant Ops Navigation</h3><ul><li><a href="#" class="active">Store Ledger Overview</a></li></ul></div>
<div><div class="section-title">Merchant Store Command Workspace</div><div style="display:flex; gap:20px; margin-bottom:35px;">
<div class="premium-card" style="padding:25px; flex:1;"><h5>Gross Store Proceeds Generated</h5><h2 style="color:#db2777; margin-top:5px;">৳ <?php echo number_format($metrics['total_rev']??0,2); ?> BDT</h2></div>
<div class="premium-card" style="padding:25px; flex:1;"><h5>Client Orders Logged Trace</h5><h2 style="color:#4c0519; margin-top:5px;"><?php echo $metrics['dc']??0; ?> Records</h2></div></div>
<div class="premium-card" style="padding:30px; margin-bottom:40px;"><h3>Deploy New Store Product Document Catalog Node</h3>
<form action="dashboard.php" method="POST" enctype="multipart/form-data" style="display:grid; grid-template-columns:1fr 1fr; gap:20px; margin-top:20px;">
<input type="text" name="p_name" placeholder="Item Label Name" required style="border:1px solid #fbcfe8; border-radius:8px; color:#000; padding:12px;"><select name="p_category_id" style="border:1px solid #fbcfe8; border-radius:8px; color:#000; padding:12px;"><?php $cats = $conn->query("SELECT * FROM categories"); while($c = $cats->fetch_assoc()){ echo "<option value='".$c['id']."'>".$c['category_name']."</option>"; } ?></select>
<input type="number" step="0.01" name="p_price" placeholder="Sales Price Valuation (BDT)" required style="border:1px solid #fbcfe8; border-radius:8px; color:#000; padding:12px;"><input type="number" name="p_stock" placeholder="Initial Vault Inventory Stock Volume" required style="border:1px solid #fbcfe8; border-radius:8px; color:#000; padding:12px;">
<div style="grid-column:span 2;"><label style="display:block; margin-bottom:5px; color:#4c0519; font-weight:700;">Upload Local Product Dynamic PNG Image File Node Resource:</label><input type="file" name="p_file_uploaded" accept="image/*" required style="border:1px solid #fbcfe8; border-radius:8px; padding:10px; background:#fff5f8; color:#000; width:100%;"></div>
<textarea name="p_description" placeholder="Provide system documentation specification descriptions..." required style="grid-column:span 2; height:120px; border:1px solid #fbcfe8; border-radius:8px; color:#000; padding:12px; font-family:inherit;"></textarea>
<button type="submit" name="broadcast_asset" class="btn btn-primary" style="grid-column:span 2; justify-content:center; border-radius:8px; padding:14px;">Publish Catalog Asset Stream</button></form></div>
<div class="section-title">My Active Store Inventory Array Logs</div><table class="table-style"><thead><tr><th>ID Code</th><th>Snapshot Link Preview</th><th>Asset Name</th><th>Price Rate</th><th>Stock Allocation</th><th>Review State Flag</th></tr></thead><tbody>
<?php $res = $conn->query("SELECT * FROM products WHERE seller_id='$seller_id'"); while($p = $res->fetch_assoc()): ?>
<tr><td>#<?php echo $p['id']; ?></td><td><img src="uploads/<?php echo htmlspecialchars($p['image']); ?>" style="width:50px; height:50px; object-fit:cover; border-radius:6px; border:1px solid #fbcfe8;"></td><td><strong><?php echo htmlspecialchars($p['name']); ?></strong></td><td>৳ <?php echo $p['price']; ?></td><td><?php echo $p['stock']; ?> units</td><td><span style="color:<?php echo $p['is_approved'] == 'approved' ? '#166534' : '#991b1b'; ?>; font-weight:800;"><?php echo strtoupper($p['is_approved']); ?></span></td></tr><?php endwhile; ?>
</tbody></table></div></div></div></body></html>