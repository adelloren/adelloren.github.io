<?php
include "db.php";

$id = $_POST['id'];
$status = $_POST['status'];

// Validate status
if (!in_array($status, ['pending', 'done'])) {
    $status = 'pending';
}

$conn->query("UPDATE tasks SET status='$status' WHERE id=$id");

echo json_encode(['success' => true]);
?>
