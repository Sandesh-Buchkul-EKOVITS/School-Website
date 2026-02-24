<?php
include "../db.php";

$search = $_GET['search'] ?? '';
$statusFilter = $_GET['status'] ?? '';

$sql = "SELECT * FROM enquiries WHERE 1";

if($search != ''){
    $sql .= " AND (name LIKE '%$search%' OR phone LIKE '%$search%' OR email LIKE '%$search%')";
}

if($statusFilter != '' && $statusFilter != 'All'){
    $sql .= " AND status='$statusFilter'";
}

$sql .= " ORDER BY id DESC";

$result = $conn->query($sql);
?>

<div class="page-header">
    <h2>Enquiry Leads</h2>
    <p>Manage admission enquiries and leads</p>
</div>

<!-- 🔎 FILTER BAR -->
<form method="GET">
<div class="filter-bar">

    <input type="hidden" name="page" value="enquiries">

    <input type="text" name="search"
        placeholder="Search by name, phone, or email..."
        value="<?php echo $search; ?>">

    <select name="status" onchange="this.form.submit()">
        <option value="All">All Status</option>
        <option value="New" <?= $statusFilter=='New'?'selected':'' ?>>New</option>
        <option value="Contacted" <?= $statusFilter=='Contacted'?'selected':'' ?>>Contacted</option>
        <option value="Enrolled" <?= $statusFilter=='Enrolled'?'selected':'' ?>>Enrolled</option>
        <option value="Closed" <?= $statusFilter=='Closed'?'selected':'' ?>>Closed</option>
    </select>

</div>
</form>

<?php while($row = $result->fetch_assoc()) { ?>

<div class="enquiry-card">

    <div class="enquiry-top">

        <div class="left">
            <h3>
                <?php echo $row['name']; ?>
                <span class="badge status-<?php echo $row['status']; ?>">
                    <?php echo $row['status']; ?>
                </span>
            </h3>

            <p>📞 <?php echo $row['phone']; ?></p>
            <p>✉ <?php echo $row['email']; ?></p>
            <p><?php echo $row['message']; ?></p>
            <small>Received on: <?php echo date("Y-m-d", strtotime($row['created_at'])); ?></small>
        </div>

        <div class="right">
            <form method="POST" action="update_status.php">
                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">

                <select name="status" class="status-dropdown" onchange="this.form.submit()">
                    <option value="New" <?= $row['status']=="New"?'selected':'' ?>>New</option>
                    <option value="Contacted" <?= $row['status']=="Contacted"?'selected':'' ?>>Contacted</option>
                    <option value="Enrolled" <?= $row['status']=="Enrolled"?'selected':'' ?>>Enrolled</option>
                    <option value="Closed" <?= $row['status']=="Closed"?'selected':'' ?>>Closed</option>
                </select>
            </form>
        </div>

    </div>

</div>

<?php } ?>