<?php
require_once 'db_connection.php';

// Fetch all tasks ordered by newest first
$stmt = $pdo->query("SELECT * FROM tasks ORDER BY created_at DESC");
$tasks = $stmt->fetchAll();

// Count statistics
$total_tasks = count($tasks);
$completed_tasks = count(array_filter($tasks, function($task) {
    return $task['is_completed'] == 1;
}));
$pending_tasks = $total_tasks - $completed_tasks;

// Check for error message from add_task.php
$error_message = '';
if (isset($_GET['error']) && $_GET['error'] == 'empty_title') {
    $error_message = 'Task title is required!';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TaskFlow - Professional Task Manager</title>
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
                    <div class="header-stats">
                        <div class="stat-card stat-total">
                            <div class="stat-icon">
                                <i class="fa-solid fa-layer-group"></i>
                            </div>
                            <div class="stat-info">
                                <span class="stat-number"><?php echo $total_tasks; ?></span>
                                <span class="stat-label">Total</span>
                            </div>
                        </div>
                        <div class="stat-card stat-pending">
                            <div class="stat-icon">
                                <i class="fa-solid fa-clock"></i>
                            </div>
                            <div class="stat-info">
                                <span class="stat-number"><?php echo $pending_tasks; ?></span>
                                <span class="stat-label">Pending</span>
                            </div>
                        </div>
                        <div class="stat-card stat-completed">
                            <div class="stat-icon">
                                <i class="fa-solid fa-circle-check"></i>
                            </div>
                            <div class="stat-info">
                                <span class="stat-number"><?php echo $completed_tasks; ?></span>
                                <span class="stat-label">Done</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="app-main">
            <div class="container">
                <!-- Add Task Form -->
                <div class="task-form-card">
                    <div class="card-header-custom">
                        <i class="fa-solid fa-plus-circle"></i>
                        <h3>Create New Task</h3>
                    </div>
                    <div class="card-body-custom">
                        <form action="add_task.php" method="POST" class="add-task-form">
                            <div class="row g-3">
                                <div class="col-md-5">
                                    <div class="input-group-custom">
                                        <label for="title">
                                            <i class="fa-solid fa-heading"></i> Task Title <span class="required-star">*</span>
                                        </label>
                                        <div class="input-wrapper">
                                            <input type="text" id="title" name="title" class="form-control-custom <?php echo !empty($error_message) ? 'input-error' : ''; ?>" placeholder="Enter task title..." maxlength="255" value="<?php echo isset($_GET['title']) ? htmlspecialchars($_GET['title']) : ''; ?>">
                                            <?php if (!empty($error_message)): ?>
                                                <div class="error-message">
                                                    <i class="fa-solid fa-circle-exclamation error-icon"></i>
                                                    <span><?php echo $error_message; ?></span>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="input-group-custom">
                                        <label for="description">
                                            <i class="fa-solid fa-align-left"></i> Description
                                        </label>
                                        <input type="text" id="description" name="description" class="form-control-custom" placeholder="Add a description (optional)..." value="<?php echo isset($_GET['description']) ? htmlspecialchars($_GET['description']) : ''; ?>">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="input-group-custom">
                                        <label>&nbsp;</label>
                                        <button type="submit" class="btn-add-task">
                                            <i class="fa-solid fa-plus"></i>
                                            Add Task
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Tasks List -->
                <div class="tasks-section">
                    <div class="section-header">
                        <div class="section-title">
                            <i class="fa-solid fa-list"></i>
                            <h2>My Tasks</h2>
                            <span class="task-count-badge"><?php echo $total_tasks; ?> tasks</span>
                        </div>
                        <?php if ($total_tasks > 0): ?>
                            <form action="delete_all.php" method="POST" class="delete-all-form">
                                <button type="submit" class="btn-delete-all" onclick="return confirm('Are you sure you want to delete ALL tasks? This action cannot be undone.')">
                                    <i class="fa-solid fa-trash-can"></i>
                                    Clear All Tasks
                                </button>
                            </form>
                        <?php endif; ?>
                    </div>

                    <?php if ($total_tasks > 0): ?>
                        <div class="tasks-container">
                            <div class="tasks-scroll">
                                <?php foreach ($tasks as $task): ?>
                                    <div class="task-item <?php echo $task['is_completed'] ? 'task-completed' : ''; ?>">
                                        <div class="task-status">
                                            <form action="toggle_task.php" method="POST" class="toggle-form">
                                                <input type="hidden" name="task_id" value="<?php echo $task['id']; ?>">
                                                <input type="hidden" name="current_status" value="<?php echo $task['is_completed']; ?>">
                                                <button type="submit" class="btn-toggle">
                                                    <?php if ($task['is_completed']): ?>
                                                        <i class="fa-solid fa-circle-check completed-icon"></i>
                                                    <?php else: ?>
                                                        <i class="fa-regular fa-circle incomplete-icon"></i>
                                                    <?php endif; ?>
                                                </button>
                                            </form>
                                        </div>
                                        <div class="task-content">
                                            <h4 class="task-title"><?php echo htmlspecialchars($task['title']); ?></h4>
                                            <?php if (!empty($task['description'])): ?>
                                                <p class="task-description"><?php echo htmlspecialchars($task['description']); ?></p>
                                            <?php endif; ?>
                                            <div class="task-meta">
                                                <span class="task-date">
                                                    <i class="fa-regular fa-calendar"></i>
                                                    <?php echo date('M d, Y', strtotime($task['created_at'])); ?>
                                                </span>
                                                <span class="task-time">
                                                    <i class="fa-regular fa-clock"></i>
                                                    <?php echo date('h:i A', strtotime($task['created_at'])); ?>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="task-status-badge">
                                            <?php if ($task['is_completed']): ?>
                                                <span class="badge badge-done">Completed</span>
                                            <?php else: ?>
                                                <span class="badge badge-pending">Pending</span>
                                            <?php endif; ?>
                                        </div>
                                        <div class="task-actions">
                                            <a href="edit_task.php?id=<?php echo $task['id']; ?>" class="btn-edit-task" title="Edit Task">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </a>
                                            <form action="delete_task.php" method="POST" class="delete-form">
                                                <input type="hidden" name="task_id" value="<?php echo $task['id']; ?>">
                                                <button type="submit" class="btn-delete-task" title="Delete Task" onclick="return confirm('Are you sure you want to delete this task?')">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="empty-state">
                            <div class="empty-icon">
                                <i class="fa-solid fa-clipboard-list"></i>
                            </div>
                            <h3>No tasks yet!</h3>
                            <p>Start by adding your first task using the form above.</p>
                            <div class="empty-decoration">
                                <i class="fa-solid fa-arrow-up"></i>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>