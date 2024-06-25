<?php


use Classes\Session;
use Classes\FetchDisplayData;
use Classes\Message;

require_once "../vendor/autoload.php";
Session::CheckSession();
$userData = FetchDisplayData::DisplayCurrentUser();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Recipes</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../assets/plugins/fontawesome-free/css/all.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Tempusdominus Bootstrap 4 -->
    <link rel="stylesheet" href="../assets/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="../assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- JQVMap -->
    <link rel="stylesheet" href="../assets/plugins/jqvmap/jqvmap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../assets/dist/css/adminlte.min.css">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="../assets/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="../assets/plugins/daterangepicker/daterangepicker.css">
    <!-- summernote -->
    <link rel="stylesheet" href="../assets/plugins/summernote/summernote-bs4.min.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="../assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="../assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="../assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    <link rel="stylesheet" href="../assets/dist/css/custom.style.css">
</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">

        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="/Recipe_Sharing_Web/index.php" class="nav-link">Home</a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="/Recipe_Sharing_Web/login.php" class="nav-link">Logout</a>
                </li>
            </ul>

            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
                <!-- Profile Dropdown Menu -->
                <li class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="#">
                        <img src="<?php echo FetchDisplayData::DisplayUserImg() ?>" alt="User Image" class="img-circle elevation-2" style="width: 30px; height: 30px;">
               <span><?php echo $userData['UserName'] ?></span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                        <div class="dropdown-divider"></div>
                        <a href="/Recipe_Sharing_Web/chef_page/profile/update_photo.php" class="dropdown-item">
                            <i class="fas fa-user-circle mr-2"></i> Update Photo
                        </a>
                        <a href="/Recipe_Sharing_Web/chef_page/profile/account_settings.php" class="dropdown-item">
                            <i class="fas fa-cog mr-2"></i> Account Settings
                        </a>
                        <a href="/Recipe_Sharing_Web/chef_page/profile/change_password.php" class="dropdown-item">
                            <i class="fas fa-key mr-2"></i> Change Password
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="/Recipe_Sharing_Web/login.php" class="dropdown-item dropdown-footer">Logout</a>
                    </div>
                </li>
            </ul>

        </nav>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <!-- Brand Logo -->
            <a href="/Recipe_Sharing_Web/index.php" class="brand-link">
                <img src="../assets/dist/img/logoWeb.jpg" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
                <span class="brand-text font-weight-light">Recipe Oasis</span>
            </a>

            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Sidebar user panel (optional) -->
                <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                    <div class="image">
                        <img src="<?php echo FetchDisplayData::DisplayUserImg()?>" class="img-circle elevation-2" alt="User Image">
                    </div>
                    <div class="info">
                       <a href="#" class="d-block"><?php echo $userData['UserName'] ?></a>
                    </div>
                </div>

                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                        <li class="nav-item">
                            <a href="index.php" class="nav-link">
                                <i class="nav-icon fas fa-th"></i>
                                <p>
                                    Dashboard
                                </p>
                            </a>
                        </li>
 
                        <li class="nav-item">
                            <a href="recipe.page.php" class="nav-link active">
                                <i class="nav-icon fas fa-utensils"></i>
                                <p>
                                    Recipes
                                </p>
                            </a>
                        </li>
                    </ul>
                </nav>
                <!-- /.sidebar-menu -->
            </div>
            <!-- /.sidebar -->
        </aside>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">Meal Type</h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item active">Recipe Managements</li>
                            </ol>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->


            <!-- Main content -->
            <div class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h3 class="card-title">Recipes <span><?php Message::DisplayMessage() ?></h3>
                                    <a href="RecipePageActions/adding_recipe.php" class="btn btn-success btn-sm ml-auto" style="font-size: 18px;">
                                        +<span style="font-size: 14px;"> Add New</span>
                                    </a>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <table id="recipeCategories" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>Image</th>
                                                <th>Recipe Name</th>
                                                <th>Meal Type</th>
                                                <th>Added Date</th>
                                                <th style="width: 109px;">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            FetchDisplayData::DisplayRecipeTableUser()
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                                <!-- /.card-body -->
                            </div>
                            <!-- /.card -->
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content -->



        </div>
        <!-- /.content-wrapper -->

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
            <div class="p-3">
                <h5>Title</h5>
                <p>Sidebar content</p>
            </div>
        </aside>
        <!-- /.control-sidebar -->

        <!-- Main Footer -->
        <footer class="main-footer">
            <!-- To the right -->
            <div class="float-right d-none d-sm-inline">
                This website is created for fun
            </div>
            <!-- Default to the left -->
            <strong>Copyright &copy; Reymart Calicdan <a href="https://adminlte.io">AdminLTE.io</a>.</strong> All rights reserved.
        </footer>
    </div>
    <!-- ./wrapper -->

    <!-- REQUIRED SCRIPTS -->

    <!-- jQuery -->
    <script src="../assets/plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="../assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="../assets/dist/js/adminlte.min.js"></script>
    <!-- AdminLTE Dashboard -->
    <!-- DataTables -->
    <script src="../assets/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="../assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="../assets/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
    <script src="../assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
    <script src="../assets/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
    <script src="../assets/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#recipeCategories').DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
            });
        });
    </script>
</body>

</html>