<!DOCTYPE HTML>
<html>

<head>
    <title>Employee Profile</title>
    <!-- Latest compiled and minified Bootstrap CSS -->
    <?php include 'head.php'; ?>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <div class="wrapper">
        <!-- Sidebar  -->
        <nav id="sidebar">
            <div class="sidebar-header">
                <h5 class="ms-4">Welcome</h5>
            </div>
            <ul class="list-unstyled components mt-5">
                <li>
                    <a class="text-decoration-none" aria-current="page" href="index.php">Dashboard</a>
                </li>

                <li>
                    <a href="#pagemenu" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle text-decoration-none">Employee</a>
                    <ul class="collapse list-unstyled" id="pagemenu">
                        <li>
                            <a class="nav-link text-decoration-none" href="employee_create.php">Create employee</a>
                        </li>
                        <li>
                            <a class="nav-link text-decoration-none" href="employee_read.php">Employee List</a>
                        </li>
                    </ul>
                </li>

                <li>
                    <a href="#pageSubmenu" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle text-decoration-none">Product</a>
                    <ul class="collapse list-unstyled" id="pageSubmenu">
                        <li>
                            <a class="nav-link text-decoration-none" href="leave_apply.php">Apply Leave</a>
                        </li>
                        <li>
                            <a class="nav-link text-decoration-none" href="leave_read.php">Leave List</a>
                        </li>

                    </ul>
                </li>
                <li>
                    <a href="#pageSubmenu1" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle text-decoration-none">Order</a>
                    <ul class="collapse list-unstyled" id="pageSubmenu1">
                        <li>
                            <a class="nav-link text-decoration-none" href="order_create.php">Create New Order</a>
                        </li>
                        <li>
                            <a class="nav-link text-decoration-none" href="order_summary.php">Order Summary</a>
                        </li>

                    </ul>
                </li>
                <li>
                    <a class="nav-link text-decoration-none" href="contact_us.php">Contact Us</a>
                </li>

            </ul>
            <ul class="list-unstyled CTAs">
                <li>
                    <a class="nav-link text-white text-decoration-none" href="logout.php"> <i class="fa-solid fa-right-from-bracket">Logout</i> Logout </a>
                </li>

            </ul>
        </nav>

        <!-- Page Content  -->
        <div id="content">
            <div class="container-fluid mb-5">
                <button type="button" id="sidebarCollapse" class="btn ">
                    <i class="fas fa-align-left"></i>
                    <span>Sidebar</span>
                </button>
            </div>


            <div id="wrapper">
                <div id="content-wrapper">
                    <div class="container-fluid">
                        <div class="card mb-3">
                            <div class="card-header">
                                <i class="fa fa-info-circle"></i>
                                View Your Details
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>S.No.</th>
                                                <th>Leave Type</th>
                                                <th>Leave Category</th>
                                                <th>Leave Start Date</th>
                                                <th>Leave End Date</th>
                                                <th>Description</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

<?php include 'script.php'; ?>

</html>