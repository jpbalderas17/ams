<?php
	require_once 'support/config.php';
	if(!isLoggedIn()){
		toLogin();
		die();
	}
    if(!empty($_GET['id'])){
        $asset_model=$con->myQuery("SELECT id,name,manufacturer_id,category_id,image,model_no,EOL,depreciation_id FROM asset_models WHERE id=?",array($_GET['id']))->fetch(PDO::FETCH_ASSOC);
        if(empty($asset_model)){
            //Alert("Invalid asset selected.");
            Modal("Invalid Asset Model Selected");
            redirect("asset_models.php");
            die();
        }
    }

    $manufacturers=$con->myQuery("SELECT id,name FROM manufacturers")->fetchAll(PDO::FETCH_ASSOC);
    $categories=$con->myQuery("SELECT id,name FROM categories WHERE is_deleted=0 AND category_type_id=1")->fetchAll(PDO::FETCH_ASSOC);
    $depreciations=$con->myQuery("SELECT id,name FROM depreciations")->fetchAll(PDO::FETCH_ASSOC);
                    						
	makeHead("Asset Models");
?>
<div id='wrapper'>
<?php
	 require_once 'template/navbar.php';
?>
</div>
<div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Asset Models Form</h1>
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
                    		<form class='form-horizontal' method='POST' action='save_asset_model.php'>
                                <input type='hidden' name='id' value='<?php echo !empty($asset_model)?$asset_model['id']:""?>'>

                                <div class='form-group'>
                                    <label class='col-sm-12 col-md-3 control-label'> Name</label>
                                    <div class='col-sm-12 col-md-9'>
                                        <input type='text' class='form-control' name='model_name' placeholder='Enter Model Name' value='<?php echo !empty($asset_model)?$asset_model['name']:"" ?>'>
                                    </div>
                                </div>
                                <div class='form-group'>
                                    <label class='col-sm-12 col-md-3 control-label'> Model Number</label>
                                    <div class='col-sm-12 col-md-9'>
                                        <input type='text' class='form-control' name='model_no' placeholder='Enter Model Number' value='<?php echo !empty($asset_model)?$asset_model['model_no']:"" ?>'>
                                    </div>
                                </div>
                                <div class='form-group'>
                    				<label class='col-sm-12 col-md-3 control-label'> Manufacturer</label>
                    				<div class='col-sm-12 col-md-9'>
                    					<select class='form-control' name='manufacturer_id' data-placeholder="Select a Manufacturer" <?php echo!(empty($asset_model))?"data-selected='".$asset_model['manufacturer_id']."'":NULL ?>>
                    						<?php
                    							echo makeOptions($manufacturers);
                    						?>
                    					</select>
                    				</div>
                    			</div>
                                <div class='form-group'>
                                    <label class='col-sm-12 col-md-3 control-label'> Category</label>
                                    <div class='col-sm-12 col-md-9'>
                                        <select class='form-control' name='category_id' data-placeholder="Select a Category" <?php echo!(empty($asset_model))?"data-selected='".$asset_model['category_id']."'":NULL ?>>
                                            <?php
                                                echo makeOptions($categories);
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class='form-group'>
                                    <label class='col-sm-12 col-md-3 control-label'> End of Life (EOL)</label>
                                    <div class='col-sm-12 col-md-9'>
                                        <?php
                                        $EOL="";
                                         if(!empty($asset_model)){
                                            $EOL=$asset_model['EOL'];
                                            if($EOL=="0000-00-00"){
                                                $EOL="";
                                            }
                                         }
                                        ?>
                                        <input type='date' class='form-control' name='EOL'  value='<?php echo $EOL; ?>'>
                                    </div>
                                </div>
                                <div class='form-group'>
                                    <label class='col-sm-12 col-md-3 control-label'> Depreciation</label>
                                    <div class='col-sm-12 col-md-9'>
                                        <select class='form-control' name='depreciation_id' data-placeholder="Select a Depreciation" <?php echo!(empty($asset_model))?"data-selected='".$asset_model['depreciation_id']."'":NULL ?>>
                                            <?php
                                                echo makeOptions($depreciations);
                                            ?>
                                        </select>
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