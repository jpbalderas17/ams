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
<?php
	 require_once("template/header.php");
	require_once("template/sidebar.php");
?>
<div class='content-wrapper'>
    <div class='content-header'>
        <h1 class="page-header text-center text-green">Consumables</h1>
    </div>
    <section class='content'>
        <div class="row">
            <div class='col-lg-12'>
                <?php
                    Alert();
                ?>
                <div class='row'>
                    <div class='col-sm-12'>
                            <a href='frm_consumables.php' class='btn btn-flat btn-success pull-right'> <span class='fa fa-plus'></span> Create New</a>
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
                                        <th style='min-width: 190px;width: 190px'>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        $assets=$con->myQuery("SELECT name,order_number,DATE_FORMAT(purchase_date,'%m/%d/%Y')as purchase_date,purchase_cost, quantity,id FROM consumables where is_deleted=0")->fetchAll(PDO::FETCH_ASSOC);

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
                                                    <?php
                                                        if($asset['quantity']>0):
                                                    ?>
                                                    <a class='btn btn-flat btn-sm btn-success' href='check_consumables.php?id=<?php echo $value;?>&type=out'><span class='fa fa-arrow-right'></span> Check Out</a>
                                                    <?php
                                                        endif;
                                                    ?>
                                                    <a class='btn btn-flat btn-sm btn-success' href='frm_consumables.php?id=<?php echo $value;?>'><span class='fa fa-pencil'></span></a>
                                                    <a class='btn btn-flat btn-sm btn-danger' href='delete.php?id=<?php echo $value?>&t=c' onclick='return confirm("Are you sure you want to delete this consumable?")'><span class='fa fa-trash'></span></a>
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
    </section>
</div>

<script>
    $(document).ready(function() {
        $('#dataTables').DataTable({
                "scrollY":"400px",
                "scrollX": true,
                "width":1000
        });
    });
    </script>
<?php
	makeFoot();
?>