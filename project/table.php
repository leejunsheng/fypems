<!DOCTYPE html>
<html lang="en">

<head>
    <title> Bootstrap SORT table Example </title>
    <link href="style.css" rel="stylesheet">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@2.11.6/dist/umd/popper.min.js" integrity="sha384-dp4JLmBJ0p8aWV49Vf4+JlUCiMlJ9CI2adMzCpm8B2RHv1jMOrlBjIz5iJahN3z" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
</head>

<body>
    <div class="container" style="width:40%" ;>
        <h2> Bootstrap Sort Table </h2>
        <table class='table table-hover table-responsive table-bordered' id='sortTable'> 
            <thead>
                <tr>
                    <th>Firstname</th>
                    <th>Lastname</th>
                    <th>Email</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Adam</td>
                    <td>joo</td>
                    <td>Jadamj@yahoo.com</td>
                </tr>
                <tr>
                    <td>seri</td>
                    <td>sami</td>
                    <td>ami_seri@rediff.com</td>
                </tr>
                <tr>
                    <td>zeniya</td>
                    <td>deo</td>
                    <td>zee@gmail.com</td>
                </tr>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
    <script>
        $('#sortTable').DataTable();
    </script>
</body>

</html>