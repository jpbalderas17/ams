<?php
	require_once 'support/config.php';
	if(!isLoggedIn()){
		toLogin();
		die();
	}
    if(!AllowUser(array(1,2))){
        redirect("index.php");
    }
    if(!empty($_GET['id'])){
        $asset=$con->myQuery("SELECT CONCAT(first_name,' ',middle_name,' ',last_name) as name,username,email,contact_no,employee_no,location,title,department,id FROM qry_users WHERE id=?",array($_GET['id']))->fetch(PDO::FETCH_ASSOC);
        if(empty($asset)){
            //Alert("Invalid asset selected.");
            Modal("Invalid Users Selected");
            redirect("user.php");
            die();
        }
    }
    //$asset_models=$con->myQuery("SELECT id,name FROM asset_models WHERE is_deleted=0")->fetchAll(PDO::FETCH_ASSOC);
    //$locations=$con->myQuery("SELECT id,name FROM locations WHERE is_deleted=0")->fetchAll(PDO::FETCH_ASSOC);
                    						
	makeHead("Users");
?>
<div id='wrapper'>
<?php
	 require_once 'template/navbar.php';
?>
</div>
<div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h3 class="page-header"><?php echo htmlspecialchars($asset['name'])?></h3>
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
                                            $consumables=$con->myQuery("SELECT NAME,action_date,ACTION FROM vw_user WHERE category_type_id=2 AND user_id=?",array($_GET['id']))->fetchAll(PDO::FETCH_ASSOC);
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
                                            $consumables=$con->myQuery("SELECT NAME,action_date,ACTION FROM vw_asset WHERE category_type_id=1 AND user_id=?",array($_GET['id']))->fetchAll(PDO::FETCH_ASSOC);
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