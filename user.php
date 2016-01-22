<?php
    require_once 'support/config.php';
    if(!isLoggedIn()){
        toLogin();
        die();
    }
    makeHead("View Users");
?>
<div id='wrapper'>
<?php
     require_once 'template/navbar.php';
?>
</div>
#This is a the quick brwon fox jumps over the lazy dog 
<div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Current Users</h1>
                </div>

                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class='col-lg-12'>
                    <?php
                        Alert();
                    ?>
                    <div class='row'>
                        <div class='col-sm-12'>
                                <a href='frm_users.php' class='btn btn-success pull-right'> <span class='fa fa-plus'></span> Create New</a>
                        </div>
                    </div>
                    <br/>    

                    <div class='panel panel-default'>
                        
                        <div class='panel-body ' >
                            <div class='dataTable_wrapper '>
                                <table class='table responsive table-bordered table-condensed table-hover ' id='dataTables'>
                                    <thead>
                                        <tr>
                                            <th>First Name</th>
                                            <th>Middle Name</th>
                                            <th>Last Name</th>
                                            <th>Username</th>
                                            <th>Email</th>
                                            <th>Contact Number</th>
                                            <th>Employee Number</th>
                                            <th>Location</th>
                                            <th>Title</th>
                                            <th>Department</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $users=$con->myQuery("SELECT first_name,middle_name,last_name,username,email,contact_no,employee_no,location,title,department,id FROM qry_consumables")->fetchAll(PDO::FETCH_ASSOC);

                                            foreach ($users as $user):
                                        ?>
                                            <tr>
                                                <?php
                                                    foreach ($user as $key => $value):
                                                    if($key=='id'):
                                                ?>
                                                    <td>
                                                        <button class='btn btn-sm btn-warning'><span class='fa fa-pencil'></span></button>
                                                        <button class='btn btn-sm btn-danger'><span class='fa fa-trash'></span></button>
                                                    </td>
                                                <?php
                                                    else:
                                                ?>
                                                    <td>
                                                        <?php
                                                            echo htmlspecialchars($value);
                                                        ?>
                                                    </td>
                                                <?php
                                                    endif;
                                                    endforeach;
                                                ?>
                                            </tr>
                                        <?php
                                            endforeach;
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.row -->
</div>
<script>
    $(document).ready(function() {
        $('#dataTables').DataTable({
                 "scrollY": true,
                "scrollX": true
        });
    });
    </script>
<?php
    makeFoot();
?>