<?php
session_start();
if (!isset($_SESSION['User']['id']) || empty($_SESSION['User']['id'])) {
    header('Location: login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --theme-color: #214761;
            --theme-light: #3a6a94;
            --theme-lighter: #e8f0f7;
        }

        body {
            background-color: #f8f9fa;
        }

        .login-container {
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-card {
            width: 100%;
            max-width: 400px;
            padding: 20px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .btn-primary {
            background-color: var(--theme-color);
            border-color: var(--theme-color);
        }

        .btn-primary:hover {
            background-color: var(--theme-light);
            border-color: var(--theme-light);
        }

        .form-check-input:checked {
            background-color: var(--theme-color);
            border-color: var(--theme-color);
        }

        .card-title {
            color: var(--theme-color);
        }

        a {
            color: var(--theme-color);
        }

        .form-control:focus {
            border-color: var(--theme-color);
            box-shadow: 0 0 0 0.25rem rgba(33, 71, 97, 0.25);
        }
    </style>
</head>

<body>
    <div class="login-container">
        <div class="login-card card">
            <div class="card-body">
                <h2 class="card-title text-center mb-4">Reset Password For User: <?php echo $_SESSION['User']['name'] ?></h2>
                <form>
                    <!-- <input type="hidden" name="route" value="admins-login" /> -->
                    <div class="mb-3">
                        <label for="username" class="form-label">Password</label>
                        <input type="password" class="form-control" name="password" id="password" placeholder="Enter Password" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Confirm Password</label>
                        <input type="password" class="form-control" name="cnf_password" id="cnf_password" placeholder="Confirm password" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Reset Password</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
<script>
    $(document).ready(function() {
    $('form').on('submit', function(e) {
        e.preventDefault();
        
        const cnf_password = $('#cnf_password').val();
        const password = $('#password').val();
        
        const formData = {
            user_id: <?php echo $_SESSION['User']['id']; ?>,
            password: password,
            cnf_password: cnf_password,
            route: "admins-resetPassword",
        };
        
        $.ajax({
            url: 'Routing.php?debug=1',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    window.location.href = response.redirect || 'index.php';
                } else {
                    // Show error message
                    alert(response.error || 'Password Reset Failed. Please try again.');
                }
            },
            error: function(xhr, status, error) {
                // Handle errors
                console.error('AJAX Error:', status, error);
                alert('An error occurred during this task. Please try again.');
            }
        });
    });
});
</script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    function getQueryParams() {
        const params = new URLSearchParams(window.location.search);
        const result = {};
        for (const [key, value] of params.entries()) {
            result[key] = value;
        }
        return result;
    }
    
    function clearQueryParams() {
        window.history.replaceState({}, document.title, window.location.pathname);
    }
    
    const queryParams = getQueryParams();
    if (Object.keys(queryParams).length > 0) {
        let alertMessage = "Query Parameters:\n";
        for (const [key, value] of Object.entries(queryParams)) {
            alertMessage += `${key}: ${value}\n`;
        }
        alert(alertMessage);
        clearQueryParams();
    }
});
</script>