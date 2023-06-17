<div class="wrapper">
    <!-- Sidebar  -->
    <nav id="sidebar">
        <div class="sidebar-header">
            <h5 class="ms-4">Welcome, <?php echo $_SESSION['login']; ?></h5>
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
                <a href="#pageSubmenu" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle text-decoration-none">Leave</a>
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
                <a href="#pageSubmenu1" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle text-decoration-none">Work Tour</a>
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
                <a href="password_update.php" class="text-decoration-none">Change Password</a>
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
        <div class="container-fluid">
            <button type="button" id="sidebarCollapse" class="btn ">
                <i class="fas fa-align-left"></i>
                <span>Sidebar</span>
            </button>
        </div>