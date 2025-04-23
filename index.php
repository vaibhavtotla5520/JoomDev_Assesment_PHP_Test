<?php include_once "Header.php";
if (!isset($_SESSION['User']['id']) && empty($_SESSION['User']['id'])) {
    header('Location:login.php');
} else {
    // echo "<pre>";
    // print_r($_SESSION);
    // die;
    // session_destroy();
    if ($_SESSION['User']['last_password_change_days'] == "NEW") {
        redirect('reset_password.php', 'Welcome, Reset Your Password');
    } else if ((int) $_SESSION['User']['last_password_change_days'] > 30) {
        redirect('reset_password.php', 'Password Getting Old, Change Now');
    } else {
?>
        <div id="page-wrapper">
            <div id="page-inner">
                <div class="row">
                    <div class="col-md-12">
                        <h2>Task Board </h2>
                    </div>
                </div>
                <!-- /. ROW  -->
                <hr />
                <nav class="navbar navbar-light bg-light">
                    <?php if (in_array('US', explode(',', $_SESSION['User']['roles']))) {
                    ?>
                        <button class="btn btn-success my-2 my-sm-0" data-toggle="modal" data-target="#add_task">ADD TASK</button>
                    <?php
                    } ?>
                </nav>
                <!-- /. ROW  -->
                <!-- ADD TASK MODAL -->
                <div class="modal fade" id="add_task" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Add New Task</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form id="taskCreateForm">
                                    <div class="form-group">
                                        <label for="taskName">Task Name</label>
                                        <input type="text" class="form-control" id="taskName" name="taskName" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="startTime">Start Time</label>
                                        <input type="datetime-local" class="form-control" id="startTime" name="startTime" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="stopTime">Stop Time</label>
                                        <input type="datetime-local" class="form-control" id="stopTime" name="stopTime">
                                    </div>

                                    <div class="form-group">
                                        <label for="description">Description</label>
                                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                                    </div>

                                    <div class="form-group">
                                        <label for="notes">Notes</label>
                                        <textarea class="form-control" id="notes" name="notes" rows="2"></textarea>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary" id="saveTask">Save Task</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- UPDATE TASK MODAL -->
                <div class="modal fade" id="editTaskModal" tabindex="-1" role="dialog">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Edit Task</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" id="taskId">
                                <div class="form-group">
                                    <label for="taskName">Task Name</label>
                                    <input type="text" class="form-control" id="taskName" required>
                                </div>
                                <div class="form-group">
                                    <label for="startTime">Start Time</label>
                                    <input type="datetime-local" class="form-control" id="startTime" required>
                                </div>
                                <div class="form-group">
                                    <label for="stopTime">Stop Time</label>
                                    <input type="datetime-local" class="form-control" id="stopTime">
                                </div>
                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <textarea class="form-control" id="description" rows="3"></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="notes">Notes</label>
                                    <textarea class="form-control" id="notes" rows="2"></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary" id="updateTask">Update Task</button>
                            </div>
                        </div>
                    </div>
                </div>
                <table class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Task By</th>
                            <th>Task Name</th>
                            <th>Start Time</th>
                            <th>Stop Time</th>
                            <th>Description</th>
                            <th>Notes</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
            <!-- /. PAGE WRAPPER  -->
        </div>
        <script>
            $(document).ready(function() {
                // Load task list
                $.ajax({
                    url: 'Routing.php',
                    data: {
                        route: "users-taskList"
                    },
                    type: 'POST',
                    dataType: 'json',
                    success: function(taskData) {
                        console.log(taskData);
                        var tbody = document.querySelector('.table tbody');
                        tbody.innerHTML = '';
                        taskData.forEach(function(task) {
                            var row = document.createElement('tr');
                            row.innerHTML = `
                        <td>${task.task_id}</td>
                        <td>${task.user_firstname}</td>
                        <td>${task.task_name}</td>
                        <td>${formatDateTime(task.task_start_time)}</td>
                        <td>${formatDateTime(task.task_stop_time)}</td>
                        <td>${task.task_description || ''}</td>
                        <td>${task.task_notes || ''}</td>
                        <?php if (in_array('US', explode(',', $_SESSION['User']['roles']))) { ?>
                        <td><button class="btn btn-sm btn-primary edit-task" data-id="${task.task_id}">Edit</button></td>
                        <?php } ?>
                    `;
                            tbody.appendChild(row);
                        });
                    }
                });

                // Save task button click handler
                $('#saveTask').click(function() {
                    const formData = {
                        taskName: $('#taskName').val(),
                        startTime: $('#startTime').val(),
                        stopTime: $('#stopTime').val(),
                        description: $('#description').val(),
                        notes: $('#notes').val(),
                        route: "users-addTask" // MUST keep this exact route name as per your Routing.php
                    };

                    console.log("Task data to be saved:", formData);

                    $.ajax({
                        url: 'Routing.php',
                        data: formData,
                        type: 'POST',
                        dataType: 'json',
                        success: function(response) {
                            if (response && response.success) {
                                $('#add_task').modal('hide');
                                alert(response.success);
                                location.reload();
                            } else {
                                alert(response ? response.error : "Empty response from server");
                            }
                        },
                        error: function(xhr, status, error) {
                            try {
                                // Try to parse the response as JSON first
                                const jsonResponse = JSON.parse(xhr.responseText);
                                alert(jsonResponse.error || "Unknown error occurred");
                            } catch (e) {
                                // If not JSON, show raw response
                                alert("Server response: " + xhr.responseText);
                            }
                        }
                    });
                });

                // Helper function to format date/time
                function formatDateTime(timestamp) {
                    if (!timestamp) return '-';
                    const date = new Date(timestamp);
                    return date.toLocaleString(); // Adjust format as needed
                }

                $(document).on('click', '.edit-task', function() {
                    var taskId = $(this).data('id');

                    $.ajax({
                        url: 'Routing.php',
                        data: {
                            route: "users-getTask",
                            task_id: taskId
                        },
                        type: 'POST',
                        dataType: 'json',
                        success: function(taskData) {
                            $('#editTaskModal').modal('show');
                            $('#editTaskModal #taskId').val(taskData.task_id);
                            $('#editTaskModal #taskName').val(taskData.task_name);

                            $('#editTaskModal #startTime').val(
                                taskData.task_start_time ?
                                taskData.task_start_time.replace(' ', 'T').substring(0, 16) :
                                ''
                            );
                            $('#editTaskModal #stopTime').val(
                                taskData.task_stop_time ?
                                taskData.task_stop_time.replace(' ', 'T').substring(0, 16) :
                                ''
                            );
                            $('#editTaskModal #description').val(taskData.task_description || '');
                            $('#editTaskModal #notes').val(taskData.task_notes || '');
                        }
                    });
                });

                $('#updateTask').click(function() {
                    const formData = {
                        taskName: $('#editTaskModal #taskName').val(),
                        startTime: $('#editTaskModal #startTime').val(),
                        stopTime: $('#editTaskModal #stopTime').val(),
                        description: $('#editTaskModal #description').val(),
                        notes: $('#editTaskModal #notes').val(),
                        task_id: $('#editTaskModal #taskId').val(),
                        route: "users-updateTask"
                    };

                    $.ajax({
                        url: 'Routing.php',
                        data: formData,
                        type: 'POST',
                        dataType: 'json',
                        success: function(response) {
                            if (response.success) {
                                $('#editTaskModal').modal('hide');
                                alert(response.success);
                                location.reload();
                            } else {
                                alert(response.error);
                            }
                        },
                        error: function(xhr, status, error) {
                            alert("Error updating task: " + error);
                        }
                    });
                });
            });
        </script>
        <?php include_once "Footer.php"; ?>
<?php
    }
}

?>