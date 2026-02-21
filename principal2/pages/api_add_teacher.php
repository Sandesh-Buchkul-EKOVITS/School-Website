<?php
include __DIR__ . "/../../db.php";

if($_SERVER["REQUEST_METHOD"] == "POST"){

    $name = $_POST['name'];
    $employee_id = $_POST['employee_id'];
    $subject = $_POST['subject'];
    $qualification = $_POST['qualification'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $status = $_POST['status'];

    $sql = "INSERT INTO teachers
    (name, employee_id, subject, qualification, phone, email, status)
    VALUES (?,?,?,?,?,?,?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssss",
        $name,$employee_id,$subject,$qualification,$phone,$email,$status
    );

    if($stmt->execute()){
        echo "success";
    }else{
        echo "error";
    }
}
