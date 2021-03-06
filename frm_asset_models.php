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
        $asset_model=$con->myQuery("SELECT id,name,manufacturer_id,category_id,image,model_no,EOL,depreciation_id FROM asset_models WHERE id=?",array($_GET['id']))->fetch(PDO::FETCH_ASSOC);
        if(empty($asset_model)){
            //Alert("Invalid asset selected.");
            Modal("Invalid Asset Model Selected");
            redirect("asset_models.php");
            die();
        }
    }


    if(!empty($_SESSION[WEBAPP]['frm_inputs'])){
        if(!empty($asset_model)){
            $old_asset_model=$asset_model;
        }
        $asset_model=$_SESSION[WEBAPP]['frm_inputs'];
        if(!empty($old_asset_model)){
            $asset_model['id']=$old_asset_model['id'];
        }
    }

    $manufacturers=$con->myQuery("SELECT id,name FROM manufacturers WHERE is_deleted=0")->fetchAll(PDO::FETCH_ASSOC);
    $categories=$con->myQuery("SELECT id,name FROM categories WHERE is_deleted=0 AND category_type_id=1")->fetchAll(PDO::FETCH_ASSOC);
    $depreciations=$con->myQuery("SELECT id,name FROM depreciations WHERE is_deleted=0")->fetchAll(PDO::FETCH_ASSOC);
                    						
	makeHead("Asset Models");
?>
<?php
	 require_once("template/header.php");
	require_once("template/sidebar.php");
?>
<div class='content-wrapper'>
    <div class='content-header'>
        <h1 class='page-header text-center text-green'>Asset Models Form</h1>
    </div>
    <section class='content'>
    <div class='row'>
        <div class='col-md-12'>
            <?php
                Alert();
            ?>    
            <div class='row'>
                <div class='col-sm-12 col-md-8 col-md-offset-2'>
                    <form class='form-horizontal' method='POST' action='save_asset_model.php'>
                        <input type='hidden' name='id' value='<?php echo !empty($asset_model)?$asset_model['id']:""?>'>

                        <div class='form-group'>
                            <label class='col-sm-12 col-md-3 control-label'> Name*</label>
                            <div class='col-sm-12 col-md-9'>
                                <input type='text' class='form-control' name='model_name' placeholder='Enter Model Name' value='<?php echo !empty($asset_model)?$asset_model['name']:"" ?>' required>
                            </div>
                        </div>
                        <div class='form-group'>
                            <label class='col-sm-12 col-md-3 control-label'> Model Number</label>
                            <div class='col-sm-12 col-md-9'>
                                <input type='text' class='form-control' name='model_no' placeholder='Enter Model Number' value='<?php echo !empty($asset_model)?$asset_model['model_no']:"" ?>' required>
                            </div>
                        </div>
                        <div class='form-group'>
                            <label class='col-sm-12 col-md-3 control-label'> Manufacturer*</label>
                            <div class='col-sm-12 col-md-9'>
                                <div class='row'>
                                    <div class='col-sm-11'>
                                        <select class='form-control' name='manufacturer_id' data-placeholder="Select a Manufacturer" <?php echo!(empty($asset_model))?"data-selected='".$asset_model['manufacturer_id']."'":NULL ?> required>
                                            <?php
                                                echo makeOptions($manufacturers);
                                            ?>
                                        </select>
                                    </div>
                                    <div class='col-ms-1'>
                                        <a href='manufacturers.php' class='btn btn-flat btn-sm btn-success'><span class='fa fa-plus'></span></a>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                        <div class='form-group'>
                            <label class='col-sm-12 col-md-3 control-label'> Category*</label>
                            <div class='col-sm-12 col-md-9'>
                                <div class='row'>
                                    <div class='col-sm-11'>
                                        <select class='form-control' name='category_id' data-placeholder="Select a Category" <?php echo!(empty($asset_model))?"data-selected='".$asset_model['category_id']."'":NULL ?>>
                                            <?php
                                                echo makeOptions($categories);
                                            ?>
                                        </select>
                                    </div>
                                    <div class='col-ms-1'>
                                        <a href='categories.php' class='btn btn-flat btn-sm btn-success'><span class='fa fa-plus'></span></a>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class='form-group'>
                            <label class='col-sm-12 col-md-3 control-label'> End of Life (EOL) *</label>
                            <div class='col-sm-12 col-md-9'>
                                <?php
                                $EOL="";
                                 if(!empty($asset_model)){
                                    $EOL=$asset_model['EOL'];
                                    if($EOL=="0000-00-00"){
                                        $EOL="";
                                    }
                                     else
                                    {
                                        $EOL=inputmask_format_date($EOL);
                                    }
                                 }
                                ?>
                                <input type='text' class='form-control date_picker' name='EOL'  id='EOL' value='<?php echo $EOL; ?>' required>
                            </div>
                        </div>
                        <div class='form-group'>
                            <label class='col-sm-12 col-md-3 control-label'> Depreciation*</label>
                            <div class='col-sm-12 col-md-9'>
                                
                                <div class='row'>
                                    <div class='col-sm-11'>
                                        <select class='form-control' name='depreciation_id' data-placeholder="Select a Depreciation" <?php echo!(empty($asset_model))?"data-selected='".$asset_model['depreciation_id']."'":NULL ?>>
                                            <?php
                                                echo makeOptions($depreciations);
                                            ?>
                                        </select>
                                    </div>
                                    <div class='col-ms-1'>
                                        <a href='depreciations.php' class='btn btn-flat btn-sm btn-success'><span class='fa fa-plus'></span></a>
                                    </div>
                                </div>

                            </div>
                        </div>
                        
                        <div class='form-group'>
                            <div class='col-sm-12 col-md-9 col-md-offset-3 '>
                                <a href='asset_models.php' class='btn btn-flat btn-default' onclick="return confirm('<?php echo !empty($asset_model['id'])?'Are you sure you want to cancel the modification of the asset model?':'Are you sure you want to cancel the creation of the new asset model?';?>')">Cancel</a>
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
        if(!empty($asset_model)):
    ?>
        $(document).ready(function(){
            $("#EOL").val("<?php echo $EOL?>");
            // console.log($("#purchase_date").inputmask("hasMasked"));

        });
    <?php
        endif;
    ?>
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