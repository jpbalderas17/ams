<?php
	require_once 'support/config.php';
	if(!isLoggedIn()){
		toLogin();
		die();
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
                    			<div class='form-group'>
                    				<label class='col-sm-12 col-md-3 control-label'> Model</label>
                    				<div class='col-sm-12 col-md-9'>
                    					<select class='form-control' name='model_id'>
                    						<?php
                    							echo makeOptions($asset_models,"Select a Model");
                    						?>
                    					</select>
                    				</div>
                    			</div>
                    			<div class='form-group'>
                    				<label class='col-sm-12 col-md-3 control-label'> Status</label>
                    				<div class='col-sm-12 col-md-9'>
                    					<select class='form-control' name='asset_status_id'>
                    						<?php
                    							echo makeOptions($asset_status_labels,"Select Status");
                    						?>
                    					</select>
                    				</div>
                    			</div>
                                <div class='form-group'>
                                    <label class='col-sm-12 col-md-3 control-label'> Serial Number</label>
                                    <div class='col-sm-12 col-md-9'>
                                        <input type='text' class='form-control' name='serial_number' placeholder='Enter Serial Number'>
                                    </div>
                                </div>
                                <div class='form-group'>
                                    <label class='col-sm-12 col-md-3 control-label'> Asset Name</label>
                                    <div class='col-sm-12 col-md-9'>
                                        <input type='text' class='form-control' placeholder='Enter Asset Name' name='asset_name'>
                                    </div>
                                </div>
                                <div class='form-group'>
                                    <label class='col-sm-12 col-md-3 control-label'> Purchase Date</label>
                                    <div class='col-sm-12 col-md-9'>
                                        <input type='date' class='form-control' name='purchase_date'>
                                    </div>
                                </div>
                                <div class='form-group'>
                                    <label class='col-sm-12 col-md-3 control-label'> Order Number</label>
                                    <div class='col-sm-12 col-md-9'>
                                        <input type='text' class='form-control' placeholder='Enter Order Number' name='order_number'>
                                    </div>
                                </div>
                                <div class='form-group'>
                                    <label class='col-sm-12 col-md-3 control-label'> Purchase Cost</label>
                                    <div class='col-sm-12 col-md-9'>
                                        <input type='text' class='form-control' placeholder='0.00' name='purchase_cost'>
                                    </div>
                                </div>

                                <div class='form-group'>
                                    <label class='col-sm-12 col-md-3 control-label'> Notes</label>
                                    <div class='col-sm-12 col-md-9'>
                                        <textarea class='form-control' name='notes'></textarea>
                                    </div>
                                </div>
                                <div class='form-group'>
                                    <label class='col-sm-12 col-md-3 control-label'> Default Location</label>
                                    <div class='col-sm-12 col-md-9'>
                                        <select name='location_id' class='form-control'>    
                                        <?php
                                            echo makeOptions($locations,"Select Default Location");
                                        ?>
                                        </select>

                                    </div>
                                </div>
                                <div class='form-group'>
                                    <label class='col-sm-12 col-md-3 control-label'> Image</label>
                                    <div class='col-sm-12 col-md-9'>
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
	makeFoot();
?>