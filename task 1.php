<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Chat UI / To-Do App</title>

    <style>

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            background: #f2f5f9;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        /* Main App */

        .app {
            width: 100%;
            max-width: 600px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 25px rgba(0,0,0,0.15);
            overflow: hidden;
        }

        /* Header */

        .header {
            background: #2563eb;
            color: white;
            padding: 20px;
            text-align: center;
        }

        .header h1 {
            margin-bottom: 5px;
        }

        .header p {
            font-size: 14px;
        }

        /* Input Area */

        .input-area {
            display: flex;
            padding: 20px;
            gap: 10px;
            border-bottom: 1px solid #ddd;
        }

        .input-area input {
            flex: 1;
            padding: 13px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 15px;
            outline: none;
        }

        .input-area input:focus {
            border-color: #2563eb;
        }

        .add-btn {
            background: #2563eb;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 15px;
        }

        .add-btn:hover {
            background: #1d4ed8;
        }

        /* Task List */

        .task-list {
            padding: 20px;
            max-height: 400px;
            overflow-y: auto;
        }

        .task {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px;
            margin-bottom: 10px;
            background: #f8fafc;
            border-radius: 10px;
            border: 1px solid #e5e7eb;
        }

        .task input[type="checkbox"] {
            width: 20px;
            height: 20px;
            cursor: pointer;
        }

        .task-text {
            flex: 1;
            font-size: 16px;
            word-break: break-word;
        }

        /* Completed Task */

        .completed {
            text-decoration: line-through;
            color: #888;
        }

        /* Delete Button */

        .delete-btn {
            background: #ef4444;
            color: white;
            border: none;
            padding: 8px 12px;
            border-radius: 6px;
            cursor: pointer;
        }

        .delete-btn:hover {
            background: #dc2626;
        }

        /* Empty Message */

        .empty {
            text-align: center;
            color: #888;
            padding: 30px;
        }

        /* Footer */

        .footer {
            padding: 15px 20px;
            background: #f8f9fa;
            border-top: 1px solid #ddd;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .clear-btn {
            background: #6b7280;
            color: white;
            border: none;
            padding: 8px 12px;
            border-radius: 6px;
            cursor: pointer;
        }

        .clear-btn:hover {
            background: #4b5563;
        }

        /* Mobile */

        @media (max-width: 500px) {

            body {
                padding: 10px;
            }

            .input-area {
                flex-direction: column;
            }

            .add-btn {
                width: 100%;
            }

            .task {
                padding: 12px;
            }

        }

    </style>
</head>

<body>


<div class="app">

    <!-- Header -->

    <div class="header">

        <h1>💬 My To-Do App</h1>

        <p>Manage your daily tasks easily</p>

    </div>


    <!-- Input -->

    <div class="input-area">

        <input
            type="text"
            id="taskInput"
            placeholder="Enter a task..."
            onkeypress="handleEnter(event)"
        >

        <button
            class="add-btn"
            onclick="addTask()">
            Add
        </button>

    </div>


    <!-- Task List -->

    <div class="task-list" id="taskList">

    </div>


    <!-- Footer -->

    <div class="footer">

        <span>
            Tasks: <strong id="taskCount">0</strong>
        </span>

        <button
            class="clear-btn"
            onclick="clearCompleted()">
            Clear Completed
        </button>

    </div>

</div>


<script>

    // Get tasks from localStorage

    let tasks =
        JSON.parse(
            localStorage.getItem("tasks")
        ) || [];


    // Add Task

    function addTask() {

        const input =
            document.getElementById("taskInput");

        const text =
            input.value.trim();


        // Check empty input

        if (text === "") {

            alert("Please enter a task.");

            return;

        }


        // Create task object

        const task = {

            id: Date.now(),

            text: text,

            completed: false

        };


        // Add task

        tasks.push(task);


        // Save task

        saveTasks();


        // Clear input

        input.value = "";


        // Display tasks

        displayTasks();

    }


    // Display Tasks

    function displayTasks() {

        const taskList =
            document.getElementById("taskList");


        taskList.innerHTML = "";


        // Empty task list

        if (tasks.length === 0) {

            taskList.innerHTML = `

                <div class="empty">

                    📝 No tasks yet.

                    <br>

                    Add your first task!

                </div>

            `;

            updateCount();

            return;

        }


        // Display every task

        tasks.forEach(function(task) {

            const taskDiv =
                document.createElement("div");

            taskDiv.className = "task";


            // Checkbox

            const checkbox =
                document.createElement("input");

            checkbox.type = "checkbox";

            checkbox.checked =
                task.completed;


            checkbox.onclick =
                function() {

                    toggleTask(task.id);

                };


            // Task text

            const taskText =
                document.createElement("span");

            taskText.className = "task-text";


            if (task.completed) {

                taskText.classList.add("completed");

            }


            taskText.innerText =
                task.text;


            // Delete button

            const deleteButton =
                document.createElement("button");

            deleteButton.className =
                "delete-btn";

            deleteButton.innerText =
                "Delete";


            deleteButton.onclick =
                function() {

                    deleteTask(task.id);

                };


            // Add elements

            taskDiv.appendChild(checkbox);

            taskDiv.appendChild(taskText);

            taskDiv.appendChild(deleteButton);


            taskList.appendChild(taskDiv);

        });


        updateCount();

    }


    // Mark task complete/incomplete

    function toggleTask(id) {

        tasks = tasks.map(function(task) {

            if (task.id === id) {

                task.completed =
                    !task.completed;

            }

            return task;

        });


        saveTasks();

        displayTasks();

    }


    // Delete Task

    function deleteTask(id) {

        tasks =
            tasks.filter(function(task) {

                return task.id !== id;

            });


        saveTasks();

        displayTasks();

    }


    // Clear Completed Tasks

    function clearCompleted() {

        tasks =
            tasks.filter(function(task) {

                return task.completed === false;

            });


        saveTasks();

        displayTasks();

    }


    // Save Tasks

    function saveTasks() {

        localStorage.setItem(
            "tasks",
            JSON.stringify(tasks)
        );

    }


    // Update Task Count

    function updateCount() {

        document.getElementById("taskCount")
            .innerText = tasks.length;

    }


    // Enter key

    function handleEnter(event) {

        if (event.key === "Enter") {

            addTask();

        }

    }


    // Display tasks when page loads

    displayTasks();

</script>

</body>
</html>