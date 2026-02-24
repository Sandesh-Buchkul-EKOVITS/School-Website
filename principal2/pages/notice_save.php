<?php
include __DIR__ . '/../../db.php';

$id = $_POST['id'] ?? '';
$title = $_POST['title'] ?? '';
$description = $_POST['description'] ?? '';
$notice_date = $_POST['date'] ?? '';

if($id == ""){
    // INSERT
    $stmt = $conn->prepare("
        INSERT INTO notices (title, description, notice_date)
        VALUES (?, ?, ?)
    ");
    $stmt->bind_param("sss", $title, $description, $notice_date);
    $stmt->execute();
}
else{
    // UPDATE
    $stmt = $conn->prepare("
        UPDATE notices 
        SET title=?, description=?, notice_date=?
        WHERE id=?
    ");
    $stmt->bind_param("sssi", $title, $description, $notice_date, $id);
    $stmt->execute();
}

header("Location: index.php?page=notice");
exit;