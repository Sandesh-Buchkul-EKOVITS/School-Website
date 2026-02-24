<?php
include "../config/db.php";

header('Content-Type: application/json');

/* ================= Attendance Monthly % ================= */
$months = [];
$attendance = [];

$query = "
SELECT 
    DATE_FORMAT(date, '%b') AS month,
    ROUND(AVG(status='Present')*100) AS percent
FROM attendance
GROUP BY MONTH(date)
ORDER BY MONTH(date)
";

$result = mysqli_query($conn, $query);

if($result){
    while($row = mysqli_fetch_assoc($result)){
        $months[] = $row['month'];
        $attendance[] = (int)$row['percent'];
    }
}

/* ================= Fees Paid vs Pending ================= */
$classes = [];
$paid = [];
$pending = [];

$query2 = "
SELECT 
    students.class,
    SUM(CASE WHEN fees.status='Paid' THEN 1 ELSE 0 END) AS paid,
    SUM(CASE WHEN fees.status='Pending' THEN 1 ELSE 0 END) AS pending
FROM fees
JOIN students ON students.id = fees.student_id
GROUP BY students.class
ORDER BY students.class
";

$result2 = mysqli_query($conn, $query2);

if($result2){
    while($row = mysqli_fetch_assoc($result2)){
        $classes[] = 'Class '.$row['class'];
        $paid[] = (int)$row['paid'];
        $pending[] = (int)$row['pending'];
    }
}

/* ================= Send JSON ================= */
echo json_encode([
    "months"=>$months,
    "attendance"=>$attendance,
    "classes"=>$classes,
    "paid"=>$paid,
    "pending"=>$pending
]);
