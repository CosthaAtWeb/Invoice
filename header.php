<?php
	//check login
	include("session.php");
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Invoice Management System</title>
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

  <!-- Google Fonts — load FIRST -->
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&family=Sora:wght@600;700&display=swap" rel="stylesheet">

  <!-- Font Awesome & Ionicons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">

  <!-- Bootstrap -->
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link rel="stylesheet" href="css/bootstrap.datetimepicker.css">

  <!-- DataTables -->
  <link rel="stylesheet" href="//cdn.datatables.net/1.10.7/css/jquery.dataTables.css">
  <link rel="stylesheet" href="//cdn.datatables.net/plug-ins/1.10.7/integration/bootstrap/3/dataTables.bootstrap.css">

  <!-- AdminLTE CORE — MUST be enabled for layout to work -->
  <link rel="stylesheet" href="css/AdminLTE.css">

  <!-- stylesNew.css LAST — overrides colors/fonts on top of AdminLTE -->
  <link rel="stylesheet" href="css/stylesNew.css">

  <!-- JS -->
  <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
  <script src="js/moment.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="//cdn.datatables.net/1.10.7/js/jquery.dataTables.js"></script>
  <script src="//cdn.datatables.net/plug-ins/1.10.7/integration/bootstrap/3/dataTables.bootstrap.js"></script>
  <script src="js/bootstrap.datetime.js"></script>
  <script src="js/bootstrap.password.js"></script>
  <script src="js/scripts.js"></script>
  <script src="js/app.min.js"></script>

</head>

<body class="hold-transition skin-green sidebar-mini">
<div class="wrapper">

  <!-- Main Header -->
  <header class="main-header">

    <!-- Logo -->
    <a href="" class="logo">
      <span class="logo-mini"><b>IN</b>MS</span>
      <span class="logo-lg" style="text-decoration:none;"><b>Invoice</b> System</span>
    </a>

    <!-- Header Navbar -->
    <nav class="navbar navbar-static-top" role="navigation">
      <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>
      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <img src="https://pcrt.crab.org/images/default-user.png" class="user-image" alt="User Image">
              <span class="hidden-xs"><?php echo $_SESSION['login_username'];?></span>
            </a>
            <ul class="dropdown-menu">
              <li><a href="logout.php" class="btn btn-default btn-flat">Log out</a></li>
            </ul>
          </li>
        </ul>
      </div>
    </nav>
  </header>

  <!-- Left Sidebar -->
  <aside class="main-sidebar">
    <section class="sidebar">
      <ul class="sidebar-menu">
        <li class="header">MENU</li>

        <li class="treeview">
          <a href="dashboard.php"><i class="fa fa-tachometer"></i> <span>Dashboard</span></a>
        </li>

        <li class="treeview">
          <a href="#"><i class="fa fa-file-text"></i> <span>Invoices</span>
            <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
          </a>
          <ul class="treeview-menu">
            <li><a href="invoice-create.php"><i class="fa fa-plus"></i> Create Invoice</a></li>
            <li><a href="invoice-list.php"><i class="fa fa-cog"></i> Manage Invoices</a></li>
            <li><a href="#" class="download-csv"><i class="fa fa-download"></i> Download CSV</a></li>
          </ul>
        </li>

        <li class="treeview">
          <a href="#"><i class="fa fa-archive"></i> <span>Products</span>
            <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
          </a>
          <ul class="treeview-menu">
            <li><a href="product-add.php"><i class="fa fa-plus"></i> Add Products</a></li>
            <li><a href="product-list.php"><i class="fa fa-cog"></i> Manage Products</a></li>
          </ul>
        </li>

        <li class="treeview">
          <a href="#"><i class="fa fa-users"></i> <span>Customers</span>
            <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
          </a>
          <ul class="treeview-menu">
            <li><a href="customer-add.php"><i class="fa fa-user-plus"></i> Add Customer</a></li>
            <li><a href="customer-list.php"><i class="fa fa-cog"></i> Manage Customers</a></li>
          </ul>
        </li>

        <li class="treeview">
          <a href="#"><i class="fa fa-user"></i> <span>System Users</span>
            <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
          </a>
          <ul class="treeview-menu">
            <li><a href="user-add.php"><i class="fa fa-plus"></i> Add User</a></li>
            <li><a href="user-list.php"><i class="fa fa-cog"></i> Manage Users</a></li>
          </ul>
        </li>

      </ul>
    </section>
  </aside>

  <!-- Content Wrapper -->
  <div class="content-wrapper">
    <section class="content">
      <!-- Page content goes here -->