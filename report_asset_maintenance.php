<?php
	require_once 'support/config.php';
	if(!isLoggedIn()){
		toLogin();
		die();
	}
    if(!AllowUser(array(1,2,3))){
        redirect("index.php");
    }
	makeHead("Asset Depreciation Report");
?>
<div id='wrapper'>
<?php
	 require_once 'template/navbar.php';
?>
</div>

<div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Asset Maintenance Report</h1>
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
                                                <td>Asset Tag</td>
                                                <td>Asset Name</td>    
                                                <td>Maintenance Title</td>
                                                <td>Maintenance Type</td>
                                                <td>Maintenance Cost</td>
                                                <td class='date-td'>Start Date</td>
                                                <td class='date-td'>Completion Date</td>
                                                <td>Notes</td>
                                            </tr>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $asset_sql="SELECT asset_maintenances.id,assets.asset_name,assets.asset_tag,asset_maintenances.asset_id,asset_maintenance_types.name as maintenance_type,asset_maintenances.title,asset_maintenances.start_date,asset_maintenances.completion_date,asset_maintenances.cost,asset_maintenances.notes FROM `asset_maintenances` JOIN asset_maintenance_types ON asset_maintenances.asset_maintenance_type_id=asset_maintenance_types.id JOIN assets ON assets.id=asset_maintenances.asset_id WHERE asset_maintenances.is_deleted=0  ORDER BY asset_maintenances.start_date";
                                            $assets=$con->myQuery($asset_sql)->fetchAll(PDO::FETCH_ASSOC);

                                            foreach ($assets as $asset):
                                        ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($asset['asset_tag'])?></td>
                                                <td><?php echo htmlspecialchars($asset['asset_name'])?></td>
                                                <td><?php echo htmlspecialchars($asset['title'])?></td>
                                                <td><?php echo htmlspecialchars($asset['maintenance_type'])?></td>
                                                <td><?php echo htmlspecialchars(number_format($asset['cost'],2))?> </td>
                                                <td><?php echo $asset['start_date']?></td>
                                                <td><?php echo $asset['completion_date']=="0000-00-00"?"":$asset['completion_date'];?></td>
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