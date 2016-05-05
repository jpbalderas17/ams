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


    if(!empty($_SESSION[WEBAPP]['frm_inputs'])){
        if(!empty($asset)){
            $old_asset=$asset;
        }
        $asset=$_SESSION[WEBAPP]['frm_inputs'];
        if(!empty($old_asset)){
            $asset['asset_tag']=$old_asset['asset_tag'];
            $asset['id']=$old_asset['id'];
        }
    }

    $asset_models=$con->myQuery("SELECT id,name FROM asset_models WHERE is_deleted=0")->fetchAll(PDO::FETCH_ASSOC);
    $asset_status_labels=$con->myQuery("SELECT id,name FROM asset_status_labels WHERE is_deleted=0")->fetchAll(PDO::FETCH_ASSOC);
    $locations=$con->myQuery("SELECT id,name FROM locations WHERE is_deleted=0")->fetchAll(PDO::FETCH_ASSOC);
                    						
	makeHead("Assets");
?>

<?php
	 require_once("template/header.php");
	require_once("template/sidebar.php");
?>
<div class='content-wrapper'>
    <div class='content-header'>
        <h1 class="page-header text-center text-green">Asset Form</h1>
    </div>
    <section class='content'>
            <div class="row">
                <div class='col-lg-12'>
                    <?php
                        Alert();
                    ?>    
                    <div class='row'>
                        <div class='col-sm-12 col-md-8 col-md-offset-2'>
                            <form class='form-horizontal' method='POST' action='create_asset.php' enctype="multipart/form-data" onsubmit='return validate(this)'>
                                <input type='hidden' name='id' value='<?php echo !empty($asset)?$asset['id']:""?>'>
                                <?php

                                    if(!empty($asset) && !empty($asset['asset_tag'])):
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
                                    <label class='col-sm-12 col-md-3 control-label'> Model*</label>
                                    <div class='col-sm-12 col-md-9'>
                                        
                                        <div class='row'>
                                            <div class='col-sm-11'>
                                                <select class='form-control' name='model_id' data-placeholder="Select a Model" <?php echo!(empty($asset))?"data-selected='".$asset['asset_model_id']."'":NULL ?> required>
                                                    <?php
                                                        echo makeOptions($asset_models);
                                                    ?>
                                                </select>
                                            </div>
                                            <div class='col-ms-1'>
                                                <a href='frm_asset_models.php' class='btn btn-sm btn-flat btn-success'><span class='fa fa-plus'></span></a>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class='form-group'>
                                    <label class='col-sm-12 col-md-3 control-label'> Status*</label>
                                    <div class='col-sm-12 col-md-9'>
                                        <?php
                                            if(AllowUser(array(1))):
                                        ?>
                                        <div class='row'>
                                            <div class='col-sm-11'>
                                                <select class='form-control' name='asset_status_id' data-placeholder="Select Asset Status" <?php echo !(empty($asset))?"data-selected='".$asset['asset_status_id']."'":NULL ?> required>
                                                    <?php
                                                        echo makeOptions($asset_status_labels);
                                                    ?>
                                                </select>
                                            </div>
                                            <div class='col-ms-1'>
                                                <a href='asset_status_labels.php' class='btn btn-sm btn-flat btn-success'><span class='fa fa-plus'></span></a>
                                            </div>
                                        </div>
                                        <?php
                                            else:
                                        ?>
                                            <select class='form-control' name='asset_status_id' data-placeholder="Select Asset Status" <?php echo !(empty($asset))?"data-selected='".$asset['asset_status_id']."'":NULL ?>>
                                                    <?php
                                                        echo makeOptions($asset_status_labels);
                                                    ?>
                                                </select>
                                        <?php
                                            endif;
                                        ?>
                                    </div>
                                </div>
                                <div class='form-group'>
                                    <label class='col-sm-12 col-md-3 control-label'> Serial Number*</label>
                                    <div class='col-sm-12 col-md-9'>
                                        <input type='text' class='form-control' name='serial_number' placeholder='Enter Serial Number' value='<?php echo !empty($asset)?$asset['serial_number']:"" ?>' required>
                                    </div>
                                </div>
                                <div class='form-group'>
                                    <label class='col-sm-12 col-md-3 control-label'> Asset Name*</label>
                                    <div class='col-sm-12 col-md-9'>
                                        <input type='text' class='form-control' placeholder='Enter Asset Name' name='asset_name'  value='<?php echo !empty($asset)?$asset['asset_name']:"" ?>' required>
                                    </div>
                                </div>
                                <div class='form-group'>
                                    <label class='col-sm-12 col-md-3 control-label'> Order Number*</label>
                                    <div class='col-sm-12 col-md-9'>
                                        <input type='text' class='form-control numeric' placeholder='Enter Order Number' name='order_number' value='<?php echo !empty($asset)?$asset['order_number']:"" ?>' required>
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
                                            }
                                         }
                                        ?>
                                        <input type='text' id='purchase_date' class='form-control date_picker' name='purchase_date'  value='<?php echo $purchase_date; ?>' required>
                                    </div>
                                </div>
                                
                                <div class='form-group'>
                                    <label class='col-sm-12 col-md-3 control-label'> Purchase Cost *</label>
                                    <div class='col-sm-12 col-md-9'>
                                        <input type='text' min='0' class='form-control currency' placeholder='0.00' name='purchase_cost' value='<?php echo !empty($asset)?$asset['purchase_cost']:"" ?>' required>
                                    </div>
                                </div>

                                <div class='form-group'>
                                    <label class='col-sm-12 col-md-3 control-label'> Notes</label>
                                    <div class='col-sm-12 col-md-9'>
                                        <textarea class='form-control' name='notes' value='<?php echo !empty($asset)?$asset['notes']:"" ?>'></textarea>
                                    </div>
                                </div>
                                <div class='form-group'>
                                    <label class='col-sm-12 col-md-3 control-label'> Default Location*</label>
                                    <div class='col-sm-12 col-md-9'>
                                        <div class='row'>
                                            <div class='col-sm-11'>
                                                <select name='location_id' class='form-control' data-placeholder="Select Default Location" <?php echo !(empty($asset))?"data-selected='".$asset['location_id']."'":NULL ?> required>    
                                                <?php
                                                    echo makeOptions($locations);
                                                ?>
                                                </select>
                                            </div>
                                            <div class='col-ms-1'>
                                                <a href='locations.php' class='btn btn-sm btn-flat btn-success'><span class='fa fa-plus'></span></a>
                                            </div>
                                        </div>
                                        

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
                                        <input type='file' class='form-control' name='image' accept='image/*'>
                                    </div>
                                </div>

                                <div class='form-group'>
                                    <div class='col-sm-12 col-md-9 col-md-offset-3 '>
                                        <a href='assets.php' class='btn btn-flat btn-default' onclick="return confirm('<?php echo !empty($asset) && !empty($asset['id'])?'Are you sure you want to cancel the modification of the asset?':'Are you sure you want to cancel the creation of the new asset?';?>')">Cancel</a>
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

    if(is_future_date($("#purchase_date").val())){
      alert("Purchase date cannot be a future date.");
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