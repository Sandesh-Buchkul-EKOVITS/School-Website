<?php
include "../db.php";
include "./includes/header.php"; 

$category = isset($_GET['category']) ? $_GET['category'] : 'All';

if ($category == "All") {
    $sql = "SELECT * FROM notices ORDER BY notice_date DESC";
} else {
    $sql = "SELECT * FROM notices 
            WHERE category='$category' 
            ORDER BY notice_date DESC";
}

$result = mysqli_query($conn, $sql);
?>
<link rel="stylesheet" href="../css/notice-board.css">
<div class="header-notice">
    <h1>Notice Board</h1>
    <p>Stay updated with latest announcements and events</p>
</div>

<div class="container">

    <!-- Filter Section -->
    <div class="filter-box">
        <h3>Filter by Category</h3>

        <div class="filters">
            <?php
            $categories = ["All","Event","Exam","Meeting","Holiday","Academic","Fee"];

            foreach($categories as $cat){
                $active = ($category == $cat) ? "active" : "";
                echo "<a href='?category=$cat' class='btn $active'>$cat</a>";
            }
            ?>
        </div>
    </div>

    <!-- Notice List -->
    <?php while($row = mysqli_fetch_assoc($result)) { ?>
        <div class="notice-card">

            <div class="top">
                <span class="badge <?php echo strtolower($row['category']); ?>">
                    <?php echo $row['category']; ?>
                </span>

                <span class="date">
                    <?php echo $row['notice_date']; ?>
                </span>
            </div>

            <h2><?php echo $row['title']; ?></h2>
            <p><?php echo $row['description']; ?></p>

        </div>
    <?php } ?>

</div>
<?php include "./includes/footer.php"; ?>