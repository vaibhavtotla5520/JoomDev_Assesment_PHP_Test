<?php include_once "Header.php";
if (!isset($_SESSION['User']['id']) && empty($_SESSION['User']['id'])) {
    header('Location:login.php');
}
?>
<div id="page-wrapper">
    <div id="page-inner">
        <div class="row">
            <div class="col-md-12">
                <h2>Users </h2>
            </div>
        </div>
        <!-- /. ROW  -->
        <hr />
        <nav class="navbar navbar-light bg-light">
            <?php if (in_array('SA', explode(',', $_SESSION['User']['roles']))) {
            ?>
                <button class="btn btn-success my-2 my-sm-0" data-toggle="modal" data-target="#create_user">CREATE</button>
            <?php } ?>
        </nav>
        <!-- /. ROW  -->
        <!-- CREATE USER MODAL -->
        <div class="modal fade" id="create_user" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Create New User</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="userCreateForm">
                            <div class="form-group">
                                <label for="firstName">First Name</label>
                                <input type="text" class="form-control" id="firstname" name="firstName" required>
                            </div>

                            <div class="form-group">
                                <label for="lastName">Last Name</label>
                                <input type="text" class="form-control" id="lastname" name="lastName" required>
                            </div>

                            <div class="form-group">
                                <label for="email">Email Address</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>

                            <div class="form-group">
                                <label for="phone">Phone Number</label>
                                <input type="tel" class="form-control" id="phone" name="phone" required>
                            </div>

                            <div class="form-group">
                                <label for="password">Password</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="password" name="password" required>
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary" type="button" id="generatePassword">
                                            <i class="fas fa-sync-alt"></i> Generate
                                        </button>
                                    </div>
                                </div>
                                <small class="form-text text-muted">Password will be shown in plain text for copying</small>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" id="saveUser">Create User</button>
                    </div>
                </div>
            </div>
        </div>
        <table class="table table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Last Login</th>
                    <th>Last Password Change</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>
    <!-- /. PAGE INNER  -->
</div>
<!-- /. PAGE WRAPPER  -->
</div>
<script>
    $(document).ready(function() {
        $.ajax({
            url: 'Routing.php',
            data: {
                route: "users-list"
            },
            type: 'POST',
            dataType: 'json',
            success: function(userData) {
                var tbody = document.querySelector('.table tbody');
                tbody.innerHTML = '';
                userData.forEach(function(user) {
                    var row = document.createElement('tr');
                    row.innerHTML = `
            <td>${user.user_id}</td>
            <td>${user.user_firstname}</td>
            <td>${user.user_lastname}</td>
            <td>${user.user_email}</td>
            <td>${user.user_phone}</td>
            <td>${user.user_last_login}</td>
            <td>${user.user_last_password_change_timestamp}</td>
            <td>${user.user_active === "1" ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-danger">Inactive</span>'}</td>
        `;
                    tbody.appendChild(row);
                });
            }
        });

        // Password generation function
        function generatePassword() {
            const length = 12;
            const charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_+~`|}{[]:;?><,./-=";
            let password = "";

            for (let i = 0; i < length; i++) {
                const randomIndex = Math.floor(Math.random() * charset.length);
                password += charset[randomIndex];
            }

            return password;
        }

        // Generate password button click handler
        $('#generatePassword').click(function() {
            $('#password').val(generatePassword());
        });

        // Save user button click handler
        $('#saveUser').click(function() {
            const formData = {
                firstname: $('#firstname').val(),
                lastname: $('#lastname').val(),
                email: $('#email').val(),
                phone: $('#phone').val(),
                password: $('#password').val(),
                route: "users-add"
            };

            console.log("User data to be saved:", formData);

            $.ajax({
                url: 'Routing.php',
                data: formData,
                type: 'POST',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        $('#create_user').modal('hide');
                        alert(response.success);
                    } else {
                        alert(response.error);
                    }
                },
                error: function(error) {
                    alert("Error creating user:", response.error);
                }
            });
        });
    });
</script>
<?php include_once "Footer.php"; ?>