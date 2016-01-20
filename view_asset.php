<?php
	require_once 'support/config.php';
	if(!isLoggedIn()){
		toLogin();
		die();
	}
    if(!empty($_GET['id'])){
        $asset=$con->myQuery("SELECT EOL,id,manufacturer,depreciation_term,depreciation_name,model,asset_status_label,asset_status,category,asset_tag,serial_number,asset_name,purchase_date,purchase_cost,order_number,notes,image FROM qry_assets WHERE id=?",array($_GET['id']))->fetch(PDO::FETCH_ASSOC);
        if(empty($asset)){
            //Alert("Invalid asset selected.");
            Modal("Invalid Asset Selected");
            redirect("assets.php");
            die();
        }
    }

    $asset_models=$con->myQuery("SELECT id,name FROM asset_models WHERE is_deleted=0")->fetchAll(PDO::FETCH_ASSOC);
    $asset_status_labels=$con->myQuery("SELECT id,name FROM asset_status_labels WHERE is_deleted=0")->fetchAll(PDO::FETCH_ASSOC);
    $locations=$con->myQuery("SELECT id,name FROM locations WHERE is_deleted=0")->fetchAll(PDO::FETCH_ASSOC);
                    						
	makeHead("Assets");
?>
<div id='wrapper'>
<?php
	 require_once 'template/navbar.php';
?>
</div>
<div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h3 class="page-header">View Asset <?php echo htmlspecialchars($asset['asset_name'])?> (<?php echo htmlspecialchars($asset['category'])?>)</h3>
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
                    	<div class='col-md-9'>
          
                            <div class='row'>
                                <div class='col-xs-12'>
                                    <strong>Serial: </strong>
                                    <em><?php echo htmlspecialchars($asset['serial_number'])?></em>
                                </div>
                            </div>
                            <div class='row'>
                                <div class='col-xs-12'>
                                    <strong>Manufacturer: </strong>
                                    <em><?php echo htmlspecialchars($asset['manufacturer'])?></em>
                                </div>
                            </div>
                            <div class='row'>
                                <div class='col-xs-12'>
                                    <strong>Model: </strong>
                                    <em><?php echo htmlspecialchars($asset['model'])?></em>
                                </div>
                            </div>
                            <div class='row'>
                                <div class='col-xs-12'>
                                    <strong>Purchase Cost: </strong>
                                    <?php echo htmlspecialchars(number_format($asset['purchase_cost'],2))?>
                                </div>
                            </div>
                            <div class='row'>
                                <div class='col-xs-12'>
                                    <strong>Depriciation: </strong>
                                    <?php echo htmlspecialchars("(".$asset['depreciation_name'].") ".$asset['depreciation_term']." Months")?>
                                </div>
                            </div>
                            <div class='row'>
                                <div class='col-xs-12'>
                                    <strong>EOL: </strong>

                                    <?php echo htmlspecialchars($asset['EOL'])?>
                                </div>
                            </div>
                            <div class='row'>
                                <div class='col-md-12'>
                                    <h4>History</h4>
                                    <table class='table table-bordered table-condensed '>
                                        <thead>
                                            <tr>    
                                                <td>Date</td>
                                                <td>Admin</td>
                                                <td>Actions</td>
                                                <td>User</td>
                                                <td>Notes</td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                $activites=$con->myQuery("SELECT action_date,(SELECT CONCAT(last_name,', ',first_name,' ',middle_name)  FROM users WHERe id=admin_id)as admin,(SELECT CONCAT(last_name,', ',first_name,' ',middle_name)  FROM users WHERe id=user_id)as user,action,notes FROM activities WHERE category_type_id=1 AND item_id=?",array($_GET['id']))->fetchAll(PDO::FETCH_ASSOC);
                                                if(!empty($activites)):
                                                    foreach ($activities as $activity):
                                            ?>
                                                    <tr>
                                                        <td><?php echo $activity['action_date']?></td>
                                                        <td><?php echo $activity['admin']?></td>
                                                        <td><?php echo $activity['action']?></td>
                                                        <td><?php echo $activity['user']?></td>
                                                        <td><?php echo $activity['notes']?></td>
                                                    </tr>
                                            <?php
                                                    endforeach;
                                                else:
                                            ?>
                                                <tr>
                                                    <td colspan='5'>No Results.</td>
                                                </tr>
                                            <?php
                                                endif;
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class='col-md-3'></div>
                    </div>

                </div>
            </div>
            <!-- /.row -->
</div>
<?php
Modal();
?>
<?php
	makeFoot();
?>