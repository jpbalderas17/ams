<?php
	require_once 'support/config.php';
	if(!isLoggedIn()){
		toLogin();
		die();
	}
    if(!AllowUser(array(1,2))){
        redirect("index.php");
    }
    if(empty($_GET['id']) || empty($_GET['type'])){
        redirect("consumables.php");
        die();
    }

    switch ($_GET['type']) {
        case 'out':
            # code...
            $type="Checkout";
            break;
        case 'in':
            # code...
            $type="Checkin";
            break;
        default:
            redirect("consumables.php");
        break;
    }
    #Validate if assets can be checkedin or checkedout
   
    if(!empty($_GET['id'])){
        $asset=$con->myQuery("SELECT id,name,quantity FROM consumables WHERE id=?",array($_GET['id']))->fetch(PDO::FETCH_ASSOC);
        if(empty($asset)){
            //Alert("Invalid asset selected.");
            Modal("Invalid Consumable Selected");
            redirect("consumables.php");
            die();
        }
    }
    


    $users=$con->myQuery("SELECT id,CONCAT(last_name,', ',first_name,' ',middle_name,' (',email,')') as display_name FROM users")->fetchAll(PDO::FETCH_ASSOC);
                    						
	makeHead("Consumables Checkout");
?>
<div id='wrapper'>
<?php
	 require_once 'template/navbar.php';
?>
</div>
<div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Consumable Checkout </h1>
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
                    		<form class='form-horizontal' method='POST' action='move_consumables.php' enctype="multipart/form-data">
                                <input type='hidden' name='id' value='<?php echo $asset['id']?>'>
                                <input type='hidden' name='type' value='<?php echo $_GET['type']?>'>
                                <div class='form-group'>
                                    <label class='col-sm-12 col-md-3 control-label'>Consumable Name</label>
                                    <div class='col-sm-12 col-md-9'>
                                        <input type='text' class='form-control' name='name' value='<?php echo !empty($asset)?$asset['name']:"" ?>' readonly>
                                    </div>
                                </div>
                                <div class='form-group'>
                                    <label class='col-sm-12 col-md-3 control-label'> Quantity</label>
                                    <div class='col-sm-12 col-md-9'>
                                        <input type='text' class='form-control' name='quantity' >                                        
                                    </div>
                                </div>                             
                                <div class='form-group'>
                                    <label class='col-sm-12 col-md-3 control-label'> Checkout To</label>
                                    <div class='col-sm-12 col-md-9'>
                                        <select class='form-control' name='user_id' data-placeholder="Select User" >
                                            <?php
                                                echo makeOptions($users);
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class='form-group'>
                                    <label class='col-sm-12 col-md-3 control-label'> Notes</label>
                                    <div class='col-sm-12 col-md-9'>
                                        <textarea class='form-control' name='notes' value='<?php echo !empty($asset)?$asset['notes']:"" ?>'></textarea>
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