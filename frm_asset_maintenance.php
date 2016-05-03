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
    	$maintenance=$con->myQuery("SELECT id,asset_id,asset_maintenance_type_id,title,start_date,completion_date,cost,notes FROM asset_maintenances WHERE id=?",array($_GET['id']))->fetch(PDO::FETCH_ASSOC);
        if(empty($maintenance)){
            //Alert("Invalid maintenance selected.");
            Modal("Invalid Maintenance Selected");
            redirect("asset_maintenance.php");
            die();
        }
    }

    $assets=$con->myQuery("SELECT id,asset_tag,asset_name,first_name,last_name,middle_name,model FROM qry_assets")->fetchAll(PDO::FETCH_ASSOC);
    $assets_select=array();
    foreach ($assets as $asset) {
    	$option['id']=$asset['id'];
    	$display="{$asset['asset_tag']} - {$asset['asset_name']} ";
    	if(is_null($asset['first_name'])){
    		$display.="(Unassigned)";
    	}
    	else
    	{
    		$display.="({$asset['last_name']}, {$asset['first_name']} {$asset['middle_name']})";
    	}

    	$display.=$asset['model'];
    	$option['value']=$display;
    	$assets_select[]=$option;
    	unset($option);
    }

    if(!empty($_SESSION[WEBAPP]['frm_inputs'])){
        if(!empty($maintenance)){
            $old_maintenance=$maintenance;
        }
        $maintenance=$_SESSION[WEBAPP]['frm_inputs'];
        if(!empty($old_maintenance)){
            $maintenance['id']=$old_maintenance['id'];
        }
    }

    $maintenance_types=$con->myQuery("SELECT id,name FROM asset_maintenance_types WHERE is_deleted=0")->fetchAll(PDO::FETCH_ASSOC);
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
        <h1 class='page-header text-center text-green'>Asset Maintenance Form</h1>
    </div>
    <section class='content'>
      <div class="row">
                <div class='col-lg-12'>
                    <?php
                        Alert();
                    ?>
                    <div class='row'>
                                <div class='col-sm-12 col-md-8 col-md-offset-2'>
                                    <form class='form-horizontal' method='POST' action='save_asset_maintenance.php' >
                                        <input type='hidden' name='id' value='<?php echo !empty($maintenance)?$maintenance['id']:""?>'>
                                        <div class='form-group'>
                                            <label class='col-sm-12 col-md-3 control-label'> Select Asset*</label>
                                            <div class='col-sm-12 col-md-9'>
                                                <?php
                                                    if(!empty($_GET['view_id'])){
                                                        $view=$con->myQuery("SELECT COUNT(id) FROM assets WHERE id=?",array($_GET['view_id']))->fetch(PDO::FETCH_ASSOC);
                                                        if(!empty($view)){
                                                            ?>
                                                            <select name='asset_id' class='form-control' data-placeholder="Select Asset" <?php echo "data-selected='".$_GET['view_id']."'"?> required>    

                                                            <?php
                                                        }
                                                        else{
                                                            ?>
                                                                <select name='asset_id' class='form-control' data-placeholder="Select Asset" <?php echo !(empty($maintenance))?"data-selected='".$maintenance['asset_id']."'":NULL ?> required>    
                                                            <?php
                                                        }
                                                    }
                                                    else{
                                                        ?>
                                                            <select name='asset_id' class='form-control' data-placeholder="Select Asset" <?php echo !(empty($maintenance))?"data-selected='".$maintenance['asset_id']."'":NULL ?> required>    
                                                        <?php
                                                    }
                                                ?>
                                                <?php
                                                    echo makeOptions($assets_select);
                                                ?>
                                                </select>

                                            </div>
                                        </div>
                                        <div class='form-group'>
                                            <label class='col-sm-12 col-md-3 control-label'> Maintenance Type*</label>
                                            <div class='col-sm-12 col-md-9'>
                                                <select name='asset_maintenance_type_id' class='form-control' data-placeholder="Select Maintenance Type" <?php echo !(empty($maintenance))?"data-selected='".$maintenance['asset_maintenance_type_id']."'":NULL ?> required>    
                                                <?php
                                                    echo makeOptions($maintenance_types);
                                                ?>
                                                </select>

                                            </div>
                                        </div>
                                        <div class='form-group'>
                                            <label class='col-sm-12 col-md-3 control-label'> Title* </label>
                                            <div class='col-sm-12 col-md-9'>
                                                <input type='text' class='form-control' placeholder='Enter Title' name='title'  value='<?php echo !empty($maintenance)?$maintenance['title']:"" ?>' required>
                                            </div>
                                        </div>
                                        <div class='form-group'>
                                            <label class='col-sm-12 col-md-3 control-label'> Start Date*</label>
                                            <div class='col-sm-12 col-md-9'>
                                                <?php
                                                $start_date="";
                                                 if(!empty($maintenance)){
                                                    $start_date=$maintenance['start_date'];
                                                    if($start_date=="0000-00-00" || $start_date=='mm/dd/yyyy'){
                                                        $start_date="";
                                                    }
                                                    else
                                                    {
                                                        $start_date=inputmask_format_date($start_date);
                                                    }
                                                 }
                                                ?>
                                                <input type='text' class='form-control date_picker' name='start_date'  value='<?php echo $start_date; ?>'>
                                            </div>
                                        </div>
                                        <div class='form-group'>
                                            <label class='col-sm-12 col-md-3 control-label'> Completion Date </label>
                                            <div class='col-sm-12 col-md-9'>
                                                <?php
                                                $completion_date="";
                                                 if(!empty($maintenance)){
                                                    $completion_date=$maintenance['completion_date'];
                                                    if($completion_date=="0000-00-00" || $completion_date=='mm/dd/yyyy'){
                                                        $completion_date="";
                                                    }
                                                    else
                                                    {
                                                        $completion_date=inputmask_format_date($completion_date);
                                                    }
                                                 }
                                                ?>
                                                <input type='text' class='form-control date_picker' name='completion_date'  value='<?php echo $completion_date; ?>' required>
                                            </div>
                                        </div>
                                        <div class='form-group'>
                                            <label class='col-sm-12 col-md-3 control-label'>Maintenance Cost *</label>
                                            <div class='col-sm-12 col-md-9'>
                                                <input type='text' class='form-control currency' placeholder='0.00' name='cost' value='<?php echo !empty($maintenance)?$maintenance['cost']:"" ?>' class='required'>
                                            </div>
                                        </div>

                                        <div class='form-group'>
                                            <label class='col-sm-12 col-md-3 control-label'> Notes</label>
                                            <div class='col-sm-12 col-md-9'>
                                                <textarea class='form-control' name='notes' value='<?php echo !empty($maintenance)?$maintenance['notes']:"" ?>'></textarea>
                                            </div>
                                        </div>
                                        <div class='form-group'>
                                            <div class='col-sm-12 col-md-9 col-md-offset-3 '>
                                                <?php
                                                    if(!empty($view)):
                                                ?>
                                                    <a href='view_asset.php?id=<?php echo htmlspecialchars($_GET['view_id']);?>' class='btn btn-flat btn-default'>Cancel</a>
                                                <?php
                                                    else:
                                                ?>
                                                    <a href='asset_maintenances.php' class='btn btn-flat btn-default'>Cancel</a>
                                                <?php
                                                    endif;
                                                ?>
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
        if(!empty($maintenance)):
    ?>
        $(document).ready(function(){
            
            // console.log($("#purchase_date").inputmask("hasMasked"));

        });
    <?php
        endif;
        if(!empty($_SESSION[WEBAPP]['frm_inputs'])){
            unset($_SESSION[WEBAPP]['frm_inputs']);
        }
    ?>
<?php
Modal();
?>
<?php
	makeFoot();
?>