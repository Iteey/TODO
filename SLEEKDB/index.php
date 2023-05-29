<?php
set_time_limit(0);
require_once 'vendor/autoload.php';
use SleekDB\SleekDB;

$db = SleekDB::store('todo', 'data',['timeout' => false]);

if(isset($_POST['task'])) {
    $task = $_POST['task'];

    $existingTask = $db->where('task', '=', $task)->fetch();
    if(empty($existingTask)) {
        $db->insert(['task' => $task, 'completed' => false]);
    }
}

if(isset($_POST['delete'])) {
    $taskId = $_POST['delete'];
    $db->deleteById($taskId);
}

if(isset($_POST['complete'])) {
    $taskId = $_POST['complete'];
    $task = $db->findById($taskId);
    $db->insert(['task' => $task['task'], 'completed' => true]);
    $db->deleteById($taskId);
    $tasks = $db->fetch();
}

$tasks = $db->fetch();
?>

<!DOCTYPE html>
<html>
<head>
    <title>TODO App</title>
   <style>
    body {
    font-family: Arial, sans-serif;
    background-color: #f8f8f8;
    margin: 0;
    padding: 20px;
  }
  
  h1 {
    color: #333;
  }
  
  h2 {
    color: #777;
    margin-top: 40px;
  }
  
  ul {
    list-style-type: none;
    padding: 0;
  }
  
  li {
    padding: 10px;
    border-radius: 6px;
    background-color: #fff;
    margin-bottom: 10px;
  }
  
  .taskActions {
    display: inline-block;
  }
  
  .deleteButton {
    background-color: #e74c3c;
    color: #fff;
    border: none;
    padding: 6px 12px;
    margin-left: 10px;
    border-radius: 4px;
    cursor: pointer;
  }
  
  .completeButton {
    background-color: #2ecc71;
    color: #fff;
    border: none;
    padding: 6px 12px;
    margin-left: 10px;
    border-radius: 4px;
    cursor: pointer;
  }
  
  #completedTaskList li {
    background-color: #eee;
    text-decoration: line-through;
    color: #888;
  }
  
   </style>
</head>
<body>
    <h1>TODO App</h1>

    <form id="addTaskForm" method="POST">
        <input type="text" name="task" placeholder="Enter task" required>
        <button type="submit">Add</button>
    </form>

    <h2>Uncompleted Tasks</h2>
    <ul id="taskList">
        <?php if(isset($tasks) && !empty($tasks)): ?>
            <?php foreach($tasks as $task): ?>
                <?php if(!$task['completed']): ?>
                    <li>
                        <?php echo $task['task']; ?>
                        <form class="taskActions" method="POST">
                            <?php if(isset($task['_id'])): ?>
                                <input type="hidden" name="delete" value="<?php echo $task['_id']; ?>">
                            <?php endif; ?>
                            <button type="submit" class="deleteButton">Delete</button>
                        </form>
                        <form class="taskActions" method="POST">
                            <?php if(isset($task['_id'])): ?>
                                <input type="hidden" name="complete" value="<?php echo $task['_id']; ?>">
                            <?php endif; ?>
                            <button type="submit" class="completeButton">Completed</button>
                        </form>
                    </li>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php endif; ?>
    </ul>

    <h2>Completed Tasks</h2>
    <ul id="completedTaskList">
        <?php if(isset($tasks) && !empty($tasks)): ?>
            <?php foreach($tasks as $task): ?>
                <?php if($task['completed']): ?>
                    <li>
                        <?php echo $task['task']; ?>
                        <form class="taskActions" method="POST">
                            <?php if(isset($task['_id'])): ?>
                                <input type="hidden" name="delete" value="<?php echo $task['_id']; ?>">
                            <?php endif; ?>
                            <button type="submit" class="deleteButton">Delete</button>
                        </form>
                    </li>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php endif; ?>
    </ul>

</body>
</html>
