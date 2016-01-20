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
                                            <th>Asset Tag</th>
                                            <th>Serial Number</th>
                                            <th>Asset Name</th>
                                            <th>Model</th>
                                            <th>Status</th>
                                            <th>Location</th>
                                            <th>Category</th>
                                            <th>EOL</th>
                                            <th>Notes</th>
                                            <th>Order Number</th>
                                            <th>Checkout Date</th>
                                            <th>Expected Checkin Date</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $assets=$con->myQuery("SELECT asset_tag,serial_number,asset_name,model,asset_status,location,category,eol,notes,order_number,check_out_date,expected_check_in_date,id FROM qry_assets WHERE is_deleted=0")->fetchAll(PDO::FETCH_ASSOC);

                                            foreach ($assets as $asset):
                                        ?>
                                            <tr>
                                                <?php
                                                    foreach ($asset as $key => $value):
                                                    if($key=='id'):
                                                ?>
                                                    <td>
                                                        <?php
                                                            if(empty($asset['check_out_date']) || $asset['check_out_date']=="0000-00-00"):
                                                        ?>
                                                            <a class='btn btn-sm btn-info' href='check_asset.php?id=<?php echo $value;?>&type=out'><span class='fa fa-arrow-right'></span> Check Out</a>
                                                        <?php
                                                            else:
                                                        ?>
                                                            <a class='btn btn-sm btn-info' href='check_asset.php?id=<?php echo $value;?>&type=in'><span class='fa fa-arrow-left'></span> Check In</a>
                                                        <?php
                                                            endif;
                                                        ?>
                                                        <a class='btn btn-sm btn-warning' href='frm_assets.php?id=<?php echo $value;?>'><span class='fa fa-pencil'></span></a>
                                                        <a class='btn btn-sm btn-danger' href='delete.php?id=<?php echo $value?>&t=a' onclick='return confirm("This asset will be deleted.")'><span class='fa fa-trash'></span></a>
                                                    </td>
                                                <?php
                                                    elseif($key=="check_out_date" || $key=="expected_check_in_date"):
                                                ?>
                                                    <td>
                                                        <?php
                                                            if($value!="0000-00-00"){
                                                                echo htmlspecialchars($value);                                                                
                                                            }
                                                        ?>
                                                    </td>
                                                <?php
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