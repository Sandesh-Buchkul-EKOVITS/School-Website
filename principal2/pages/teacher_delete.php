<?php
include __DIR__ . "/../../db.php";

$id = $_GET['id'];
$conn->query("DELETE FROM teachers WHERE id=$id");

header("Location: index.php?page=teachers");
