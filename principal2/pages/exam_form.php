<?php
include __DIR__ . '/../../db.php';

$id = $_GET['id'] ?? '';
$data = [
    'exam_name'=>'','class'=>'','subject'=>'',
    'exam_date'=>'','exam_time'=>'',
    'max_marks'=>'','duration'=>''
];

if($id){
    $res = $conn->query("SELECT * FROM exams WHERE id=$id");
    $data = $res->fetch_assoc();
}
?>

<div class="modal show">
<div class="modal-content">

<h3><?= $id ? "Edit Exam" : "Schedule Exam" ?></h3>

<form method="POST" action="index.php?page=exam_save">

<input type="hidden" name="id" value="<?= $id ?>">

<input type="text" name="exam_name" placeholder="Exam Name" value="<?= $data['exam_name'] ?>" required>
<input type="text" name="class" placeholder="Class" value="<?= $data['class'] ?>" required>
<input type="text" name="subject" placeholder="Subject" value="<?= $data['subject'] ?>" required>

<input type="date" name="exam_date" value="<?= $data['exam_date'] ?>" required>
<input type="time" name="exam_time" value="<?= $data['exam_time'] ?>" required>

<input type="number" name="max_marks" placeholder="Max Marks" value="<?= $data['max_marks'] ?>" required>
<input type="text" name="duration" placeholder="Duration (e.g 3 hours)" value="<?= $data['duration'] ?>" required>

<button class="btn primary">Save Exam</button>
<a href="index.php?page=exams" class="btn">Cancel</a>

</form>
</div>
</div>
