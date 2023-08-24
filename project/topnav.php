<?php
$uid = $_SESSION['user_id'];
$role = $_SESSION['role'];
?>


<div class="wrapper">
    <!-- Sidebar  -->
<div class="">
    <nav id="sidebar">
        <div class="sidebar-header">
            <h5 class="ms-4">Welcome, <?php echo $_SESSION['login']; ?></h5>
       
        </div>


        <ul class="list-unstyled components mt-5">
            <li>
                <a class="text-decoration-none nav-link" aria-current="page" href="employee_read_one.php?user_id=<?php echo $uid; ?>">My Profile <i class="fa-regular fa-id-card"></i></a>
            </li>

            <?php if ($role == 1) : ?>
                <li>
                    <a class="text-decoration-none nav-link" aria-current="page" href="index.php">Dashboard <i class="fa-solid fa-chalkboard-user"></i></a>
                </li>


                <li>
                    <a href="#pagemenu" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle text-decoration-none nav-link">Employee <i class="fa-solid fa-clipboard-user"></i></a>
                    <ul class="collapse list-unstyled" id="pagemenu">

                        <li>
                            <a class="nav-link text-decoration-none" href="employee_create.php">Create Employee</a>
                        </li>

                        <li>
                            <a class="nav-link text-decoration-none" href="employee_read.php">Employee List</a>
                        </li>
                    </ul>
                </li>
            <?php endif; ?>

            <li>
                <a href="#pageSubmenu1" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle text-decoration-none nav-link">Work Tour <i class="fa-solid fa-briefcase"></i></a>
                <ul class="collapse list-unstyled" id="pageSubmenu1">
                    <li>
                        <a class="nav-link text-decoration-none" href="tour_apply.php">Apply Work Tour</a>
                    </li>
                    <li>
                        <a class="nav-link text-decoration-none" href="tour_read.php">Work Tour Record</a>
                    </li>

                </ul>
            </li>

            <li>
                <a href="#pageSubmenu" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle text-decoration-none nav-link">Leave <i class="fa-regular fa-envelope"></i></a>
                <ul class="collapse list-unstyled" id="pageSubmenu">
                    <li>
                        <a class="nav-link text-decoration-none" href="leave_apply.php">Apply Leave</a>
                    </li>
                    <li>
                        <a class="nav-link text-decoration-none" href="leave_read.php">Leave Record</a>
                    </li>

                </ul>
            </li>

            <li>
                <a href="#pageSubmenu2" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle text-decoration-none nav-link"> Notice <i class="fa-solid fa-bullhorn"></i></a>
                <ul class="collapse list-unstyled" id="pageSubmenu2">
                    <?php if ($role >= 1) : ?>
                        <li>
                            <a class="nav-link text-decoration-none" href="notice.php">Add  Notice</a>
                        </li>
                    <?php endif; ?>
                    <li>
                        <a class="nav-link text-decoration-none" href="notice_read.php">Read  Notice</a>
                    </li>
                </ul>
            </li>

            <li>
                <?php
                echo "<a class='nav-link text-decoration-none' href='password_update.php?user_id=$uid'>Change Password <i class='fa-solid fa-key'></i></a>";
                ?>
            </li>

        </ul>

        <ul class="list-unstyled CTAs">
            <li>
                <a class="nav-link text-white text-decoration-none" href="logout.php">  Logout <i class="fa-solid fa-right-from-bracket"></i></a>
            </li>

        </ul>
    </nav>
</div>


    <!-- Page Content  -->
    <div id="content" >
        <div class="container-fluid">
            <button type="button" id="sidebarCollapse" class="btn ">
                <i class="fas fa-align-left"></i>
                <span>Sidebar</span>
            </button>
        </div>