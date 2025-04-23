<?php session_start();
require_once "CommonFunctions/Functions.php";
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Simple Responsive Admin</title>
    <!-- BOOTSTRAP STYLES-->
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <!-- FONTAWESOME STYLES-->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- CUSTOM STYLES-->
    <link href="assets/css/custom.css" rel="stylesheet" />
    <!-- GOOGLE FONTS-->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
</head>

<body>



    <div id="wrapper">
        <div class="navbar navbar-inverse navbar-fixed-top">
            <div class="adjust-nav">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".sidebar-collapse">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="btn btn-primary" disabled>
                        <?php echo "Hi, " . $_SESSION['User']['name']; ?>
                    </a>
                </div>

                <span class="logout-spn">
                    <a href="Routing.php?route=admins-logout" style="color:#fff;">LOGOUT</a>

                </span>
            </div>
        </div>
        <!-- /. NAV TOP  -->
        <nav class="navbar-default navbar-side" role="navigation">
            <div class="sidebar-collapse">
                <ul class="nav" id="main-menu">


                    <li>
                        <a href="index.php"><i class="fa fa-desktop "></i>Tasks <span class="badge"></span></a>
                    </li>
                    <?php if (in_array('SA', explode(',', $_SESSION['User']['roles']))) {
                    ?>
                        <li>
                            <a href="users.php"><i class="fa fa-edit "></i>Users <span class="badge"></span></a>
                        </li>
                        <li>
                            <a href="Routing.php?route=users-downloanTaskReportCSV"><i class="fa fa-table "></i>Reports <span class="badge">Click To Download</span></a>
                        </li>
                    <?php } ?>
                    <!-- <li class="active-link">
                        <a href="blank.html"><i class="fa fa-edit "></i>Blank Page <span class="badge"></span></a>
                    </li>



                    <li>
                        <a href="#"><i class="fa fa-qrcode "></i>My Link One</a>
                    </li>
                    <li>
                        <a href="#"><i class="fa fa-bar-chart-o"></i>My Link Two</a>
                    </li>

                    <li>
                        <a href="#"><i class="fa fa-edit "></i>My Link Three </a>
                    </li>
                    <li>
                        <a href="#"><i class="fa fa-table "></i>My Link Four</a>
                    </li>
                    <li>
                        <a href="#"><i class="fa fa-edit "></i>My Link Five </a>
                    </li> -->
                </ul>
            </div>

        </nav>
        <!-- /. NAV SIDE  -->