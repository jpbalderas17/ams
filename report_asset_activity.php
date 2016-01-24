<?php
	require_once 'support/config.php';
	if(!isLoggedIn()){
		toLogin();
		die();
	}
	makeHead("Asset Activity Reports");
?>
<div id='wrapper'>
<?php
	 require_once 'template/navbar.php';
?>
</div>

<div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Asset Activity Report</h1>
                </div>

                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class='col-lg-12'>
                    <?php
                        Alert();
                    ?>

                    <div class='panel panel-default'>
                        
                        <div class='panel-body ' >
                            <div class='dataTable_wrapper '>
                                <table class='table responsive table-bordered table-condensed table-hover ' id='dataTables'>
                                    <thead>
                                        <tr>
                                            <tr>    
                                                <th class='date-td'>Date</th>
                                                <th>Asset Tag</th>
                                                <th>Asset Name</th>
                                                <th>Admin</th>
                                                <th>Actions</th>
                                                <th>User</th>
                                                <th>Notes</th>
                                            </tr>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $asset_sql="SELECT action_date,activities.item_id,assets.asset_tag,assets.asset_name,(SELECT CONCAT(last_name,', ',first_name,' ',middle_name)  FROM users WHERe id=admin_id)as admin,(SELECT CONCAT(last_name,', ',first_name,' ',middle_name)  FROM users WHERe id=activities.user_id)as user,action,activities.notes FROM activities JOIN assets ON assets.id=activities.item_id WHERE category_type_id=1  ORDER BY activities.action_date";
                                            $assets=$con->myQuery($asset_sql)->fetchAll(PDO::FETCH_ASSOC);

                                            foreach ($assets as $asset):
                                        ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($asset['action_date'])?></td>
                                                <td><a href='view_asset.php?id=<?= $asset['item_id']?>'><?php echo htmlspecialchars($asset['asset_tag'])?></a></td>
                                                <td><?php echo htmlspecialchars($asset['asset_name'])?></td>
                                                <td><?php echo htmlspecialchars($asset['admin'])?></td>
                                                <td><?php echo htmlspecialchars($asset['action'])?></td>
                                                <td><?php echo htmlspecialchars($asset['user'])?></td>
                                                <td><?php echo htmlspecialchars($asset['notes'])?></td>
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
            <div id='123'></div>
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