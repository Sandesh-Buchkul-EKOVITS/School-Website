<?php
include __DIR__ . '/../../db.php';

$id=$_GET['id'];
$conn->query("DELETE FROM exams WHERE id=$id");

header("Location:index.php?page=exams");
