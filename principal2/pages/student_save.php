<?php
include __DIR__ . '/../../db.php';

$id     = $_POST['id'];
$roll   = $_POST['roll'];
$name   = $_POST['name'];
$class  = $_POST['class'];
$gender = $_POST['gender'];
$phone  = $_POST['phone'];

if(isset($_POST['id']) && $_POST['id'] != ''){
    $id = $_POST['id'];

    $conn->query("UPDATE students SET
        name='$name',
        class='$class',
        phone='$phone'
        WHERE id=$id");

} else {

    $conn->query("INSERT INTO students(name,class,phone)
                  VALUES('$name','$class','$phone')");
}

header("Location: index.php?page=students");
