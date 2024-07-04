<?php
session_start();
require_once('db_connection.php'); 

$message = "";

if (isset($_SESSION['user_id'])) {
    $emp_id = $_SESSION['user_id'];
}


try {
    $pdo = new PDO("mysql:host=localhost;dbname=sems", "root", "");
    
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}


if (isset($_POST['ajax_request']) && $_POST['ajax_request'] == 'fetch_project_details') {
    $project_id = $_POST['project_id'];

    try {
        $sql = "SELECT * FROM projects WHERE project_id = :project_id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':project_id', $project_id);
        $stmt->execute();
        $project = $stmt->fetch(PDO::FETCH_ASSOC);

        echo json_encode($project);
        exit;
    } catch (PDOException $e) {
        echo json_encode(['error' => $e->getMessage()]);
        exit;
    }
}


if (isset($_POST['ajax_request']) && $_POST['ajax_request'] == 'search_project') {
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


if (isset($_POST['ajax_request']) && $_POST['ajax_request'] == 'fetch_unassigned_employees') {
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


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['assign_employee'])) {
        $project_id = $_POST['project_id_assign'];
        $employee_id = $_POST['employee_id'];
        $assign_date = $_POST['assign_date'];

        try {
            
            $sql_update = "UPDATE users SET project_id = :project_id WHERE user_id = :employee_id";
            $stmt_update = $pdo->prepare($sql_update);
            $stmt_update->bindParam(':project_id', $project_id);
            $stmt_update->bindParam(':employee_id', $employee_id);
            $stmt_update->execute();

            $message = "Employee assigned to project successfully";
        } catch (PDOException $e) {
            $message = "Error: " . $e->getMessage();
        }
    }
}

try {
    $sql_projects = "SELECT project_id, project_name FROM projects";
    $stmt_projects = $pdo->prepare($sql_projects);
    $stmt_projects->execute();
    $projects = $stmt_projects->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $message = "Error fetching projects: " . $e->getMessage();
}

$pdo = null;
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
        <a class="navigation-link" href="employee-dashboard.php">Home</a>
        <a class="navigation-link" href="userProfile.php">Edit Profile</a>
        <a class="navigation-link" href="Leave_Application.php">Leave Application</a>
        <a class="navigation-link" href="logout.php">Logout</a>
      </div>
    </div>
  </nav>
</header>

<div class="tab-container">
    <div class="tab-header">
        <div class="tab active" data-tab="project-creation">Project Creation</div>
        <div class="tab" data-tab="assign-employee">Assign Employee</div>
    </div>

    <div class="tab-content active" id="project-creation">
        <div class="leavecontainer">
            <h1>Project Creation</h1>
            <form action="" method="post">
                <label for="project_name_create">Project Name:</label>
                <input type="text" id="project_name_create" name="project_name">

                <label for="description_create">Description:</label>
                <textarea id="description_create" name="description" rows="4" cols="50"></textarea>

                <label for="deadline_create">Deadline:</label>
                <input type="date" id="deadline_create" name="deadline">

                <button type="submit" name="create_project">Create Project</button>
            </form>
            <?php echo $message; ?>
        </div>
    </div>

    <div class="tab-content" id="assign-employee">
        <div class="leavecontainer">
            <h1>Assign Employee</h1>
            <form action="" method="post">
                <label for="project_search">Search Project:</label>
                <input type="text" id="project_search" name="project_search" onkeyup="searchProject()">

                <button type="button" onclick="searchProject()">Search</button>

                <label for="project_id_assign">Project ID:</label>
                <input type="text" id="project_id_assign" name="project_id_assign" readonly>

                <div id="employee_list"></div>
            </form>
            <?php echo $message; ?>
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
                assign_employee: true,
                project_id_assign: project_id,
                employee_id: employee_id,
                assign_date: assign_date
            },
            success: function(response) {
                alert('Employee assigned successfully');
                fetchUnassignedEmployees(); 
            }
        });
    }
</script>
</body>
</html>
