<?php
$uid = $_SESSION['user_id'];
?>


<div class="wrapper">
    <!-- Sidebar  -->


    <nav id="sidebar">
        <div class="sidebar-header">
            <h5 class="ms-4">Welcome, <?php echo $_SESSION['login']; ?></h5>
        </div>


        <ul class="list-unstyled components mt-5">
            <li>
                <a class="text-decoration-none nav-link" aria-current="page" href="index.php">Dashboard</a>
            </li>

            <li>
                <a href="#pagemenu" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle text-decoration-none nav-link">Employee</a>
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
                <a href="#pageSubmenu" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle text-decoration-none nav-link">Leave</a>
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
                <a href="#pageSubmenu1" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle text-decoration-none nav-link">Work Tour</a>
                <ul class="collapse list-unstyled" id="pageSubmenu1">
                    <li>
                        <a class="nav-link text-decoration-none" href="tour_apply.php">Apply Work Tour</a>
                    </li>
                    <li>
                        <a class="nav-link text-decoration-none" href="tour_read.php">Work Tour List</a>
                    </li>

                </ul>
            </li>

            <li>
                <a href="#pageSubmenu2" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle text-decoration-none nav-link">Announcement</a>
                <ul class="collapse list-unstyled" id="pageSubmenu2">
                    <li>
                        <a class="nav-link text-decoration-none" href="notice.php">Notification</a>
                    </li>
                    <li>
                        <a class="nav-link text-decoration-none" href="notice_read.php">Read Notification</a>
                    </li>
                </ul>
            </li>
            
            <li>
                <?php
                $uid = $_SESSION['user_id'];
                echo "<a class='nav-link text-decoration-none' href='password_update.php?user_id=$uid'>Change Password</a>";
                ?>
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