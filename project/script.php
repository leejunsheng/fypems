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