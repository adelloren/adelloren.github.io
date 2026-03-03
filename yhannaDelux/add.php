<?php
include "db.php";

$task = $_POST['task'];

$conn->query("INSERT INTO tasks(task_name, status) VALUES('$task', 'pending')");

header("Location: index.php");
?>