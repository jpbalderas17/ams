<?php
	require_once 'support/config.php';
	if(!isLoggedIn()){
		toLogin();
		die();
	}
    if(!AllowUser(array(1,2))){
        redirect("index.php");
    }
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
                    <h1 class="page-header">Assets</h1>
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
                        <div class='col-sm-12'>
                                <a href='frm_assets.php' class='btn btn-success pull-right'> <span class='fa fa-plus'></span> Create New</a>
                        </div>
                    </div>
                    <br/>    

                    <div class='panel panel-default'>
                        
                        <div class='panel-body ' >
                            <div class='dataTable_wrapper '>
                                <table class='table responsive table-bordered table-condensed table-hover ' id='dataTables'>
                                    <thead>
                                        <tr>
                                            <?php
                                            if($_SESSION[WEBAPP]['user']['user_type']==1 || $_SESSION[WEBAPP]['user']['user_type']==2):
                                            ?>
                                                <th></th>
                                            <?php
                                            endif;
                                            ?>
                                            <th>Asset Tag</th>
                                            <th>Serial Number</th>
                                            <th>Asset Name</th>
                                            <th>Model</th>
                                            <th>Status</th>
                                            <th>Location</th>
                                            <th>Category</th>
                                            <th class='date-td'>EOL</th>
                                            <th>Notes</th>
                                            <th>Order Number</th>
                                            <th class='date-td'>Checkout Date</th>
                                            <th class='date-td'>Expected Checkin Date</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            if(empty($_GET['status']) || $_GET['status']=='All'){
                                                $assets=$con->myQuery("SELECT asset_tag,serial_number,asset_name,model,asset_status,asset_status_label,location,category,eol,notes,order_number,check_out_date,expected_check_in_date,id,CONCAT(last_name,', ',first_name,' ',middle_name)as current_holder FROM qry_assets WHERE is_deleted=0")->fetchAll(PDO::FETCH_ASSOC);
                                            }
                                            else{
                                                if($_GET['status']!="Deployed"){
                                                    if($_GET['status']=='Deployable'){
                                                        $assets=$con->myQuery("SELECT asset_tag,serial_number,asset_name,model,asset_status,asset_status_label,location,category,eol,notes,order_number,check_out_date,expected_check_in_date,id,CONCAT(last_name,', ',first_name,' ',middle_name)as current_holder FROM qry_assets WHERE is_deleted=0 AND asset_status_label=? AND qry_assets.user_id=0",array($_GET['status']))->fetchAll(PDO::FETCH_ASSOC);

                                                    }
                                                    else{

                                                        $assets=$con->myQuery("SELECT asset_tag,serial_number,asset_name,model,asset_status,asset_status_label,location,category,eol,notes,order_number,check_out_date,expected_check_in_date,id,CONCAT(last_name,', ',first_name,' ',middle_name)as current_holder FROM qry_assets WHERE is_deleted=0 AND asset_status_label=?",array($_GET['status']))->fetchAll(PDO::FETCH_ASSOC);
                                                    }
                                                }
                                                else{
                                                 $assets=$con->myQuery("SELECT asset_tag,serial_number,asset_name,model,asset_status,asset_status_label,location,category,eol,notes,order_number,check_out_date,expected_check_in_date,id,CONCAT(last_name,', ',first_name,' ',middle_name)as current_holder FROM qry_assets WHERE is_deleted=0 AND check_out_date<>'0000-00-00'")->fetchAll(PDO::FETCH_ASSOC);   
                                                }
                                            }

                                            foreach ($assets as $asset):
                                        ?>
                                            <tr>
                                                <?php
                                                if($_SESSION[WEBAPP]['user']['user_type']==1 || $_SESSION[WEBAPP]['user']['user_type']==2):
                                                ?>
                                                    <th>
                                                        <a href='barcode/download.php?id=<?php echo $asset['id'] ?>' class='btn btn-default'><span class='fa fa-barcode'></span></a>
                                                    </th>
                                                <?php
                                                endif
                                                ?>
                                                <?php
                                                    foreach ($asset as $key => $value):
                                                    if($key=='id'):
                                                ?>
                                                    <td>
                                                        <?php
                                                            if($asset['asset_status']==4):
                                                            if(empty($asset['check_out_date']) || $asset['check_out_date']=="0000-00-00"):
                                                        ?>
                                                            <a class='btn btn-sm btn-info' href='check_asset.php?id=<?php echo $value;?>&type=out'><span class='fa fa-arrow-right'></span> Check Out</a>
                                                        <?php
                                                            else:
                                                        ?>
                                                            <a class='btn btn-sm btn-info' href='check_asset.php?id=<?php echo $value;?>&type=in'><span class='fa fa-arrow-left'></span> Check In</a>
                                                        <?php
                                                            endif;
                                                            endif;
                                                        ?>
                                                        <a class='btn btn-sm btn-warning' href='frm_assets.php?id=<?php echo $value;?>'><span class='fa fa-pencil'></span></a>
                                                        <a class='btn btn-sm btn-danger' href='delete.php?id=<?php echo $value?>&t=a' onclick='return confirm("This asset will be deleted.")'><span class='fa fa-trash'></span></a>
                                                    </td>
                                                <?php
                                                    elseif($key=="check_out_date" || $key=="expected_check_in_date" || $key=="EOL"):
                                                ?>
                                                    <td>
                                                        <?php
                                                            if($value!="0000-00-00"){
                                                                echo htmlspecialchars($value);                                                                
                                                            }
                                                        ?>
                                                    </td>

                                                <?php
                                                    elseif($key=="asset_tag"):
                                                ?>
                                                    <td>
                                                        <a href='view_asset.php?id=<?= $asset['id']?>'><?php echo htmlspecialchars($value)?></a>
                                                    </td>
                                                <?php
                                                    elseif($key=="asset_status_label"):
                                                ?>
                                                        <td>
                                                            <?php
                                                                if($asset['check_out_date']!="0000-00-00"){
                                                                    echo "Deployed (".htmlspecialchars($asset['current_holder']).")";
                                                                }
                                                                else{
                                                                    echo htmlspecialchars($value);
                                                                }
                                                            ?>
                                                        </td>
                                                <?php
                                                    elseif($key=="asset_status" || $key=="current_holder"):
                                                        #skipped keys
                                                    else:
                                                ?>
                                                    <td>
                                                        <?php
                                                            echo htmlspecialchars($value);
                                                        ?>
                                                    </td>
                                                <?php
                                                    endif;
                                                    endforeach;
                                                ?>
                                            </tr>
                                        <?php
                                            endforeach;
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.row -->
</div>
<script>
    $(document).ready(function() {
        $('#dataTables').DataTable({
                 "scrollY": true,
                "scrollX": true
        });
    });
    </script>
<?php
    Modal();
	makeFoot();
?>