<?php
include "db.php";

$result = $conn->query("SELECT * FROM tasks");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Coffee Task List</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Georgia', serif;
            background: linear-gradient(135deg, #E8D8C4 0%, #C7B7A3 100%);
            min-height: 100vh;
            padding: 40px 20px;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
        }

        h1 {
            color: #561C24;
            text-align: center;
            margin-bottom: 30px;
            font-size: 2.5em;
            font-style: italic;
            letter-spacing: 2px;
        }

        .add-task-form {
            display: flex;
            gap: 10px;
            margin-bottom: 30px;
        }

        .add-task-form input {
            flex: 1;
            padding: 12px 15px;
            border: 2px solid #6D2932;
            border-radius: 25px;
            font-size: 1em;
            background-color: #E8D8C4;
            color: #561C24;
            font-family: 'Georgia', serif;
        }

        .add-task-form input::placeholder {
            color: #C7B7A3;
        }

        .add-task-form input:focus {
            outline: none;
            border-color: #561C24;
            box-shadow: 0 0 10px rgba(86, 28, 36, 0.3);
        }

        .add-task-form button {
            padding: 12px 30px;
            background-color: #561C24;
            color: #E8D8C4;
            border: none;
            border-radius: 25px;
            cursor: pointer;
            font-size: 1em;
            font-family: 'Georgia', serif;
            font-weight: bold;
            transition: all 0.3s ease;
        }

        .add-task-form button:hover {
            background-color: #6D2932;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(86, 28, 36, 0.3);
        }

        .tasks-list {
            list-style: none;
        }

        .task-item {
            background-color: #E8D8C4;
            margin-bottom: 15px;
            padding: 15px 20px;
            border-radius: 15px;
            border-left: 4px solid #561C24;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 10px;
            box-shadow: 0 3px 10px rgba(86, 28, 36, 0.1);
            transition: all 0.3s ease;
        }

        .task-item:hover {
            background-color: #F0E6D8;
            border-left-color: #6D2932;
            transform: translateX(5px);
            box-shadow: 0 5px 15px rgba(86, 28, 36, 0.15);
        }

        .task-text {
            color: #561C24;
            font-size: 1.1em;
            flex: 1;
            word-break: break-word;
        }

        .task-actions {
            display: flex;
            gap: 8px;
            margin-left: 10px;
        }

        .btn-edit, .btn-delete {
            padding: 6px 12px;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            font-size: 0.85em;
            font-family: 'Georgia', serif;
            transition: all 0.3s ease;
            white-space: nowrap;
        }

        .btn-edit {
            background-color: #C7B7A3;
            color: #561C24;
            border: 1px solid #561C24;
        }

        .btn-edit:hover {
            background-color: #561C24;
            color: #E8D8C4;
        }

        .btn-delete {
            background-color: #6D2932;
            color: #E8D8C4;
        }

        .btn-delete:hover {
            background-color: #561C24;
            box-shadow: 0 3px 8px rgba(86, 28, 36, 0.3);
        }

        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: #6D2932;
            font-style: italic;
        }

        .task-checkbox {
            width: 20px;
            height: 20px;
            cursor: pointer;
            margin-right: 15px;
            accent-color: #561C24;
            flex-shrink: 0;
        }

        .task-item.completed .task-text {
            color: #C7B7A3;
            text-decoration: line-through;
        }

        .task-item.completed {
            opacity: 0.8;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>☕ Tasks</h1>

    <form class="add-task-form" action="add.php" method="POST">
        <input type="text" name="task" placeholder="Add a new task..." required>
        <button type="submit">Add</button>
    </form>

    <ul class="tasks-list">
    <?php if($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) { 
            $isCompleted = $row['status'] == 'done';
        ?>
        <li class="task-item <?php echo $isCompleted ? 'completed' : ''; ?>">
            <input type="checkbox" class="task-checkbox" 
                   id="check-<?php echo $row['id']; ?>"
                   <?php echo $isCompleted ? 'checked' : ''; ?>
                   onchange="updateStatus(<?php echo $row['id']; ?>)">
            <span class="task-text" id="task-<?php echo $row['id']; ?>">
                <?php echo htmlspecialchars($row['task_name']); ?>
            </span>
            <div class="task-actions">
                <button class="btn-edit" onclick="editTask(<?php echo $row['id']; ?>)">
                    Edit
                </button>
                <a href="delete.php?id=<?php echo $row['id']; ?>"
                   class="btn-delete"
                   style="text-decoration: none;"
                   onclick="return confirm('Delete this task?')">
                   Delete
                </a>
            </div>
        </li>
        <?php }
    } else { ?>
        <div class="empty-state">
            <p>No tasks yet. Add one to get started! ☕</p>
        </div>
    <?php } ?>
    </ul>
</div>

<script src="cosa.js"></script>
<script>
    function updateStatus(id) {
        const checkbox = document.getElementById('check-' + id);
        const status = checkbox.checked ? 'done' : 'pending';
        
        fetch('update-status.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: 'id=' + id + '&status=' + status
        })
        .then(response => response.text())
        .then(data => {
            location.reload();
        })
        .catch(error => console.error('Error:', error));
    }
</script>

</body>
</html>