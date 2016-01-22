<?php
	require_once 'support/config.php';
	if(!isLoggedIn()){
		toLogin();
		die();
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
                    <h1 class="page-header">Deleted Assets</h1>
                </div>

                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class='col-lg-12'>
                    <?php
                        Alert();
                    ?>
                    <div class='panel panel-default'>
                        
                        <div class='panel-body ' >
                            <div class='dataTable_wrapper '>
                                <table class='table responsive table-bordered table-condensed table-hover ' id='dataTables'>
                                    <thead>
                                        <tr>
                                            <th>Asset Tag</th>
                                            <th>Serial Number</th>
                                            <th>Asset Name</th>
                                            <th>Model</th>
                                            <th>Category</th>
                                            <th>EOL</th>
                                            <th>Notes</th>
                                            <th>Order Number</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            if(empty($_GET['status'])){
                                                $assets=$con->myQuery("SELECT id,asset_tag,serial_number,asset_name,model,category,eol,notes,order_number,CONCAT(last_name,', ',first_name,' ',middle_name)as current_holder FROM qry_assets WHERE is_deleted=1")->fetchAll(PDO::FETCH_ASSOC);
                                            }
                                            else{
                                                if($_GET['status']!="Deployed"){
                                                    $assets=$con->myQuery("SELECT id,asset_tag,serial_number,asset_name,model,category,eol,notes,order_number,CONCAT(last_name,', ',first_name,' ',middle_name)as current_holder FROM qry_assets WHERE is_deleted=1 AND asset_status_label=?",array($_GET['status']))->fetchAll(PDO::FETCH_ASSOC);
                                                }
                                                else{
                                                 $assets=$con->myQuery("SELECT id,asset_tag,serial_number,asset_name,model,category,eol,notes,order_number,CONCAT(last_name,', ',first_name,' ',middle_name)as current_holder FROM qry_assets WHERE is_deleted=1 AND check_out_date<>'0000-00-00'")->fetchAll(PDO::FETCH_ASSOC);   
                                                }
                                            }

                                            foreach ($assets as $asset):
                                        ?>
                                            <tr>
                                                <?php
                                                    foreach ($asset as $key => $value):
                                                    if($key=="check_out_date" || $key=="expected_check_in_date" ):
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
                                                                    echo htmlspecialchars($asset['current_holder']);
                                                                }
                                                                else{
                                                                    echo htmlspecialchars($value);
                                                                }
                                                            ?>
                                                        </td>
                                                <?php
                                                    elseif($key=="asset_status" || $key=="current_holder" || $key=="id"):
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