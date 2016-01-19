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
                    		<form class='form-horizontal'>
                    			<div class='form-group'>
                    				<label class='col-sm-12 col-md-2 control-label'> Model</label>
                    				<div class='col-sm-12 col-md-10'>
                    					<select class='form-control' name='model_id'>
                    						<?php
                    							echo makeOptions($asset_models,"Select a Model");
                    						?>
                    					</select>
                    				</div>
                    			</div>
                    			<div class='form-group'>
                    				<label class='col-sm-12 col-md-2 control-label'> Status</label>
                    				<div class='col-sm-12 col-md-10'>
                    					<select class='form-control' name='model_id'>
                    						<?php
                    							echo makeOptions($asset_status_labels,"Select Status");
                    						?>
                    					</select>
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