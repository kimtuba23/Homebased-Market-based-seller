<?php include('includes/header.php'); $cat_id = isset($_GET['id']) ? intval($_GET['id']) : 1;
$cat_meta = $conn->query("SELECT category_name FROM categories WHERE id='$cat_id'")->fetch_assoc(); ?>
<!DOCTYPE html><html><head><title>Market Category Catalog</title><link rel="stylesheet" href="css/style.css"></head><body>
<div class="container">
    <div class="section-title">Active Inventory Index: <?php echo htmlspecialchars($cat_meta['category_name'] ?? 'Unmapped'); ?></div>
    <div class="product-grid">
        <?php $sql = "SELECT * FROM products WHERE category_id='$cat_id' AND is_approved='approved'"; $res = $conn->query($sql);
        if ($res->num_rows > 0){ while($row = $res->fetch_assoc()): ?>
            <a href="product-details.php?id=<?php echo $row['id']; ?>" class="product-card">
                <img src="uploads/<?php echo htmlspecialchars($row['image']); ?>" alt="Product">
                <div class="product-info">
                    <div class="product-title"><?php echo htmlspecialchars($row['name']); ?></div>
                    <div class="product-price">৳ <?php echo number_format($row['price'], 2); ?></div>
                    <div style="font-size:13px; color:#9d174d; margin-bottom:12px;">Remaining Units: <?php echo $row['stock']; ?></div>
                    <span class="btn btn-primary" style="width:100%; justify-content:center;">View Complete Details</span>
                </div>
            </a>
        <?php endwhile; } else { echo "<div class='premium-card' style='padding:40px; text-align:center; grid-column:1/-1; color:#9d174d;'>No verified products in this catalog node yet.</div>"; } ?>
    </div>
</div></body></html>