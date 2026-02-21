<?php
include(__DIR__ . "/../../db.php");


/* STUDENTS COUNT */
$students = $conn->query("SELECT COUNT(*) AS total FROM students");
$row = $students->fetch_assoc();
$students_total = $row['total'] ?? 0;


/* TEACHERS COUNT */
$teachers = $conn->query("SELECT COUNT(*) AS total FROM teachers");
$row = $teachers->fetch_assoc();
$teachers_total = $row['total'] ?? 0;


/* PRESENT TODAY (FIXED COLUMN NAME) */
$present = $conn->query("
    SELECT COUNT(*) AS total 
    FROM attendance 
    WHERE status='Present' AND att_date = CURDATE()
");
$row = $present->fetch_assoc();
$present_today = $row['total'] ?? 0;


/* TOTAL FEES COLLECTED */
$fees = $conn->query("
    SELECT IFNULL(SUM(amount),0) AS total 
    FROM fees 
    WHERE status='Paid'
");
$row = $fees->fetch_assoc();
$fees_total = $row['total'] ?? 0;

?>
