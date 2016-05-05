<?php
	require_once("support/config.php");
	if(!isLoggedIn()){
		toLogin();
		die();
	}

	makeHead();
?>

<?php
	require_once("template/header.php");
	require_once("template/sidebar.php");
?>
 	<div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1 class='page-header text-center text-green'>
            Dashboard
          </h1>
        </section>
        <div class='col-md-12'>
            <?php
                Alert();
            ?>
        </div>
        <!-- Main content -->
        <section class="content">
            <?php
                if(AllowUser(array(1,2))):
            ?>
            <div class="row">
                <div class="col-lg-3 col-md-6">
                        <div class="panel panel-success">
                        <div class="panel-heading bg-green">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-barcode fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="" style='font-size:40px'><?php echo $con->myQuery("SELECT COUNT(id) FROM assets WHERE is_deleted=0")->fetchColumn();?></div>
                                    <div>Total Assets</div>
                                </div>
                            </div>
                        </div>
                        <a href="assets.php">
                            <div class="panel-footer">
                                <span class="pull-left">View Details</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="panel panel-success">
                        <div class="panel-heading bg-green">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-barcode fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="" style='font-size:40px'><?php echo $con->myQuery("SELECT COUNT(id) FROM qry_assets WHERE is_deleted=0 AND asset_status_label='Deployable' AND qry_assets.user_id=0")->fetchColumn();?></div>
                                    <div>Assets Available</div>
                                </div>
                            </div>
                        </div>
                        <a href="assets.php?status=Deployable">
                            <div class="panel-footer">
                                <span class="pull-left">View Details</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="panel panel-success">
                        <div class="panel-heading bg-green">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-tint fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="" style='font-size:40px'><?php echo $con->myQuery("SELECT COUNT(id) FROM consumables WHERE is_deleted=0")->fetchColumn() ?></div>
                                    <div>Consumables</div>
                                </div>
                            </div>
                        </div>
                        <a href="consumables.php">
                            <div class="panel-footer">
                                <span class="pull-left">View Details</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="panel panel-success">
                        <div class="panel-heading bg-green">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-globe fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="" style='font-size:40px'><?php echo $con->myQuery("SELECT COUNT(id) FROM locations WHERE is_deleted=0")->fetchColumn()?></div>
                                    <div>Locations</div>
                                </div>
                            </div>
                        </div>
                        <a href="locations.php">
                            <div class="panel-footer">
                                <span class="pull-left">View Details</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            <div class='row table-responsive'>
                                <div class='col-md-12'>
                                  <div class='box-header'><h4><strong>Activity History </strong><small><a href='report_asset_activity.php'>View All</a></small></h4></div>
                                    <?php
                                        $asset_sql="SELECT DATE_FORMAT(action_date,'%m/%d/%Y') as action_date,activities.item_id,assets.asset_tag,assets.asset_name,(SELECT CONCAT(last_name,', ',first_name,' ',middle_name)  FROM users WHERe id=admin_id)as admin,(SELECT CONCAT(last_name,', ',first_name,' ',middle_name)  FROM users WHERe id=activities.user_id)as user,action,activities.notes FROM activities JOIN assets ON assets.id=activities.item_id WHERE category_type_id=1  ORDER BY activities.action_date DESC LIMIT 10";
                                            $activities=$con->myQuery($asset_sql)->fetchAll(PDO::FETCH_ASSOC);
                                        // $activities=$con->myQuery("SELECT action_date,(SELECT CONCAT(last_name,', ',first_name,' ',middle_name)  FROM users WHERe id=admin_id)as admin,(SELECT CONCAT(last_name,', ',first_name,' ',middle_name)  FROM users WHERe id=user_id)as user,action,notes FROM activities WHERE category_type_id=1  ORDER BY activities.action_date DESC LIMIT 5")->fetchAll(PDO::FETCH_ASSOC);
                                        if(!empty($activities)):

                                    ?>
                                    <table class='table table-bordered table-condensed '>
                                        <thead>
                                            <tr>    
                                                <th class='text-center date-td'>Date</th>
                                                <th class='text-center'>Asset Tag</th>
                                                <th class='text-center'>Asset Name</th>
                                                <th class='text-center'>Admin</th>
                                                <th class='text-center'>Actions</th>
                                                <th class='text-center'>User</th>
                                                <th class='text-center'>Notes</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                    foreach ($activities as $activity):
                                            ?>
                                                    <tr>
                                                <td class='text-center'><?php echo htmlspecialchars($activity['action_date'])?></td>
                                                <td class='text-center'><a href='view_asset.php?id=<?= $activity['item_id']?>'><?php echo htmlspecialchars($activity['asset_tag'])?></a></td>
                                                <td class='text-center'><?php echo htmlspecialchars($activity['asset_name'])?></td>
                                                <td class='text-center'><?php echo htmlspecialchars($activity['admin'])?></td>
                                                <td class='text-center'><?php echo htmlspecialchars($activity['action'])?></td>
                                                <td class='text-center'><?php echo htmlspecialchars($activity['user'])?></td>
                                                <td class='text-center'><?php echo htmlspecialchars($activity['notes'])?></td>
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
            <?php
                else:
                $asset=$con->myQuery("SELECT CONCAT(first_name,' ',middle_name,' ',last_name) as name,username,email,contact_no,employee_no,location,title,department,id FROM qry_users WHERE id=?",array($_SESSION[WEBAPP]['user']['id']))->fetch(PDO::FETCH_ASSOC);


            ?>
            <div class='row'>
                        <div class='col-md-12'>          
                         
                         <div class='row'>
                                <div class='col-md-12'>
                                <a href='change_secret_password.php' class='btn btn-success btn-flat'>Change Secret Password</a>
                                </div>
                            </div>
                              <div class='row'>
                                <div class='col-xs-12'>
                                    <strong>Employee Number: </strong>
                                    <em><?php echo htmlspecialchars($asset['employee_no'])?></em>
                                </div>
                            </div>
                            <div class='row'>
                                <div class='col-xs-12'>
                                    <strong>Email Address: </strong>
                                    <em><?php echo htmlspecialchars($asset['email'])?></em>
                                </div>
                            </div>
                            <div class='row'>
                                <div class='col-xs-12'>
                                    <strong>Contact Number: </strong>
                                    <em><?php echo htmlspecialchars($asset['contact_no'])?></em>
                                </div>
                            </div>
                            <div class='row'>
                                <div class='col-xs-12'>
                                    <strong>Department: </strong>
                                    <em><?php echo htmlspecialchars($asset['department'])?></em>
                                </div>
                            </div>
                            <div class='row'>
                                <div class='col-xs-12'>
                                    <strong>Location: </strong>
                                    <em><?php echo htmlspecialchars($asset['location'])?></em>
                                </div>
                            </div>
                            <div class='row'>
                                <div class='col-md-12'>
                                    </br></br>
                                    <!--FOR CONSUMABLES-->
                                    <h4>Consumables</h4>
                                    <table class='table table-bordered table-condensed '>
                                        <thead>
                                            <tr>    
                                                <td>Name</td>
                                                <td>Date</td>
                                                <td>Actions</td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $consumables=$con->myQuery("SELECT NAME,action_date,ACTION FROM vw_user WHERE category_type_id=2 AND user_id=?",array($_SESSION[WEBAPP]['user']['id']))->fetchAll(PDO::FETCH_ASSOC);
                                               if(!empty($consumables)):

                                                    foreach ($consumables as $consumable):
                                            ?>
                                                    <tr>
                                                        <td><?php echo $consumable['NAME']?></td>
                                                        <td><?php echo $consumable['action_date']?></td>
                                                        <td><?php echo $consumable['ACTION']?></td>
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


                                    </br>
                                    <!--FOR ASSETS-->
                                    <h4>Assets</h4>
                                    <table class='table table-bordered table-condensed '>
                                        <thead>
                                            <tr>    
                                                <td>Name</td>
                                                <td>Date</td>
                                                <td>Actions</td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $consumables=$con->myQuery("SELECT NAME,action_date,ACTION FROM vw_asset WHERE category_type_id=1 AND user_id=?",array($_SESSION[WEBAPP]['user']['id']))->fetchAll(PDO::FETCH_ASSOC);
                                               if(!empty($consumables)):

                                                    foreach ($consumables as $consumable):
                                            ?>
                                                    <tr>
                                                        <td><?php echo $consumable['NAME']?></td>
                                                        <td><?php echo $consumable['action_date']?></td>
                                                        <td><?php echo $consumable['ACTION']?></td>
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
                        
                    </div>
            <?php
                endif;
            ?>
        </section><!-- /.content -->
  </div>

<?php
    Modal();
	makeFoot();
?>