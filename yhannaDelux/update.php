<?php
include "db.php";

$id   = $_GET['id'];
$task = $_GET['task'];

$conn->query("UPDATE tasks SET task_name='$task' WHERE id=$id");

header("Location: index.php");
?>