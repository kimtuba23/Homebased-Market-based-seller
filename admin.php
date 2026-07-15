<?php include('includes/header.php'); if($_SESSION['role'] !== 'admin') { header("Location: home.php"); exit(); }
$msg = "";
if(isset($_POST['create_new_category_node'])) {
    $cat_name = $conn->real_escape_string($_POST['new_cat_string']);
    $conn->query("INSERT INTO categories (category_name) VALUES ('$cat_name')");
    $msg = "New dynamic marketplace category asset layer successfully mapped!";
}
if(isset($_GET['approve_prod'])) { $p_id = $conn->real_escape_string($_GET['approve_prod']); $conn->query("UPDATE products SET is_approved='approved' WHERE id='$p_id'"); header("Location: admin.php"); exit(); }
if(isset($_GET['reject_prod'])) { $p_id = $conn->real_escape_string($_GET['reject_prod']); $conn->query("UPDATE products SET is_approved='rejected' WHERE id='$p_id'"); header("Location: admin.php"); exit(); }
if(isset($_GET['toggle_user_status'])) { $u_id = $conn->real_escape_string($_GET['toggle_user_status']); $u_meta = $conn->query("SELECT status FROM users WHERE id='$u_id'")->fetch_assoc(); $ns = ($u_meta['status'] == 'active') ? 'suspended' : 'active'; $conn->query("UPDATE users SET status='$ns' WHERE id='".$u_id."'"); header("Location: admin.php"); exit(); }
$global_users = $conn->query("SELECT COUNT(id) as c FROM users")->fetch_assoc()['c'];
$global_prods = $conn->query("SELECT COUNT(id) as c FROM products")->fetch_assoc()['c'];
$comm_query = $conn->query("SELECT SUM(commission_earned) as total_c, SUM(total_amount) as total_rev FROM orders")->fetch_assoc();
?>
<!DOCTYPE html><html><head><title>System Core Platform Operations Console Director</title><link rel="stylesheet" href="css/style.css"></head><body>
<?php if($msg) echo "<div class='alert-msg'>$msg</div>"; ?>
<div class="container"><div class="section-title">Platform Operations System Administration Hub Base Terminal</div><div style="display:flex; gap:20px; margin-bottom:35px;">
<div class="premium-card" style="padding:25px; flex:1;"><h5>Registered Network User Profiles</h5><h2><?php echo $global_users; ?> Nodes</h2></div>
<div class="premium-card" style="padding:25px; flex:1;"><h5>Ecommerce Catalog SKU Vault Data</h5><h2><?php echo $global_prods; ?> SKUs</h2></div>
<div class="premium-card" style="padding:25px; flex:1;"><h5>Market Capitalization Gross Turnover</h5><h2>৳ <?php echo number_format($comm_query['total_rev']??0,2); ?></h2></div>
<div class="premium-card" style="padding:25px; flex:1; border-top:5px solid #db2777;"><h5>Corporate Revenue Profits Strategy (5% Margin Cut)</h5><h2 style="color:#db2777;">৳ <?php echo number_format($comm_query['total_c']??0,2); ?> BDT</h2></div></div>
<div style="display:grid; grid-template-columns:350px 1fr; gap:30px; margin-bottom:40px;">
<div class="premium-card" style="padding:25px;"><h3>Create Dynamic Marketplace Category Entry</h3><form action="admin.php" method="POST" style="margin-top:15px;"><input type="text" name="new_cat_string" placeholder="Category Name String Entry" required style="border:1px solid #fbcfe8; border-radius:8px; padding:12px; color:#000; width:100%; margin-bottom:15px;"><button type="submit" name="create_new_category_node" class="btn btn-primary" style="width:100%; justify-content:center; border-radius:8px; padding:12px;">Commit Category Matrix</button></form></div>
<div class="premium-card" style="padding:25px;"><h3>Pending Micro Store Product Distribution Applications</h3><table class="table-style"><thead><tr><th>Merchant Handle</th><th>Product Name Label</th><th>Target Price Value</th><th>Admin Authorization Operations Matrix</th></tr></thead><tbody>
<?php $mod = $conn->query("SELECT p.*, u.username FROM products p JOIN users u ON p.seller_id = u.id WHERE p.is_approved='pending'");
if($mod->num_rows > 0){ while($p_row = $mod->fetch_assoc()): ?>
<tr><td><?php echo htmlspecialchars($p_row['username']); ?></td><td><strong><?php echo htmlspecialchars($p_row['name']); ?></strong></td><td>৳ <?php echo $p_row['price']; ?></td><td><a href="admin.php?approve_prod=<?php echo $p_row['id']; ?>" style="color:#166534; font-weight:800; text-decoration:none; margin-right:15px;">[Allow/Approve Entry]</a> <a href="admin.php?reject_prod=<?php echo $p_row['id']; ?>" style="color:#991b1b; font-weight:800; text-decoration:none;">[Deny/Purge Payload]</a></td></tr><?php endwhile; } else { echo "<tr><td colspan='4' style='text-align:center;'>Review validation pipeline execution buffer queue is empty.</td></tr>"; } ?>
</tbody></table></div></div><div style="display:grid; grid-template-columns:1fr 1fr; gap:30px;"><div class="premium-card" style="padding:25px;"><h3>User Authorization Access Security Protocols Management</h3><table class="table-style"><thead><tr><th>Username</th><th>Role</th><th>Network Access State</th><th>System Controls</th></tr></thead><tbody>
<?php $ul = $conn->query("SELECT * FROM users WHERE id != '1'"); while($u = $ul->fetch_assoc()): ?>
<tr><td><?php echo htmlspecialchars($u['username']); ?></td><td><?php echo strtoupper($u['role']); ?></td><td><strong style="color:<?php echo $u['status'] == 'active' ? '#166534' : '#991b1b'; ?>;"><?php echo strtoupper($u['status']); ?></strong></td><td><a href="admin.php?toggle_user_status=<?php echo $u['id']; ?>" style="color:#db2777; text-decoration:none; font-weight:700;">[Toggle Ban Protocol/Suspend Node Access]</a></td></tr><?php endwhile; ?>
</tbody></table></div><div class="premium-card" style="padding:25px;"><h3>Ecosystem Ledger Financial Invoices Historical Trace</h3><table class="table-style"><thead><tr><th>Invoice Log Target Ref</th><th>Gross Amount BDT Valuation</th><th>Payment Channel Trace Data</th></tr></thead><tbody>
<?php $ol = $conn->query("SELECT * FROM orders ORDER BY id DESC"); if($ol->num_rows > 0){ while($o = $ol->fetch_assoc()): ?>
<tr><td>#SELLNEST-SYSTEM-INVOICE-00<?php echo $o['id']; ?></td><td><strong>৳ <?php echo $o['total_amount']; ?> BDT</strong></td><td><?php echo $o['transaction_id'] ?? 'Cash On Delivery Operations Stream Record'; ?></td></tr><?php endwhile; } else { echo "<tr><td colspan='3' style='text-align:center;'>Marketplace logs transaction ledger database matrix empty.</td></tr>"; } ?>
</tbody></table></div></div></div></body></html>