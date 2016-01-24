<?php
	require_once 'support/config.php';
	if(!isLoggedIn()){
		toLogin();
		die();
	}
	makeHead("Asset Reports");
?>
<div id='wrapper'>
<?php
	 require_once 'template/navbar.php';
?>
</div>

<div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Asset Report</h1>
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
                            <form method='get' action='download_excel.php' onclick='download_report(); return false;' id='csv_form'>
                                <input type='hidden' name='report' value='report_asset'>
                                <input type='hidden' name='filter' value='' id='filter_text'>
                                <button type='submit' class='btn btn-info'><span class='fa fa-plus'></span> Download CSV</button>
                            </form>
                                
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
                                            <th>Manufacturer</th>
                                            <th>Model</th>
                                            <th>Status</th>
                                            <th>Location</th>
                                            <th>Category</th>
                                            <th>EOL</th>
                                            <th>Notes</th>
                                            <th>Order Number</th>
                                            <th>Checkout Date</th>
                                            <th>Expected Checkin Date</th>
                                            <th>Purchase Date</th>
                                            <th>Depreciation Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $asset_sql="SELECT asset_tag,serial_number,asset_name,manufacturer,model,asset_status,asset_status_label,location,category,eol,notes,order_number,check_out_date,expected_check_in_date,id,CONCAT(last_name,', ',first_name,' ',middle_name)as current_holder,purchase_date,depreciation_term FROM qry_assets WHERE is_deleted=0";
                                            if(empty($_GET['status']) || $_GET['status']=='All'){
                                                $assets=$con->myQuery($asset_sql)->fetchAll(PDO::FETCH_ASSOC);
                                            }
                                            else{
                                                if($_GET['status']!="Deployed"){
                                                    $assets=$con->myQuery($asset_sql." AND asset_status_label=?",array($_GET['status']))->fetchAll(PDO::FETCH_ASSOC);
                                                }
                                                else{
                                                 $assets=$con->myQuery($asset_sql." AND check_out_date<>'0000-00-00'")->fetchAll(PDO::FETCH_ASSOC);   
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
                                                                    echo "Deployed (".htmlspecialchars($asset['current_holder']).")";
                                                                }
                                                                else{
                                                                    echo htmlspecialchars($value);
                                                                }
                                                            ?>
                                                        </td>
                                                <?php
                                                    elseif($key=="depreciation_term"):
                                                ?>
                                                        <td>
                                                            <?php
                                                                echo date_format( getDepriciationDate($asset['purchase_date'],$value),"Y-m-d");
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
            <div id='123'></div>
</div>
<script>
    $(document).ready(function() {
        $('#dataTables').DataTable({
                 "scrollY": true,
                "scrollX": true,
                "bSort" : false,
                dom: 'Bfrtip',
                buttons: [
                'csv'
                    ]
        });
    });
    function download_report(){
        $("#filter_text").val($(".dataTables_filter input").val());
        $("#csv_form").submit();
    }
    </script>
<?php
    Modal();
	makeFoot();
?>