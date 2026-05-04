<?php
require_once 'db_connection.php';

$task = null;
$error_message = '';

// Check if task ID is provided
if (isset($_GET['id'])) {
    $task_id = $_GET['id'];
    
    // Fetch task details
    $stmt = $pdo->prepare("SELECT * FROM tasks WHERE id = :id");
    $stmt->execute([':id' => $task_id]);
    $task = $stmt->fetch();
    
    if (!$task) {
        header('Location: index.php');
        exit;
    }
} else {
    header('Location: index.php');
    exit;
}

// Check for error message from update
if (isset($_GET['error']) && $_GET['error'] == 'empty_title') {
    $error_message = 'Task title is required!';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Task - TaskFlow</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="app-wrapper">
        <!-- Header -->
        <header class="app-header">
            <div class="container">
                <div class="header-content">
                    <div class="logo-section">
                        <div class="logo-icon">
                            <i class="fa-solid fa-list-check"></i>
                        </div>
                        <div class="logo-text">
                            <h1>TaskFlow</h1>
                            <span>Professional Task Manager</span>
                        </div>
                    </div>
                    <div class="header-actions">
                        <a href="index.php" class="btn-back-nav">
                            <i class="fa-solid fa-arrow-left"></i>
                            Back to Tasks
                        </a>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="app-main">
            <div class="container">
                <div class="edit-page-wrapper">
                    <!-- Edit Task Form -->
                    <div class="task-form-card edit-form-card">
                        <div class="card-header-custom edit-header">
                            <i class="fa-solid fa-pen-to-square"></i>
                            <h3>Edit Task</h3>
                        </div>
                        <div class="card-body-custom">
                            <form action="update_task.php" method="POST" class="edit-task-form">
                                <input type="hidden" name="task_id" value="<?php echo $task['id']; ?>">
                                
                                <div class="form-group-edit">
                                    <label for="edit_title">
                                        <i class="fa-solid fa-heading"></i> Task Title <span class="required-star">*</span>
                                    </label>
                                    <div class="input-wrapper">
                                        <input type="text" id="edit_title" name="title" class="form-control-custom <?php echo !empty($error_message) ? 'input-error' : ''; ?>" placeholder="Enter task title..." maxlength="255" value="<?php echo isset($_GET['title']) ? htmlspecialchars($_GET['title']) : htmlspecialchars($task['title']); ?>">
                                        <?php if (!empty($error_message)): ?>
                                            <div class="error-message">
                                                <i class="fa-solid fa-circle-exclamation error-icon"></i>
                                                <span><?php echo $error_message; ?></span>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="form-group-edit">
                                    <label for="edit_description">
                                        <i class="fa-solid fa-align-left"></i> Description
                                    </label>
                                    <textarea id="edit_description" name="description" class="form-control-custom textarea-custom" placeholder="Add a description (optional)..." rows="4"><?php echo isset($_GET['description']) ? htmlspecialchars($_GET['description']) : htmlspecialchars($task['description'] ?? ''); ?></textarea>
                                </div>

                                <div class="task-info-card">
                                    <div class="info-item">
                                        <div class="info-label">
                                            <i class="fa-solid fa-circle-info"></i> Status
                                        </div>
                                        <div class="info-value">
                                            <?php if ($task['is_completed']): ?>
                                                <span class="badge badge-done">
                                                    <i class="fa-solid fa-circle-check"></i> Completed
                                                </span>
                                            <?php else: ?>
                                                <span class="badge badge-pending">
                                                    <i class="fa-regular fa-clock"></i> Pending
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="info-item">
                                        <div class="info-label">
                                            <i class="fa-regular fa-calendar"></i> Created
                                        </div>
                                        <div class="info-value-text">
                                            <?php echo date('F d, Y \a\t h:i A', strtotime($task['created_at'])); ?>
                                        </div>
                                    </div>
                                    <?php if ($task['updated_at'] != $task['created_at']): ?>
                                    <div class="info-item">
                                        <div class="info-label">
                                            <i class="fa-solid fa-clock-rotate-left"></i> Last Updated
                                        </div>
                                        <div class="info-value-text">
                                            <?php echo date('F d, Y \a\t h:i A', strtotime($task['updated_at'])); ?>
                                        </div>
                                    </div>
                                    <?php endif; ?>
                                </div>

                                <div class="form-actions">
                                    <a href="index.php" class="btn-cancel">
                                        <i class="fa-solid fa-xmark"></i>
                                        Cancel
                                    </a>
                                    <button type="submit" class="btn-update">
                                        <i class="fa-solid fa-check"></i>
                                        Update Task
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>