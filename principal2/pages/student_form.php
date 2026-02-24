<?php
include __DIR__ . '/../../db.php';

$editData=null;

if(isset($_GET['id'])){
    $id=$_GET['id'];
    $editData=$conn->query("SELECT * FROM students WHERE id=$id")->fetch_assoc();
}
?>

<link rel="stylesheet" href="assets/css/students.css">

<div class="card form-card">
<h2><?= $editData ? "Edit Student" : "Add Student" ?></h2>

<form method="POST" action="pages/student_save.php" class="form">

<input type="hidden" name="id" value="<?= $editData['id'] ?? '' ?>">

<input name="roll" placeholder="Roll No" required value="<?= $editData['roll_no'] ?? '' ?>">
<input name="name" placeholder="Name" required value="<?= $editData['name'] ?? '' ?>">
<input name="class" placeholder="Class" required value="<?= $editData['class'] ?? '' ?>">

<select name="gender">
<option <?= isset($editData)&&$editData['gender']=="Male"?'selected':'' ?>>Male</option>
<option <?= isset($editData)&&$editData['gender']=="Female"?'selected':'' ?>>Female</option>
</select>

<input name="phone" placeholder="Phone" value="<?= $editData['phone'] ?? '' ?>">

<button class="add-btn">Save Student</button>

</form>
</div>
