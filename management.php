<?php
session_start();
require_once('db_connection.php');

global $emp_id;

if (isset($_SESSION['user_id'])) {
    $emp_id = $_SESSION['user_id'];
}

try {
    $pdo = new PDO("mysql:host=localhost;dbname=sems", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

if (isset($_POST['ajax_request'])) {
    switch ($_POST['ajax_request']) {

        case 'search_project':
            searchProject($pdo);
            break;
        case 'fetch_unassigned_employees':
            fetchUnassignedEmployees($pdo);
            break;
        case 'create_project':
            createProject($pdo);
            break;
        case 'assign_employee':
            assignEmployee($pdo);
            break;
        case 'fetch_assigned_employees':
            fetchAssignedEmployees($pdo);
            break;
        case 'assign_task_all':
            assignTaskAll($pdo);
            break;
    }
}

function searchProject($pdo) {
    $project_name = $_POST['project_name'];
    try {
        $sql = "SELECT * FROM projects WHERE project_name = :project_name";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':project_name', $project_name);
        $stmt->execute();
        $project = $stmt->fetch(PDO::FETCH_ASSOC);

        echo json_encode($project);
        exit;
    } catch (PDOException $e) {
        echo json_encode(['error' => $e->getMessage()]);
        exit;
    }
}

function fetchUnassignedEmployees($pdo) {
    try {
        $sql = "SELECT * FROM users WHERE project_id IS NULL";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $employees = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($employees);
        exit;
    } catch (PDOException $e) {
        echo json_encode(['error' => $e->getMessage()]);
        exit;
    }
}

function createProject($pdo) {
    global $emp_id;
    $project_name = $_POST['project_name'];
    $description = $_POST['description'];
    $deadline = $_POST['deadline'];

    try {
        $sql = "INSERT INTO projects (project_name, description, start_date, end_date, user_id) VALUES (:project_name, :description, NOW(), :deadline, :user_id)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':project_name', $project_name);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':deadline', $deadline);
        $stmt->bindParam(':user_id', $emp_id);
        $stmt->execute();

        echo json_encode(['success' => 'Project created successfully']);
        exit;
    } catch (PDOException $e) {
        echo json_encode(['error' => $e->getMessage()]);
        exit;
    }
}


function assignEmployee($pdo) {
    $project_id = $_POST['project_id_assign'];
    $employee_id = $_POST['employee_id'];
    $assign_date = $_POST['assign_date'];

    try {
        $sql_update = "UPDATE users SET project_id = :project_id WHERE user_id = :employee_id";
        $stmt_update = $pdo->prepare($sql_update);
        $stmt_update->bindParam(':project_id', $project_id);
        $stmt_update->bindParam(':employee_id', $employee_id);
        $stmt_update->execute();

        echo json_encode(['success' => 'Employee assigned to project successfully']);
        exit;
    } catch (PDOException $e) {
        echo json_encode(['error' => $e->getMessage()]);
        exit;
    }
}

function fetchAssignedEmployees($pdo) {
    $project_id = $_POST['project_id'];
    try {
        $sql = "SELECT users.user_id, users.first_name, users.last_name FROM users WHERE project_id = :project_id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':project_id', $project_id);
        $stmt->execute();
        $employees = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($employees);
        exit;
    } catch (PDOException $e) {
        echo json_encode(['error' => $e->getMessage()]);
        exit;
    }
}

function assignTaskAll($pdo) {
    $project_id = $_POST['project_id'];
    $response = array();

    $sql = "SELECT user_id FROM project_employees WHERE project_id = :project_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':project_id', $project_id);
    $stmt->execute();
    $employees = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($employees as $employee) {
        $user_id = $employee['user_id'];
        $task_name = $_POST["task_name_$user_id"];
        $task_description = $_POST["task_description_$user_id"];
        $task_start_date = $_POST["task_start_date_$user_id"];
        $task_end_date = $_POST["task_end_date_$user_id"];

        try {
            $sql_task = "INSERT INTO tasks (project_id, user_id, task_name, description, start_date, end_date, status) 
                         VALUES (:project_id, :user_id, :task_name, :description, :start_date, :end_date, 'Not Started')";
            $stmt_task = $pdo->prepare($sql_task);
            $stmt_task->bindParam(':project_id', $project_id);
            $stmt_task->bindParam(':user_id', $user_id);
            $stmt_task->bindParam(':task_name', $task_name);
            $stmt_task->bindParam(':description', $task_description);
            $stmt_task->bindParam(':start_date', $task_start_date);
            $stmt_task->bindParam(':end_date', $task_end_date);
            $stmt_task->execute();

            $response['success'] = 'Tasks assigned successfully.';
        } catch (PDOException $e) {
            $response['error'] = 'Error assigning tasks: ' . $e->getMessage();
            echo json_encode($response);
            exit;
        }
    }

    echo json_encode($response);
    exit;
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project Management System</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body>
<header>
  <nav class="navigation">
    <a class="navigation-logo" href="#">Smart Employee</a>
    <div>
      <div class="navigation-cont">
        <a class="navigation-link" href="projectmanager.php">Home</a>
        <a class="navigation-link" href="management.php">Create Project</a>
        <a class="navigation-link" href="Manage_Leave_Requests.php">Leave Approval</a>
        <a class="navigation-link" href="logout.php">Logout</a>
      </div>
    </div>
  </nav>
</header>

<div class="tab-container">
    <div class="tab-header">
        <div class="tab active" data-tab="project-creation">Project Creation</div>
        <div class="tab" data-tab="assign-employee">Assign Employee To Project</div>
        <div class="tab" data-tab="view-assigned-employees">Assign Tasks to Employee</div>
    </div>

    <div class="tab-content active" id="project-creation">
        <div class="leavecontainer">
            <h1>Project Creation</h1>
            <form id="create_project_form">
                <label for="project_name_create">Project Name:</label>
                <input type="text" id="project_name_create" name="project_name">

                <label for="description_create">Description:</label>
                <textarea id="description_create" name="description" rows="4" cols="50"></textarea>

                <label for="deadline_create">Deadline:</label>
                <input type="date" id="deadline_create" name="deadline">

                <button type="button" onclick="createProject()">Create Project</button>
            </form>
        </div>
    </div>

    <div class="tab-content" id="assign-employee">
        <div class="leavecontainer">
            <h1>Assign Employee To Project</h1>
            <form action="" method="post">
                <label for="project_search">Search Project:</label>
                <input type="text" id="project_search" name="project_search">

                <button type="button" onclick="searchProject()">Search</button>

                <label for="project_id_assign">Project ID:</label>
                <input type="text" id="project_id_assign" name="project_id_assign" readonly>

                <div id="employee_list"></div>
            </form>
        </div>
    </div>

    <div class="tab-content" id="view-assigned-employees">
    <div class="leavecontainer">
        <h1>Assign Tasks to Employee</h1>
        <form id="assign_tasks_form" action="" method="post">
            <label for="project_search_view">Search Project:</label>
            <input type="text" id="project_search_view" name="project_search_view">

            <button type="button" onclick="searchProjectView()">Search</button>

            <label for="project_id_view">Project ID:</label>
            <input type="text" id="project_id_view" name="project_id_view" readonly>

            <div id="assigned_employee_list"></div>

            <button type="button" onclick="assignAllTasks()">Submit</button>
        </form>
    </div>
</div>

</div>

<script>
    document.querySelectorAll('.tab').forEach(tab => {
        tab.addEventListener('click', function() {
            document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
            document.querySelectorAll('.tab-content').forEach(tc => tc.classList.remove('active'));

            this.classList.add('active');
            document.getElementById(this.getAttribute('data-tab')).classList.add('active');
        });
    });

    function createProject() {
        const formData = {
            ajax_request: 'create_project',
            project_name: $('#project_name_create').val(),
            description: $('#description_create').val(),
            deadline: $('#deadline_create').val()
        };

        $.ajax({
            url: '',
            type: 'POST',
            data: formData,
            success: function(response) {
                const result = JSON.parse(response);
                if (result.success) {
                    alert(result.success);
                    $('#create_project_form')[0].reset();
                } else if (result.error) {
                    alert(result.error);
                }
            }
        });
    }

    function searchProject() {
        const project_name = document.getElementById('project_search').value;
        if (project_name) {
            $.ajax({
                url: '',
                type: 'POST',
                data: { ajax_request: 'search_project', project_name: project_name },
                success: function(response) {
                    const project = JSON.parse(response);
                    if (project) {
                        $('#project_id_assign').val(project.project_id);
                        fetchUnassignedEmployees();
                    } else {
                        alert('Project not found');
                    }
                }
            });
        }
    }

    function fetchUnassignedEmployees() {
        $.ajax({
            url: '',
            type: 'POST',
            data: { ajax_request: 'fetch_unassigned_employees' },
            success: function(response) {
                const employees = JSON.parse(response);
                let html = '<table><tr><th>Employee Name</th><th>Action</th></tr>';
                employees.forEach(employee => {
                    html += `<tr>
                                <td>${employee.first_name} ${employee.last_name}</td>
                                <td><button type="button" onclick="assignEmployee(${employee.user_id})">Assign</button></td>
                             </tr>`;
                });
                html += '</table>';
                $('#employee_list').html(html);
            }
        });
    }

    function assignEmployee(employee_id) {
        const project_id = $('#project_id_assign').val();
        const assign_date = new Date().toISOString().split('T')[0];

        $.ajax({
            url: '',
            type: 'POST',
            data: {
                ajax_request: 'assign_employee',
                project_id_assign: project_id,
                employee_id: employee_id,
                assign_date: assign_date
            },
            success: function(response) {
                const result = JSON.parse(response);
                if (result.success) {
                    alert(result.success);
                    fetchUnassignedEmployees(); 
                } else if (result.error) {
                    alert(result.error);
                }
            }
        });
    }

    function searchProjectView() {
        const project_name = document.getElementById('project_search_view').value;
        if (project_name) {
            $.ajax({
                url: '',
                type: 'POST',
                data: { ajax_request: 'search_project', project_name: project_name },
                success: function(response) {
                    const project = JSON.parse(response);
                    if (project) {
                        $('#project_id_view').val(project.project_id);
                        fetchAssignedEmployees();
                    } else {
                        alert('Project not found');
                    }
                }
            });
        }
    }

    function fetchAssignedEmployees() {
        const project_id = $('#project_id_view').val();
        $.ajax({
            url: '',
            type: 'POST',
            data: { ajax_request: 'fetch_assigned_employees', project_id: project_id },
            success: function(response) {
                const employees = JSON.parse(response);
                let html = '<table><tr><th>Employee Name</th><th>Task Name</th><th>Description</th><th>Start Date</th><th>End Date</th></tr>';
                employees.forEach(employee => {
                    html += `<tr>
                                <td>${employee.first_name} ${employee.last_name}</td>
                                <td><input type="text" name="task_name_${employee.user_id}" id="task_name_${employee.user_id}"></td>
                                <td><input type="text" name="task_description_${employee.user_id}" id="task_description_${employee.user_id}"></td>
                                <td><input type="date" name="task_start_date_${employee.user_id}" id="task_start_date_${employee.user_id}"></td>
                                <td><input type="date" name="task_end_date_${employee.user_id}" id="task_end_date_${employee.user_id}"></td>
                             </tr>`;
                });
                html += '</table>';
                $('#assigned_employee_list').html(html);
            }
        });
    }

    function assignAllTasks() {
    const project_id = $('#project_id_view').val();
    const formData = $('#assign_tasks_form').serialize() + `&ajax_request=assign_task_all&project_id=${project_id}`;

    $.ajax({
        url: '',
        type: 'POST',
        data: formData,
        success: function(response) {
            const result = JSON.parse(response);
            if (result.success) {
                alert(result.success);
                fetchAssignedEmployees();
            } else if (result.error) {
                alert(result.error);
            }
        }
    });
}

</script>
</body>
</html>