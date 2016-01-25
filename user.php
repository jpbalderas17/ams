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
                                            <th>Name</th>
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
                                            $users=$con->myQuery("SELECT CONCAT(first_name,' ',middle_name,' ',last_name) as name,username,email,contact_no,employee_no,location,title,department,id FROM qry_users")->fetchAll(PDO::FETCH_ASSOC);

                                            foreach ($users as $user):
                                        ?>
                                            <tr>
                                                <?php
                                                    foreach ($user as $key => $value):
                                                    if($key=='name'):
                                                ?>
                                                    <td>
                                                        <a href='view_user.php?id=<?= $user['id']?>'><?php echo htmlspecialchars($value)?></a>
                                                    </td>

                                                <?php
                                                    elseif($key=='id'):
                                                ?>
                                                    <td>
                                                        <a class='btn btn-sm btn-warning' href='frm_users.php?id=<?php echo $value;?>'><span class='fa fa-pencil'></span></a>
                                                        <a class='btn btn-sm btn-danger' href='delete.php?id=<?php echo $value?>&t=u' onclick='return confirm("This consumable will be deleted.")'><span class='fa fa-trash'></span></a>
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