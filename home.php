<?php include('includes/header.php'); ?>
<!DOCTYPE html><html><head><title>SellNest Core Market Hub</title><link rel="stylesheet" href="css/style.css"></head><body>
<div class="container">
    <div class="role-toggle-bar">
        <div>💡 <strong>Ecosystem Architecture Matrix Info:</strong> Current context execution identity is set to: <span style="color:#db2777; font-weight:800; text-transform:uppercase;"><?php echo $_SESSION['role']; ?></span></div>
        <div>No secondary registration form is required. Simply switch roles dynamically from the toggle control above!</div>
    </div>
    <div class="section-title">Explore Global Categories Grid Marketplace</div>
    <div class="category-grid">
        <?php $categories = $conn->query("SELECT * FROM categories");
        while($cat = $categories->fetch_assoc()): ?>
            <a href="category-products.php?id=<?php echo $cat['id']; ?>" class="category-card"><div>🌸 <?php echo htmlspecialchars($cat['category_name']); ?></div></a>
        <?php endwhile; ?>
    </div>
    <div class="section-title" style="margin-top:60px;">Top Merchant Store Volume Leaders</div>
    <table class="table-style"><thead><tr><th>Merchant Name Profile ID</th><th>Total Sales Volume Snapshots Distributed</th></tr></thead><tbody>
    <?php $top = $conn->query("SELECT u.username, COUNT(oi.id) as sv FROM users u JOIN products p ON u.id=p.seller_id JOIN order_items oi ON p.id=oi.product_id GROUP BY u.id ORDER BY sv DESC LIMIT 3");
    if($top && $top->num_rows > 0){ while($r=$top->fetch_assoc()){ echo "<tr><td>👑 ".htmlspecialchars($r['username'])."</td><td><strong>".$r['sv']." Orders Settled</strong></td></tr>"; } } else { echo "<tr><td colspan='2' style='text-align:center;'>Waiting for marketplace transactions logs data streams.</td></tr>"; } ?>
    </tbody></table>
</div></body></html>