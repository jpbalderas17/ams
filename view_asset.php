<?php
	require_once 'support/config.php';
	if(!isLoggedIn()){
		toLogin();
		die();
	}
    if(empty($_GET['asset_tag']) && empty($_GET['id'])){
        redirect("index.php");
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
    elseif(!empty($_GET['asset_tag'])){
        $asset=$con->myQuery("SELECT EOL,id,manufacturer,depreciation_term,depreciation_name,model,asset_status_label,asset_status,category,asset_tag,serial_number,asset_name,purchase_date,purchase_cost,order_number,notes,image FROM qry_assets WHERE asset_tag=?",array($_GET['asset_tag']))->fetch(PDO::FETCH_ASSOC);
        
        if(empty($asset)){
            //Alert("Invalid asset selected.");
            Modal("No Asset found.");
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
                            <?php
                                if(!empty($asset['purchase_date']) && $asset['purchase_date']!="0000-00-00"):
                            ?>
                            <div class='row'>
                                <div class='col-xs-12'>
                                    <strong>Purchase Date: </strong>
                                    <?php echo htmlspecialchars($asset['purchase_date'])?>
                                </div>
                            </div>
                            <?php
                                endif;
                            ?>
                            <div class='row'>
                                <div class='col-xs-12'>
                                    <strong>Depreciation: </strong>
                                    <?php echo htmlspecialchars("(".$asset['depreciation_name'].") ".$asset['depreciation_term']." Months")?>
                                </div>
                            </div>
                            <?php
                                if(!empty($asset['purchase_date']) && $asset['purchase_date']!="0000-00-00"):
                            ?>
                            <div class='row'>
                                <div class='col-xs-12'>
                                    <strong>Fully Depreciated: </strong>
                                    
                                    <?php
                                        $dt1=date_create(date('Y-m-d'));
                                        $dt2=date_create(getDepriciationDate($asset['purchase_date'],$asset['depreciation_term']));
                                        
                                        $interval=date_diff($dt1,$dt2);

                                        echo ($interval->y>0)?$interval->y." year/s ":"";
                                        echo ($interval->m>0)?$interval->m." month/s ":"";
                                        echo ($interval->d>0)?$interval->d." days/s ":"";
                                        // var_dump($interval);
                                    ?>
                                    <?php echo htmlspecialchars("(".getDepriciationDate(Date("Y-m-d"),$asset['depreciation_term']).")")?>
                                </div>
                            </div>
                            <?php
                                endif;
                            ?>
                            <div class='row'>
                                <div class='col-xs-12'>
                                    <strong>EOL: </strong>

                                    <?php echo htmlspecialchars($asset['EOL'])?>
                                </div>
                            </div>
                            <div class='row'>
                                <div class='col-md-12'>
                                    <h4>File Uploads</h4>
                                        <?php
                                            if($_SESSION[WEBAPP]['user']['user_type_id']==1 || $_SESSION[WEBAPP]['user']['user_type_id']==2):
                                        ?>
                                            <h5>Upload File</h5>
                                            <form class='form form-inline' enctype="multipart/form-data" action='file_upload.php' method='post'>
                                                <input type='hidden' name='asset_id' value='<?php echo $asset['id']; ?>'>
                                                <div class='form-group'>
                                                    <input type='text' class='form-control' placeholder='Notes' name='notes'>
                                                </div>
                                                <div class='form-group'>
                                                    <input type='file' class='form-control' name='file' required>
                                                </div>
                                                <button type='submit' class='btn btn-sm btn-success'>Upload</button>
                                            </form>
                                        <?php
                                            endif;
                                        ?>
                                    <?php
                                        $activities=$con->myQuery("SELECT files.id,image,notes,user_id,date_added,file_name,CONCAT(last_name,', ',first_name,' ',middle_name) as user FROM files  JOIN users ON files.user_id=users.id WHERE category_types=1 AND item_id=?",array($asset['id']))->fetchAll(PDO::FETCH_ASSOC);
                                        if(!empty($activities)):

                                    ?>
                                    <table class='table table-bordered table-condensed '>
                                        <thead>
                                            <tr>    
                                                <td>Date</td>
                                                <td>File</td>
                                                <td>Notes</td>
                                                <td>User</td>
                                                <td>Action</td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                    foreach ($activities as $activity):
                                            ?>
                                                    <tr>
                                                        <td><?php echo htmlspecialchars($activity['date_added']);?></td>
                                                        <td><a href='uploads/<?php echo $activity['file_name'] ?>' download='<?php echo htmlspecialchars($activity['image']);?>'><?php echo htmlspecialchars($activity['image']);?></a></td>
                                                        <td><?php echo htmlspecialchars($activity['notes']);?></td>
                                                        <td><?php echo htmlspecialchars($activity['user']);?></td>
                                                        <td><?php echo htmlspecialchars($activity['id']);?></td>
                                                    </tr>
                                            <?php
                                                    endforeach;
                                            ?>
                                            
                                        </tbody>
                                    </table>
                                    <?php
                                        else:

                                            createAlert("No Results.");
                                        endif;
                                    ?>
                                </div>
                            </div>
                            <div class='row'>
                                <div class='col-md-12'>
                                    <h4>Activity History</h4>
                                    <?php
                                        $activities=$con->myQuery("SELECT action_date,(SELECT CONCAT(last_name,', ',first_name,' ',middle_name)  FROM users WHERe id=admin_id)as admin,(SELECT CONCAT(last_name,', ',first_name,' ',middle_name)  FROM users WHERe id=user_id)as user,action,notes FROM activities WHERE category_type_id=1 AND item_id=? ORDER BY activities.action_date DESC LIMIT 20",array($asset['id']))->fetchAll(PDO::FETCH_ASSOC);
                                        if(!empty($activities)):

                                    ?>
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
                                            ?>
                                            
                                        </tbody>
                                    </table>
                                    <?php
                                        else:

                                            createAlert("No Results.");
                                        endif;
                                    ?>
                                </div>
                            </div>

                            <div class='row'>
                                <div class='col-md-12'>
                                    <h4>Asset Maintenance</h4>
                                    <?php
                                        if($_SESSION[WEBAPP]['user']['user_type']==1)
                                    ?>
                                    <?php
                                        $activities=$con->myQuery("SELECT asset_maintenances.id,assets.asset_name,asset_maintenances.asset_id,asset_maintenance_types.name as maintenance_type,asset_maintenances.title,asset_maintenances.start_date,asset_maintenances.completion_date,asset_maintenances.cost FROM `asset_maintenances` JOIN asset_maintenance_types ON asset_maintenances.asset_maintenance_type_id=asset_maintenance_types.id JOIN assets ON assets.id=asset_maintenances.asset_id WHERE asset_maintenances.is_deleted=0 AND asset_maintenances.asset_id=?",array($asset['id']))->fetchAll(PDO::FETCH_ASSOC);
                                        if(!empty($activities)):

                                    ?>
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
                                            ?>
                                            
                                        </tbody>
                                    </table>
                                    <?php
                                        else:

                                            createAlert("No Results.");
                                        endif;
                                    ?>
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