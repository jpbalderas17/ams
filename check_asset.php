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
        redirect("assets.php");
        die();
    }

    switch ($_GET['type']) {
        case 'out':
            # code...
            $type="Checkout";
            $asset_filter=" AND user_id=0 ";
            break;
        case 'in':
            # code...
            $type="Checkin";
            $asset_filter=" AND user_id<>0 ";

            break;
        default:
            redirect("assets.php");
        break;
    }
    #Validate if assets can be checkedin or checkedout
    if(!empty($_GET['id'])){

        $asset=$con->myQuery("SELECT id,model,asset_tag,serial_number,asset_name,image FROM qry_assets WHERE id=? {$asset_filter}",array($_GET['id']))->fetch(PDO::FETCH_ASSOC);
        if(empty($asset)){
            //Alert("Invalid asset selected.");
            Modal("Invalid Asset Selected");
            redirect("assets.php");
            die();
        }
    }


    $asset_status_labels=$con->myQuery("SELECT id,name FROM asset_status_labels WHERE is_deleted=0")->fetchAll(PDO::FETCH_ASSOC);
    $users=$con->myQuery("SELECT id,CONCAT(last_name,', ',first_name,' ',middle_name,' (',email,')') as display_name FROM users WHERE is_deleted=0")->fetchAll(PDO::FETCH_ASSOC);
                    						
	makeHead("Asset ".$type);
?>
<?php
	 require_once("template/header.php");
	require_once("template/sidebar.php");
?>
<div class='content-wrapper'>
    <div class='content-header'>
        <h1 class='page-header text-center text-green'>Asset <?php echo $type;?></h1>
    </div>
    <section class='content'>
        <div class="row">
                <div class='col-lg-12'>
                    <?php
                        Alert();
                    ?>    
                    <div class='col-sm-12 col-md-8 col-md-offset-2'>
                            <form class='form-horizontal' method='POST' action='move_asset.php' enctype="multipart/form-data">
                                <input type='hidden' name='id' value='<?php echo $asset['id']?>'>
                                <input type='hidden' name='type' value='<?php echo $_GET['type']?>'>
                                <div class='form-group'>
                                    <label class='col-sm-12 col-md-3 control-label'> Asset Tag</label>
                                    <div class='col-sm-12 col-md-9'>
                                        <input type='text' class='form-control' value='<?php echo !empty($asset)?$asset['asset_tag']:"" ?>' readonly>
                                    </div>
                                </div>
                                <div class='form-group'>
                                    <label class='col-sm-12 col-md-3 control-label'> Asset Name *</label>
                                    <div class='col-sm-12 col-md-9'>
                                        <input type='text' class='form-control' name='asset_name' value='<?php echo !empty($asset)?$asset['asset_name']:"" ?>' required>
                                    </div>
                                </div>
                                <?php
                                    if($type=="Checkout"):
                                ?>
                                <div class='form-group'>
                                    <label class='col-sm-12 col-md-3 control-label'> Model</label>
                                    <div class='col-sm-12 col-md-9'>
                                        <input type='text' class='form-control' value='<?php echo !empty($asset)?$asset['model']:"" ?>' readonly>
                                    </div>
                                </div>
                                <div class='form-group'>
                                    <label class='col-sm-12 col-md-3 control-label'> Checkout To*</label>
                                    <div class='col-sm-12 col-md-9'>
                                        <select class='form-control select2' name='user_id' data-placeholder="Select User" required>
                                            <?php
                                                echo makeOptions($users);
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class='form-group'>
                                    <label class='col-sm-12 col-md-3 control-label'> Checkout Date*</label>
                                    <div class='col-sm-12 col-md-9'>
                                        <input type='text' name='checkout_date' id='checkout_date' class='form-control date_picker' value='<?php echo date("Y-m-d") ?>' required>
                                    </div>
                                </div>
                                <div class='form-group'>
                                    <label class='col-sm-12 col-md-3 control-label'> Expected Checkin Date</label>
                                    <div class='col-sm-12 col-md-9'>
                                        <input type='text' name='expected_checkin_date' id='expected_checkin_date' class='form-control date_picker' value=''>
                                    </div>
                                </div>
                                <?php
                                    elseif($type=="Checkin"):
                                ?>
                                <div class='form-group'>
                                    <label class='col-sm-12 col-md-3 control-label'> Status*</label>
                                    <div class='col-sm-12 col-md-9'>
                                        <select class='form-control select2' name='asset_status_id' data-placeholder="Select Asset Status" <?php echo !(empty($asset))?"data-selected='".$asset['asset_status_id']."'":NULL ?> required>
                                            <?php
                                                echo makeOptions($asset_status_labels);
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <?php
                                    endif;
                                ?>
                                <div class='form-group'>
                                    <label class='col-sm-12 col-md-3 control-label'> Notes</label>
                                    <div class='col-sm-12 col-md-9'>
                                        <textarea class='form-control' name='notes' value='<?php echo !empty($asset)?$asset['notes']:"" ?>'></textarea>
                                    </div>
                                </div>
                                <div class='form-group'>
                                    <div class='col-sm-12 col-md-9 col-md-offset-3 '>
                                        <a href='assets.php' class='btn btn-flat btn-default'>Cancel</a>
                                        <button type='submit' class='btn btn-flat btn-success'> <span class='fa fa-check'></span> Save</button>
                                    </div>
                                    
                                </div>
                                
                            </form>
                        </div>
                </div>
            </div>
    </section>
</div>
<div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header"></h1>
                </div>

                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            
            <!-- /.row -->
</div>
<?php
Modal();
?>
<?php
	makeFoot();
?>