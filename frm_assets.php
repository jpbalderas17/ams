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
        $asset=$con->myQuery("SELECT id,asset_model_id,asset_status_id,asset_tag,serial_number,asset_name,purchase_date,purchase_cost,order_number,notes,location_id,image FROM assets WHERE id=?",array($_GET['id']))->fetch(PDO::FETCH_ASSOC);
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
                    <h1 class="page-header">Asset Form</h1>
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
                    	<div class='col-sm-12 col-md-8 col-md-offset-2'>
                    		<form class='form-horizontal' method='POST' action='create_asset.php' enctype="multipart/form-data">
                                <input type='hidden' name='id' value='<?php echo !empty($asset)?$asset['id']:""?>'>
                    			<?php

                                    if(!empty($asset)):
                                ?>
                                    <div class='form-group'>
                                        <label class='col-sm-12 col-md-3 control-label'> Asset Tag</label>
                                        <div class='col-sm-12 col-md-9'>
                                            <?php echo htmlspecialchars($asset['asset_tag'])?>
                                            <!-- <input type='text' value='<?php echo htmlspecialchars($asset['asset_tag'])?>' class='form-control disabled' readonly> -->
                                        </div>
                                    </div>
                                <?php
                                    endif;
                                    //var_dump(!(empty($asset))?"data-selected='".$asset['asset_model_id']."'":NULL );
                                ?>
                                <div class='form-group'>
                    				<label class='col-sm-12 col-md-3 control-label'> Model</label>
                    				<div class='col-sm-12 col-md-9'>
                    					<select class='form-control' name='model_id' data-placeholder="Select a Model" <?php echo!(empty($asset))?"data-selected='".$asset['asset_model_id']."'":NULL ?>>
                    						<?php
                    							echo makeOptions($asset_models);
                    						?>
                    					</select>
                    				</div>
                    			</div>
                    			<div class='form-group'>
                    				<label class='col-sm-12 col-md-3 control-label'> Status</label>
                    				<div class='col-sm-12 col-md-9'>
                    					<select class='form-control' name='asset_status_id' data-placeholder="Select Asset Status" <?php echo !(empty($asset))?"data-selected='".$asset['asset_status_id']."'":NULL ?>>
                    						<?php
                    							echo makeOptions($asset_status_labels);
                    						?>
                    					</select>
                    				</div>
                    			</div>
                                <div class='form-group'>
                                    <label class='col-sm-12 col-md-3 control-label'> Serial Number</label>
                                    <div class='col-sm-12 col-md-9'>
                                        <input type='text' class='form-control' name='serial_number' placeholder='Enter Serial Number' value='<?php echo !empty($asset)?$asset['serial_number']:"" ?>'>
                                    </div>
                                </div>
                                <div class='form-group'>
                                    <label class='col-sm-12 col-md-3 control-label'> Asset Name</label>
                                    <div class='col-sm-12 col-md-9'>
                                        <input type='text' class='form-control' placeholder='Enter Asset Name' name='asset_name'  value='<?php echo !empty($asset)?$asset['asset_name']:"" ?>'>
                                    </div>
                                </div>
                                <div class='form-group'>
                                    <label class='col-sm-12 col-md-3 control-label'> Order Number</label>
                                    <div class='col-sm-12 col-md-9'>
                                        <input type='text' class='form-control' placeholder='Enter Order Number' name='order_number' value='<?php echo !empty($asset)?$asset['order_number']:"" ?>'>
                                    </div>
                                </div>
                                <div class='form-group'>
                                    <label class='col-sm-12 col-md-3 control-label'> Purchase Date</label>
                                    <div class='col-sm-12 col-md-9'>
                                        <?php
                                        $purchase_date="";
                                         if(!empty($asset)){
                                            $purchase_date=$asset['purchase_date'];
                                            if($purchase_date=="0000-00-00"){
                                                $purchase_date="";
                                            }
                                         }
                                        ?>
                                        <input type='date' class='form-control' name='purchase_date'  value='<?php echo $purchase_date; ?>'>
                                    </div>
                                </div>
                                
                                <div class='form-group'>
                                    <label class='col-sm-12 col-md-3 control-label'> Purchase Cost</label>
                                    <div class='col-sm-12 col-md-9'>
                                        <input type='text' class='form-control' placeholder='0.00' name='purchase_cost' value='<?php echo !empty($asset)?$asset['purchase_cost']:"0" ?>'>
                                    </div>
                                </div>

                                <div class='form-group'>
                                    <label class='col-sm-12 col-md-3 control-label'> Notes</label>
                                    <div class='col-sm-12 col-md-9'>
                                        <textarea class='form-control' name='notes' value='<?php echo !empty($asset)?$asset['notes']:"" ?>'></textarea>
                                    </div>
                                </div>
                                <div class='form-group'>
                                    <label class='col-sm-12 col-md-3 control-label'> Default Location</label>
                                    <div class='col-sm-12 col-md-9'>
                                        <select name='location_id' class='form-control' data-placeholder="Select Default Location" <?php echo !(empty($asset))?"data-selected='".$asset['location_id']."'":NULL ?>>    
                                        <?php
                                            echo makeOptions($locations);
                                        ?>
                                        </select>

                                    </div>
                                </div>
                                <div class='form-group'>
                                    <label class='col-sm-12 col-md-3 control-label'> Image</label>
                                    <div class='col-sm-12 col-md-9'>
                                        <?php
                                            if(!empty($asset['image'])):
                                        ?>
                                        <img src='asset_images/<?php echo $asset['image'];?>' class='img-responsive'>

                                        <?php
                                            endif;
                                        ?>
                                        <input type='file' class='form-control' name='image'>
                                    </div>
                                </div>

                                <div class='form-group'>
                                    <div class='col-sm-12 col-md-9 col-md-offset-3 '>
                                        <a href='assets.php' class='btn btn-default'>Cancel</a>
                                        <button type='submit' class='btn btn-success'> <span class='fa fa-check'></span> Save</button>
                                    </div>
                                    
                                </div>
                    			
                    		</form>
                    	</div>
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