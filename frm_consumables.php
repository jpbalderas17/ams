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
    // var_dump($_SESSION[WEBAPP]['frm_inputs']);
    // die;
      if(!empty($_SESSION[WEBAPP]['frm_inputs'])){
        if(!empty($asset)){
            $old_asset=$asset;
        }
        $asset=$_SESSION[WEBAPP]['frm_inputs'];
        if(!empty($old_asset)){
            $asset['id']=$old_asset['id'];
        }
    }

    $consumables=$con->myQuery("SELECT name,order_number,purchase_date,purchase_cost, quantity,id FROM consumables WHERE is_deleted=0")->fetchAll(PDO::FETCH_ASSOC);
    $category_type=$con->myQuery("SELECT id,name,category_type_id FROM categories WHERE category_type_id=2 AND is_deleted=0")->fetchAll(PDO::FETCH_ASSOC);              						
	makeHead("Consumables");
?>
<?php
	 require_once("template/header.php");
	require_once("template/sidebar.php");
?>
<div class='content-wrapper'>
    <div class='content-header'>
        <h1 class="page-header text-center text-green">Consumable Form</h1>
    </div>
    <section class='content'>
        <div class="row">
            <div class='col-lg-12'>
                <?php
                    Alert();
                ?>    
                <div class='row'>
                    <div class='col-sm-12 col-md-8 col-md-offset-2'>
                      <form class='form-horizontal' method='POST' action='create_consumables.php' enctype="multipart/form-data" onsubmit='return validate(this)'>
                            <input type='hidden' name='id' value='<?php echo !empty($asset)?$asset['id']:""?>'>
                            <div class='form-group'>
                                <label class='col-sm-12 col-md-3 control-label'> Name*</label>
                                <div class='col-sm-12 col-md-9'>
                                    <input type='text' class='form-control' placeholder='Enter Consumable Name' name='name' value='<?php echo !empty($asset)?$asset['name']:"" ?>' required>
                                </div>
                            </div>
                            <div class='form-group'>
                                <label class='col-sm-12 col-md-3 control-label'> Order Number*</label>
                                <div class='col-sm-12 col-md-9'>
                                    <input type='text' class='form-control' placeholder='Enter Order Number' name='order_number' value='<?php echo !empty($asset)?$asset['order_number']:"" ?>' required>
                                </div>
                            </div>
                            <div class='form-group'>
                                <label class='col-sm-12 col-md-3 control-label'> Category Type*</label>
                                <div class='col-sm-12 col-md-9'>
                                    <select class='form-control' name='category_type' data-placeholder='Select a Category' <?php echo!(empty($asset))?"data-selected='".$asset['category_id']."'":NULL ?> required>
                                        <?php
                                        echo makeOptions($category_type);
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class='form-group'>
                                <label class='col-sm-12 col-md-3 control-label'> Purchase Date*</label>
                                <div class='col-sm-12 col-md-9'>
                                    <?php
                                    $purchase_date="";
                                     if(!empty($asset)){
                                        $purchase_date=$asset['purchase_date'];
                                        if($purchase_date=="0000-00-00"){
                                            $purchase_date="";
                                        }
                                        else
                                        {
                                            $purchase_date=inputmask_format_date($purchase_date);
                                            // echo $purchase_date;
                                        }
                                     }
                                    ?>
                                    <input type='text' class='form-control date_picker' id='purchase_date' name='purchase_date' value='<?php echo $purchase_date; ?>' required>
                                </div>
                            </div>
                            <div class='form-group'>
                                <label class='col-sm-12 col-md-3 control-label'> Purchase Cost*</label>
                                <div class='col-sm-12 col-md-9'>
                                    <input type='text' min='0' class='form-control currency' placeholder='0.00' name='purchase_cost' value='<?php echo !empty($asset)?$asset['purchase_cost']:"" ?>' required>
                                </div>
                            </div>
                            <div class='form-group'>
                                <label class='col-sm-12 col-md-3 control-label'> Quantity*</label>
                                <div class='col-sm-12 col-md-9'>
                                    <input type='text' id='quantity' min='0' class='form-control unsigned_integer' placeholder='0' name='quantity' value='<?php echo !empty($asset)?$asset['quantity']:"" ?>' required>
                                </div>
                            </div>                                
                            <div class='form-group'>
                                <div class='col-sm-12 col-md-9 col-md-offset-3 '>
                                    <a href='consumables.php' class='btn btn-flat btn-default' onclick="return confirm('<?php echo !empty($asset)?'Are you sure you want to cancel the modification of the consumable?':'Are you sure you want to cancel the creation of the new consumable?';?>')">Cancel</a>
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
<script type="text/javascript">
    <?php
        if(!empty($asset)):
    ?>
        $(document).ready(function(){
            $("#purchase_date").val("<?php echo $purchase_date?>");
            // console.log($("#purchase_date").inputmask("hasMasked"));

        });
    <?php
        endif;
    ?>
   function validate(frm) {
    str_error="";
    if($("#quantity").val()<1){
        str_error+="Quantity should be greater than 0 \n";
        //return false;
    }

    if(is_future_date($("#purchase_date").val())){
      // alert("Purchase date cannot be a future date.");
      str_error+="Purchase date cannot be a future date.";
      // return false;
    }

    if(str_error!==""){
        alert("You have the following errors:\n"+str_error);
        return false;
    }

    // return false;
    return true;
  }
</script>
<?php

if(!empty($_SESSION[WEBAPP]['frm_inputs'])){
    unset($_SESSION[WEBAPP]['frm_inputs']);
}

Modal();
?>
<?php
	makeFoot();
?>