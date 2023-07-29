<?php
include 'check_user_login.php';
?>
<!DOCTYPE HTML>
<html>

<head>
    <title>Employee Profile</title>
    <!-- Latest compiled and minified Bootstrap CSS -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/x-icon" href="images/online-shopping.png">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.2.2/css/buttons.bootstrap.min.css">
    <script src="https://use.fontawesome.com/releases/v6.1.0/js/all.js" crossorigin="anonymous"></script>
    <link href='https://fonts.googleapis.com/css?family=Open Sans' rel='stylesheet'>
    <link rel="stylesheet" href="style1.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
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
                            <a class="nav-link text-decoration-none" href="order_create.php">Create New Order</a>
                        </li>
                        <li>
                            <a class="nav-link text-decoration-none" href="order_summary.php">Order Summary</a>
                        </li>

                    </ul>
                </li>

                <li>
                    <a href="#pageSubmenu2" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle text-decoration-none nav-link">Notice</a>
                    <ul class="collapse list-unstyled" id="pageSubmenu2">
                        <li>
                            <a class=" nav-link text-decoration-none" href="notice.php"> Notification

                                <i class=" ms-5 fa-solid fa-bell"></i>
                            </a>

                        <li>
                            <a class="nav-link text-decoration-none" href="notice_read.php"> Read Notification

                                <i class=" ms-5 fa-solid fa-bell"></i>
                            </a>
                        </li>
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
                                            <?php
                                            // Create 20 dummy rows
                                            for ($i = 1; $i <= 20; $i++) {
                                                echo "<tr>";
                                                echo "<td>{$i}</td>";
                                                echo "<td>Leave Type {$i}</td>";
                                                echo "<td>Leave Category {$i}</td>";
                                                echo "<td>Leave Start Date {$i}</td>";
                                                echo "<td>Leave End Date {$i}</td>";
                                                echo "<td>Description {$i}</td>";
                                                echo "<td>Status {$i}</td>";
                                                echo "</tr>";
                                            }
                                            ?>
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

<!-- jQuery CDN - Slim version (=without AJAX) -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<!-- Popper.JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('#sidebarCollapse').on('click', function() {
            $('#sidebar').toggleClass('active');
        });

        $('.dropdown-toggle').on('click', function() {
            var submenu = $(this).next('.collapse');
            var otherSubmenus = $('.collapse').not(submenu);

            if (submenu.hasClass('show')) {
                submenu.removeClass('show');
            } else {
                otherSubmenus.removeClass('show');
                submenu.addClass('show');
            }
        });
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"> </script>

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.2/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.2/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        var table = $('#example')[0]; // Get the table element

        var switching = true;
        while (switching) {
            switching = false;
            var rows = table.rows;
            for (var i = 1; i < (rows.length - 1); i++) {
                var shouldSwitch = false;
                var x = rows[i].getElementsByTagName("td")[0];
                var y = rows[i + 1].getElementsByTagName("td")[0];
                if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
                    shouldSwitch = true;
                    break;
                }
            }
            if (shouldSwitch) {
                rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
                switching = true;
            }
        }

        // DataTable initialization
        $('#example').DataTable({
            "paging": true,
            "autoWidth": true,
            "columnDefs": [{
                    "orderable": false,
                    "targets": [6]
                }, // Disable sorting for the "Action" column
                {
                    "type": "num",
                    "targets": [5]
                } // Set the sorting type for Column 5 as numeric
            ],
            "order": [
                [5, "asc"],
                [0, "asc"]
            ] // Sort by Column 5 in ascending order and then by Column 0 in ascending order
        });
    });
</script>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        // ...

        // Submit form and get new records
        $('#create_notice').on('submit', function(event) {
            event.preventDefault();
            if ($('#subject').val() != '' && $('#comment').val() != '') {
                var form_data = $(this).serialize();
                $.ajax({
                    url: "insert.php",
                    method: "POST",
                    data: form_data,
                    success: function(data) {
                        $('#create_notice')[0].reset();
                        load_unseen_notification();
                    }
                });
            } else {
                alert("Both Fields are Required");
            }
        });

        // Load new notifications
        $(document).on('click', '.dropdown-toggle', function() {
            $('.count').html(data.unseen_notification);
            load_unseen_notification('yes');
        });

        setInterval(function() {
            load_unseen_notification();
        }, 5000);
    });
</script>
<!-- confirm delete record will be here -->
<script type='text/javascript'>
    // confirm record deletion
    function delete_employee(user_id) {
        if (confirm('Are you sure?')) {
            // if the user clicked ok,
            // pass the id to delete.php and execute the delete query
            window.location = 'employee_delete.php?user_id=' + user_id;
        }
    }
</script>

</html>