<?php
include __DIR__ . "/../../db.php";

if(!isset($_POST['class']) || !isset($_POST['date'])){
    header("Location: index.php?page=attendance");
    exit;
}

$class = $_POST['class'];
$date  = $_POST['date'];

/* Delete old attendance of that day */
$conn->query("DELETE FROM attendance WHERE class='$class' AND 'date'='$date'");

/* Insert new attendance */
if(isset($_POST['att'])){

    foreach($_POST['att'] as $sid => $status){

        // get roll no from student id
        $student = $conn->query("SELECT roll_no FROM students WHERE id=$sid")->fetch_assoc();

        if(!$student) continue;

        $roll = $student['roll_no'];

        $conn->query("
            INSERT INTO attendance (roll_no, class, date, status)
            VALUES ('$roll','$class','$date','$status')
        ");
    }
}

/* Redirect back */
header("Location: index.php?page=attendance&class=$class&date=$date&saved=1");
exit;
?>
