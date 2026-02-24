<?php
include "../../db.php";

$id = $_POST['id'];
$name = $_POST['name'];
$employee_id = $_POST['employee_id'];
$subject = $_POST['subject'];
$qualification = $_POST['qualification'];
$phone = $_POST['phone'];
$email = $_POST['email'];
$status = $_POST['status'];

$conn->query("UPDATE teachers SET
name='$name',
employee_id='$employee_id',
subject='$subject',
qualification='$qualification',
phone='$phone',
email='$email',
status='$status'
WHERE id=$id");

echo "success";
