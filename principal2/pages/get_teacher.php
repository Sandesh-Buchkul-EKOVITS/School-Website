<?php
include __DIR__ . "/../../db.php";

$id = $_GET['id'];

$stmt = $conn->prepare("SELECT * FROM teachers WHERE id=?");
$stmt->bind_param("i",$id);
$stmt->execute();

$result = $stmt->get_result();
echo json_encode($result->fetch_assoc());
