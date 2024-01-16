<?PHP
// update-task-status.php

include("database.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $taskId = $_POST['taskId'];
    $newStatus = $_POST['newStatus'];

    // Update the task status in the database
    $updateQuery = "UPDATE `project_task` SET `status` = '$newStatus' WHERE `id_task` = $taskId";
    $result = mysqli_query($con, $updateQuery);

    // Return a response (if needed)
    if ($result) {
        echo json_encode(['success' => true, 'message' => 'Task status updated successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error updating task status']);
    }
}
?>