<?php
include __DIR__ . '/../../db.php';

$id=$_GET['id'];
$conn->query("DELETE FROM students WHERE id=$id");

header("Location: ../index.php?page=students");
exit;
