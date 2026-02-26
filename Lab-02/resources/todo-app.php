<?php
if (!is_dir(__DIR__ . '/data')) {
    mkdir(__DIR__ . '/data', 0775, true);
}
$db = new SQLite3(__DIR__ . '/data/todo.db');

$query = "CREATE TABLE IF NOT EXISTS tasks (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    task TEXT NOT NULL,
                    completed INTEGER DEFAULT 0
                  )";
$db->query($query);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_task'])) {
        $task = $_POST['task'];
        $stmt = $db->prepare('INSERT INTO tasks (task) VALUES (:task)');
        $stmt->bindValue(':task', $task, SQLITE3_TEXT);
        $stmt->execute();
    } elseif (isset($_POST['delete_task'])) {
        $id = $_POST['id'];
        $stmt = $db->prepare('DELETE FROM tasks WHERE id = :id');
        $stmt->bindValue(':id', $id, SQLITE3_INTEGER);
        $stmt->execute();
    } elseif (isset($_POST['toggle_task'])) {
        $id = $_POST['id'];
        $stmt = $db->prepare('UPDATE tasks SET completed = 1 - completed WHERE id = :id');
        $stmt->bindValue(':id', $id, SQLITE3_INTEGER);
        $stmt->execute();
    } elseif (isset($_POST['clear_all'])) {
        $db->query('DELETE FROM tasks');
    }
    header('Location: index.php');
}

// Fetch all tasks
$result = $db->query('SELECT * FROM tasks ORDER BY id DESC');
$tasks = [];
while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
    $tasks[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>To-Do List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea, #764ba2);
            font-family: 'Poppins', sans-serif;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #333;
        }
        .container {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            max-width: 600px;
            width: 100%;
        }
        h1 {
            font-size: 2.5rem;
            font-weight: 700;
            color: #333;
            text-align: center;
            margin-bottom: 20px;
        }
        .form-control {
            border-radius: 10px;
            border: 2px solid #ddd;
            padding: 10px 15px;
            font-size: 1rem;
        }
        .form-control:focus {
            border-color: #667eea;
            box-shadow: none;
        }
        .btn-primary {
            background: #667eea;
            border: none;
            border-radius: 10px;
            padding: 10px 20px;
            font-size: 1rem;
            transition: background 0.3s ease;
        }
        .btn-primary:hover {
            background: #764ba2;
        }
        .task-list {
            margin-top: 20px;
            height: 400px;
            overflow: auto  ;
        }
        .task-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
            border: 2px solid #ddd;
            border-radius: 10px;
            margin-bottom: 10px;
            background: #fff;
            transition: all 0.3s ease;
        }
        .task-item:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        .task-text {
            margin: 0;
            flex-grow: 1;
            margin-left: 10px;
            font-size: 1.1rem;
        }
        .task-text.completed {
            text-decoration: line-through;
            color: #888;
        }
        .task-actions {
            display: flex;
            gap: 10px;
        }
        .btn-action {
            background: none;
            border: none;
            cursor: pointer;
            padding: 5px;
            transition: color 0.3s ease;
        }
        .btn-action:hover {
            color: #667eea;
        }
        .btn-clear {
            width: 100%;
            margin-top: 20px;
            background: #ff4d4d;
            border: none;
            border-radius: 10px;
            padding: 10px 20px;
            font-size: 1rem;
            color: #fff;
            transition: background 0.3s ease;
        }
        .btn-clear:hover {
            background: #ff1a1a;
        }
        .badge {
            font-size: 12px;
            padding: 5px 10px;
        }
        .empty-state {
            text-align: center;
            color: #888;
            font-size: 1.2rem;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>To-Do List</h1>
        <form method="POST" action="" class="d-flex mb-4">
            <input type="text" name="task" class="form-control me-2" placeholder="Add a new task" required>
            <button type="submit" name="add_task" class="btn btn-primary">Add</button>
        </form>
        <div class="task-list">
            <?php if (empty($tasks)){ ?>
                <div class="empty-state">No tasks found. Add a new task!</div>
            <?php }else{ ?>
                <?php foreach ($tasks as $task){ ?>
                    <div class="task-item">
                        <form method="POST" action="" class="d-flex align-items-center" style="flex-grow: 1;">
                            <input type="hidden" name="id" value="<?php echo $task['id']; ?>">
                            <button type="submit" name="toggle_task" class="btn-action">
                                <i class="fas <?php echo $task['completed'] ? 'fa-check-circle text-success' : 'fa-circle'; ?>"></i>
                            </button>
                            <p class="task-text <?php echo $task['completed'] ? 'completed' : ''; ?>">
                                <?php echo htmlspecialchars($task['task']); ?>
                            </p>
                        </form>
                        <div class="task-actions">
                            <form method="POST" action="">
                                <input type="hidden" name="id" value="<?php echo $task['id']; ?>">
                                <button type="submit" name="delete_task" class="btn-action text-danger">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                <?php } ?>
                <form method="POST" action="">
                    <button type="submit" name="clear_all" class="btn btn-clear">Clear All Tasks</button>
                </form>
            <?php } ?>
        </div>
    </div>
</body>
</html>