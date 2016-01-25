<?php
	require_once 'support/config.php';
	if(!isLoggedIn()){
		toLogin();
		die();
	}
    if(!AllowUser(array(1,2))){
        redirect("index.php");
    }
	makeHead("Consumables");
?>
<div id='wrapper'>
<?php
	 require_once 'template/navbar.php';
?>
</div>

<div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Consumables</h1>
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
                                <a href='frm_consumables.php' class='btn btn-success pull-right'> <span class='fa fa-plus'></span> Create New</a>
                        </div>
                    </div>
                    <br/>    

                    <div class='panel panel-default'>
                        
                        <div class='panel-body ' >
                            <div class='dataTable_wrapper '>
                                <table class='table  table-bordered table-condensed table-hover ' id='dataTables' >
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Order Number</th>
                                            <th>Purchase Date</th>
                                            <th>Purchase Cost</th>
                                            <th>Quantity</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $assets=$con->myQuery("SELECT name,order_number,purchase_date,purchase_cost, quantity,id FROM consumables where is_deleted=0")->fetchAll(PDO::FETCH_ASSOC);

                                            foreach ($assets as $asset):
                                        ?>
                                            <tr>
                                                
                                                <?php
                                                    foreach ($asset as $key => $value):
                                                    if($key=='name'):
                                                ?>
                                                    <td>
                                                        <a href='view_consumables.php?id=<?= $asset['id']?>'><?php echo htmlspecialchars($value)?></a>
                                                    </td>
                                                
                                                <?php
                                                    elseif($key=='id'):
                                                ?>                                                                                                 
                                                    <td>
                                                        <a class='btn btn-sm btn-info' href='check_consumables.php?id=<?php echo $value;?>&type=out'><span class='fa fa-arrow-right'></span> Check Out</a>
                                                        <a class='btn btn-sm btn-warning' href='frm_consumables.php?id=<?php echo $value;?>'><span class='fa fa-pencil'></span></a>
                                                        <a class='btn btn-sm btn-danger' href='delete.php?id=<?php echo $value?>&t=c' onclick='return confirm("This consumable will be deleted.")'><span class='fa fa-trash'></span></a>
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
                "scrollX": true,
                "width":1000
        });
    });
    </script>
<?php
	makeFoot();
?>