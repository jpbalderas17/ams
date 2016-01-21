<?php
	require_once 'support/config.php';
	if(!isLoggedIn()){
		toLogin();
		die();
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
                                            $assets=$con->myQuery("SELECT name,order_number,purchase_date,purchase_cost, quantity,id FROM consumables")->fetchAll(PDO::FETCH_ASSOC);

                                            foreach ($assets as $asset):
                                        ?>
                                            <tr>
                                                <?php
                                                    foreach ($asset as $key => $value):
                                                    if($key=='id'):
                                                ?>
                                                    <td>
                                                        <button class='btn btn-sm btn-warning'><a href='items.php?id=<?= $item['ID']?>'><span class='fa fa-pencil'></span></a></button>
                                                        <button  class='btn btn-sm btn-danger'> <a href='delete_consumables.php?id=<?= $asset['ID']?>' onclick="return confirm('Are you sure you want to delete this item?') class='btn btn-xs btn-danger'"><span  class='fa fa-trash'></span></a></button>&nbsp;
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