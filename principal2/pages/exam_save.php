<?php
include __DIR__ . '/../../db.php';

$id = $_POST['id'];

$exam_name = $_POST['exam_name'];
$class = $_POST['class'];
$subject = $_POST['subject'];
$date = $_POST['exam_date'];
$time = $_POST['exam_time'];
$marks = $_POST['max_marks'];
$duration = $_POST['duration'];

if($id){
$conn->query("UPDATE exams SET
exam_name='$exam_name',
class='$class',
subject='$subject',
exam_date='$date',
exam_time='$time',
max_marks='$marks',
duration='$duration'
WHERE id=$id");
}else{
$conn->query("INSERT INTO exams (exam_name,class,subject,exam_date,exam_time,max_marks,duration)
VALUES ('$exam_name','$class','$subject','$date','$time','$marks','$duration')");
}

header("Location:index.php?page=exams");
