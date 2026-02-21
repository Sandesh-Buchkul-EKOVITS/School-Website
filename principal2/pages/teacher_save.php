<?php
include __DIR__ . "/../../db.php";

$id = $_POST['id'];
$name = $_POST['name'];
$subject = $_POST['subject'];
$phone = $_POST['phone'];
$status = $_POST['status'];

if($id){
    $conn->query("UPDATE teachers SET
        name='$name',
        subject='$subject',
        phone='$phone',
        status='$status'
        WHERE id=$id
    ");
}
else{
    $conn->query("INSERT INTO teachers(name,subject,phone,status)
        VALUES('$name','$subject','$phone','$status')");
}

header("Location: index.php?page=teachers");
