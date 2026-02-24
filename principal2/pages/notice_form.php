<?php
include __DIR__ . '/../../db.php';

$id = $_GET['id'] ?? '';

$title = $type = $description = $notice_date = '';

if($id){
    $res = $conn->query("SELECT * FROM notices WHERE id=$id");
    $data = $res->fetch_assoc();

    $title = $data['title'];
    $type = $data['type'];
    $description = $data['description'];
    $notice_date = $data['notice_date'];
}
?>

<div class="page">
<div class="card form-card">

<h2><?= $id ? 'Edit Notice' : 'Create Notice' ?></h2>

<form method="POST" action="index.php?page=notice_save">

    <input type="hidden" name="id" value="<?= $id ?>">

    <label>Title</label>
    <input type="text" name="title" required value="<?= $title ?>">

    <label>Type</label>
    <select name="type" required>
        <option <?= $type=='Event'?'selected':'' ?>>Event</option>
        <option <?= $type=='Exam'?'selected':'' ?>>Exam</option>
        <option <?= $type=='Meeting'?'selected':'' ?>>Meeting</option>
        <option <?= $type=='General'?'selected':'' ?>>General</option>
    </select>

    <label>Date</label>
    <input type="date" name="notice_date" required value="<?= $notice_date ?>">

    <label>Description</label>
    <textarea name="description" rows="4" required><?= $description ?></textarea>

    <br><br>
    <div class="form-actions">
    <button class="btn primary">Save Notice</button>
    <a href="index.php?page=notice" class="btn">Cancel</a>
</div>

    

</form>

</div>
</div>
