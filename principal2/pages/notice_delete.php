<?php
include __DIR__ . '/../../db.php';

if(isset($_POST['id']) && is_numeric($_POST['id'])){

    $id = $_POST['id'];

    $stmt = $conn->prepare("DELETE FROM notices WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
}

header("Location: index.php?page=notice");
exit;