<?php
session_start();
include "../db.php";

if(!isset($_SESSION['role']) || $_SESSION['role'] != 'teacher'){
    header("Location: ../auth/login.php");
    exit();
}

$email = $_SESSION['email'] ?? 'teacher@email.com';

/* ================= INSERT NOTICE ================= */
if(isset($_POST['add_notice'])){
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $date = $_POST['date'];
    $description = mysqli_real_escape_string($conn, $_POST['description']);

    mysqli_query($conn,"INSERT INTO notices 
        (title, description, category, notice_date)
        VALUES ('$title','$description','$category','$date')");

    header("Location: notices.php");
    exit();
}

/* ================= DELETE NOTICE ================= */
if(isset($_GET['delete'])){
    $id = intval($_GET['delete']);
    mysqli_query($conn,"DELETE FROM notices WHERE id=$id");
    header("Location: notices.php");
    exit();
}

/* ================= UPDATE NOTICE ================= */
if(isset($_POST['update_notice'])){
    $id = intval($_POST['id']);
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $date = $_POST['date'];
    $description = mysqli_real_escape_string($conn, $_POST['description']);

    mysqli_query($conn,"UPDATE notices SET
        title='$title',
        description='$description',
        category='$category',
        notice_date='$date'
        WHERE id=$id");

    header("Location: notices.php");
    exit();
}

/* ================= EDIT FETCH ================= */
$editData = null;
if(isset($_GET['edit'])){
    $id = intval($_GET['edit']);
    $editQuery = mysqli_query($conn,"SELECT * FROM notices WHERE id=$id");
    $editData = mysqli_fetch_assoc($editQuery);
}

/* ================= FETCH ALL ================= */
$result = mysqli_query($conn,"SELECT * FROM notices ORDER BY notice_date DESC");
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Notices | Bright Future School</title>

<link rel="stylesheet" href="./includes/sidebar-topbar.css">
<link rel="stylesheet" href="notices.css">
</head>

<body>

<?php include "./includes/sidebar-topbar.php"; ?>

<div class="main">

<div class="page-header">
<div>
<h1>Notices Management</h1>
<p>Create and manage school notices</p>
</div>
<button class="btn-primary" onclick="openModal()">+ Create Notice</button>
</div>

<div class="notice-list">

<?php if(mysqli_num_rows($result)>0){ ?>
<?php while($row=mysqli_fetch_assoc($result)){

$badgeClass="badge-event";
if($row['category']=="Exam") $badgeClass="badge-exam";
if($row['category']=="Meeting") $badgeClass="badge-meeting";
if($row['category']=="Holiday") $badgeClass="badge-holiday";
if($row['category']=="Acadamic") $badgeClass="badge-acadamic";
if($row['category']=="Fees") $badgeClass="badge-fees";

?>

<div class="notice-card">

<div class="notice-top">
<span class="badge <?php echo $badgeClass; ?>">
<?php echo $row['category']; ?>
</span>

<div class="action-btns">
<a href="?edit=<?php echo $row['id']; ?>" class="edit-btn">Edit</a>
<a href="?delete=<?php echo $row['id']; ?>"
onclick="return confirm('Delete this notice?')"
class="delete-btn">Delete</a>
</div>
</div>

<h3><?php echo $row['title']; ?></h3>
<p><?php echo $row['description']; ?></p>

<div class="date">
<?php echo date("d M Y",strtotime($row['notice_date'])); ?>
</div>

</div>

<?php } ?>
<?php } else { ?>
<p>No Notices Available</p>
<?php } ?>

</div>
</div>

<!-- ===== Modal ===== -->
<div class="modal" id="noticeModal">

<form method="POST" class="modal-content">

<?php if($editData){ ?>
<input type="hidden" name="id" value="<?php echo $editData['id']; ?>">
<?php } ?>

<label>Title *</label>
<input type="text" name="title"
value="<?php echo $editData['title'] ?? ''; ?>" required>

<div class="row">
<div>
<label>Category *</label>
<select name="category" required>
<option <?php if(($editData['category']??'')=="Event") echo "selected"; ?>>Event</option>
<option <?php if(($editData['category']??'')=="Exam") echo "selected"; ?>>Exam</option>
<option <?php if(($editData['category']??'')=="Meeting") echo "selected"; ?>>Meeting</option>
<option <?php if(($editData['category']??'')=="Holiday") echo "selected"; ?>>Holiday</option>
<option <?php if(($editData['category']??'')=="Acadamic") echo "selected"; ?>>Academic</option>
<option <?php if(($editData['category']??'')=="Fees") echo "selected"; ?>>Fees</option>

</select>
</div>

<div>
<label>Date *</label>
<input type="date" name="date"
value="<?php echo $editData['notice_date'] ?? ''; ?>" required>
</div>
</div>

<label>Description *</label>
<textarea name="description" required><?php echo $editData['description'] ?? ''; ?></textarea>

<div class="modal-footer">
<button type="button" class="btn-outline" onclick="closeModal()">Cancel</button>

<button type="submit"
name="<?php echo $editData ? 'update_notice' : 'add_notice'; ?>"
class="btn-primary">
<?php echo $editData ? 'Update Notice' : 'Publish Notice'; ?>
</button>
</div>

</form>
</div>

<script>
function openModal(){
document.getElementById('noticeModal').style.display='flex';
}
function closeModal(){
document.getElementById('noticeModal').style.display='none';
}

<?php if($editData){ ?>
document.getElementById('noticeModal').style.display='flex';
<?php } ?>
</script>

</body>
</html>
