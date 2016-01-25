<?php
    require_once 'support/config.php';
    if(!isLoggedIn()){
        toLogin();
        die();
    }
    makeHead("Consumable Activity Reports");
?>
<div id='wrapper'>
<?php
     require_once 'template/navbar.php';
?>
</div>

<div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Consumables Activity Report</h1>
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
                                
                        </div>
                    </div>
                    <br/>    

                    <div class='panel panel-default'>
                        
                        <div class='panel-body ' >
                            <div class='dataTable_wrapper '>
                                <table class='table responsive table-bordered table-condensed table-hover ' id='dataTables'>
                                    <thead>
                                        <tr>
                                            
                                            <th>Action Date</th>
                                            <th>Admin</th>
                                            <th>Name</th>
                                            <th>Order Number</th>
                                            <th>Action</th>
                                            <th>User</th>
                                            <th>Notes</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                            <tr>

                                             <?php
                                            $assets=$con->myQuery("SELECT action_date,(SELECT CONCAT(first_name,' ',middle_name,' ',last_name)  FROM users WHERE id=admin_id) AS admin,NAME, order_number,ACTION,(SELECT CONCAT(first_name,' ',middle_name,' ',last_name) FROM users WHERE id=c.user_id)AS USER,notes FROM consumables AS c LEFT JOIN activities AS a ON a.item_id=c.id WHERE category_type_id=2 and is_deleted=0")->fetchAll(PDO::FETCH_ASSOC);
                                            foreach ($assets as $asset):
                                            ?>
                                                <?php
                                                    foreach ($asset as $key => $value):
                                                ?>
                                                 
                                                    <td>
                                                        <?php
                                                            echo htmlspecialchars($value);
                                                        ?>
                                                    </td>
                                                <?php
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
                "scrollX": true,
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend:"csv",
                        text:"<span class='fa fa-download'></span> Download CSV "
                    }
                    ]
        });
    });

    </script>
<?php
    Modal();
    makeFoot();
?>