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
        $asset=$con->myQuery("SELECT id,name FROM consumables WHERE id=?",array($_GET['id']))->fetch(PDO::FETCH_ASSOC);
        if(empty($asset)){
            //Alert("Invalid asset selected.");
            Modal("Invalid Consumable Selected");
            redirect("consumables.php");
            die();
        }
    }
    // var_dump($_SESSION[WEBAPP]['frm_inputs']);
    if(!empty($_SESSION[WEBAPP]['frm_inputs'])){
        if(!empty($asset)){
            $old_asset=$asset;
        }
        $asset=$_SESSION[WEBAPP]['frm_inputs'];
        if(!empty($old_asset)){
            $asset['id']=$old_asset['id'];
        }
    }
    // die;

    $users=$con->myQuery("SELECT id,CONCAT(last_name,', ',first_name,' ',middle_name,' (',email,')') as display_name FROM users WHERE is_deleted=0")->fetchAll(PDO::FETCH_ASSOC);
                    						
	makeHead("Consumables Checkout");
?>
<?php
	 require_once("template/header.php");
	require_once("template/sidebar.php");
?>
<div class='content-wrapper'>
    <div class='content-header'>
        <h1 class='page-header text-center text-green'>Consumable Checkout</h1>
    </div>
    <section class='content'>
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
                                                <input type='text' class='form-control unsigned_integer' name='quantity' value='<?php echo !empty($asset['quantity'])?$asset['quantity']:"" ?>' required placeholder='0'>                                        
                                            </div>
                                        </div>                             
                                        <div class='form-group'>
                                            <label class='col-sm-12 col-md-3 control-label'> Checkout To</label>
                                            <div class='col-sm-12 col-md-9'>
                                                <select class='form-control' name='user_id' data-placeholder="Select User" required="" <?php echo!(empty($asset))?"data-selected='".$asset['user_id']."'":NULL ?>>
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
                                                <a href='consumables.php' class='btn btn-flat btn-default'>Cancel</a>
                                                <button type='submit' class='btn btn-flat btn-success'> <span class='fa fa-check'></span> Save</button>
                                            </div>
                                            
                                        </div>
                                        
                                    </form>
                        </div>
                    </div>

                </div>
            </div>
    </section>
</div>
<?php
if(!empty($_SESSION[WEBAPP]['frm_inputs'])){
    unset($_SESSION[WEBAPP]['frm_inputs']);
}
Modal();
?>
<?php
	makeFoot();
?>