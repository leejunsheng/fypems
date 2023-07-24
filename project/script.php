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
