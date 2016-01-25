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
        $asset=$con->myQuery("SELECT name,order_number,purchase_date,purchase_cost,category_id,quantity,id FROM consumables WHERE id=?",array($_GET['id']))->fetch(PDO::FETCH_ASSOC);
        if(empty($asset)){
            //Alert("Invalid consumables selected.");
            Modal("Invalid Consumables Selected");
            redirect("consumables.php");
            die();
        }
    }
    
    $consumables=$con->myQuery("SELECT name,order_number,purchase_date,purchase_cost, quantity,id FROM consumables WHERE is_deleted=0")->fetchAll(PDO::FETCH_ASSOC);
    $category_type=$con->myQuery("SELECT id,name,category_type_id FROM categories WHERE category_type_id=2 AND is_deleted=0")->fetchAll(PDO::FETCH_ASSOC);              						
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
                    <h1 class="page-header">Consumable Form</h1>
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
                    			<input type='hidden' name='id' value='<?php echo !empty($asset)?$asset['id']:""?>'>
                                <div class='form-group'>
                    				<label class='col-sm-12 col-md-3 control-label'> Name</label>
                    				<div class='col-sm-12 col-md-9'>
                    					<input type='text' class='form-control' placeholder='Enter Consumable Name' name='name' value='<?php echo !empty($asset)?$asset['name']:"" ?>'>
                    				</div>
                    			</div>
                                <div class='form-group'>
                    				<label class='col-sm-12 col-md-3 control-label'> Order Number</label>
                    				<div class='col-sm-12 col-md-9'>
                    					<input type='text' class='form-control' placeholder='Enter Order Number' name='order_number' value='<?php echo !empty($asset)?$asset['order_number']:"" ?>'>
                    				</div>
                    			</div>
                                <div class='form-group'>
                                    <label class='col-sm-12 col-md-3 control-label'> Category Type</label>
                                    <div class='col-sm-12 col-md-9'>
                                        <select class='form-control' name='category_type' data-placeholder='Select a Category' <?php echo!(empty($asset))?"data-selected='".$asset['category_id']."'":NULL ?>>
                                            <?php
                                            echo makeOptions($category_type);
                                            ?>
                                        </select>
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
                                        <input type='date' class='form-control' name='purchase_date' value='<?php echo $purchase_date; ?>'>
                                    </div>
                                </div>
                                <div class='form-group'>
                                    <label class='col-sm-12 col-md-3 control-label'> Purchase Cost</label>
                                    <div class='col-sm-12 col-md-9'>
                                        <input type='text' class='form-control' placeholder='Enter Purchase Cost' name='purchase_cost' value='<?php echo !empty($asset)?$asset['purchase_cost']:"" ?>''>
                                    </div>
                                </div>
                                <div class='form-group'>
                                    <label class='col-sm-12 col-md-3 control-label'> Quantity</label>
                                    <div class='col-sm-12 col-md-9'>
                                        <input type='text' class='form-control' placeholder='Enter quantity' name='quantity' value='<?php echo !empty($asset)?$asset['quantity']:"" ?>'>
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
Modal();
?>
<?php
	makeFoot();
?>