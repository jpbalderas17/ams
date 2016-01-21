<?php
	require_once 'support/config.php';
	if(!isLoggedIn()){
		toLogin();
		die();
	}
    
    $consumabless=$con->myQuery("SELECT name,order_number,purchase_date,purchase_cost, quantity,id FROM consumables WHERE is_deleted=0")->fetchAll(PDO::FETCH_ASSOC);
                   						
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
                    <h1 class="page-header">Consumables Form</h1>
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
                    		<form class='form-horizontal' method='POST' action='create_consumables.php' enctype="multipart/form-data">
                    			<div class='form-group'>
                    				<label class='col-sm-12 col-md-3 control-label'> Name</label>
                    				<div class='col-sm-12 col-md-9'>
                    					<input type='text' class='form-control' placeholder='Enter Consumable Name' name='name'>
                    				</div>
                    			</div>
                    			<div class='form-group'>
                    				<label class='col-sm-12 col-md-3 control-label'> Order Number</label>
                    				<div class='col-sm-12 col-md-9'>
                    					<input type='text' class='form-control' placeholder='Enter Order Number' name='order_number'>
                    				</div>
                    			</div>
                                <div class='form-group'>
                                    <label class='col-sm-12 col-md-3 control-label'> Purchase Date</label>
                                    <div class='col-sm-12 col-md-9'>
                                        <input type='date' class='form-control' name='purchase_date'>
                                    </div>
                                </div>
                                <div class='form-group'>
                                    <label class='col-sm-12 col-md-3 control-label'> Purchase Cost</label>
                                    <div class='col-sm-12 col-md-9'>
                                        <input type='text' class='form-control' placeholder='Enter Purchase Cost' name='purchase_cost'>
                                    </div>
                                </div>
                                <div class='form-group'>
                                    <label class='col-sm-12 col-md-3 control-label'> Quantity</label>
                                    <div class='col-sm-12 col-md-9'>
                                        <input type='text' class='form-control' placeholder='Enter quantity' name='quantity'>
                                    </div>
                                </div>                                
                                <div class='form-group'>
                                    <div class='col-sm-12 col-md-9 col-md-offset-3 '>
                                        <a href='consumables.php' class='btn btn-default'>Cancel</a>
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