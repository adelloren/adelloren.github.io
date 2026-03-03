function editTask(id) {

    let oldText = document.getElementById("task-" + id).innerText;
    let newTask = prompt("Edit task:", oldText);

    if(newTask !== null && newTask.trim() !== "") {
        window.location = "update.php?id=" + id + "&task=" + encodeURIComponent(newTask);
    }
}