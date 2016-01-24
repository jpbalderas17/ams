<?php
	require_once 'support/config.php';
	if(!isLoggedIn()){
		toLogin();
		die();
	}
    if(!empty($_GET['id'])){
        $asset=$con->myQuery("SELECT id,name,purchase_date,order_number,purchase_cost,user_id  FROM consumables WHERE id=?",array($_GET['id']))->fetch(PDO::FETCH_ASSOC);
        if(empty($asset)){
            //Alert("Invalid asset selected.");
            Modal("Invalid Consumables Selected");
            redirect("consumables.php");
            die();
        }
    }
    
    //$asset_models=$con->myQuery("SELECT id,name FROM asset_models WHERE is_deleted=0")->fetchAll(PDO::FETCH_ASSOC);
    //$locations=$con->myQuery("SELECT id,name FROM locations WHERE is_deleted=0")->fetchAll(PDO::FETCH_ASSOC);
                    						
	makeHead("Consumables");
?>
<div id='wrapper'>
<?php
	 require_once 'template/navbar.php';
?>
</div>
<div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h3 class="page-header"><?php echo htmlspecialchars($asset['name'])?> Consumable</h3>
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
                        <a href='user.php' class='btn btn-success pull-right'> <span class='fa fa-arrow-left'></span>Back</a>         
                            <div class='row'>
                                <div class='col-xs-12'>
                                    <strong>Order Number: </strong>
                                    <em><?php echo htmlspecialchars($asset['order_number'])?></em>
                                </div>
                            </div>
                            <div class='row'>
                                <div class='col-xs-12'>
                                    <strong>Purchase Cost: </strong>
                                    <?php echo htmlspecialchars(number_format($asset['purchase_cost'],2))?>
                                </div>
                            </div>
                            <div class='row'>
                                <div class='col-md-12'>
                                </br>
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
                                            $activities=$con->myQuery("SELECT action_date,(SELECT CONCAT(first_name,' ',middle_name,' ',last_name)  FROM users WHERe id=admin_id)as admin,action,(SELECT CONCAT(first_name,' ',middle_name,' ',last_name)  FROM users WHERe id=user_id)as user,notes FROM activities WHERE category_type_id=2 AND item_id=?",array($asset['id']))->fetchAll(PDO::FETCH_ASSOC);
                                                if(!empty($activities)):
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