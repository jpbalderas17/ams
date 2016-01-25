<?php
    require_once 'support/config.php';
    if(!isLoggedIn()){
        toLogin();
        die();
    }
    makeHead("Consumable Reports");
?>
<div id='wrapper'>
<?php
     require_once 'template/navbar.php';
?>
</div>

<div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Consumables Report</h1>
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
                                
                        </div>
                    </div> 

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
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $assets=$con->myQuery("SELECT name,order_number,purchase_date,purchase_cost, quantity, id FROM consumables where is_deleted=0")->fetchAll(PDO::FETCH_ASSOC);
                                            
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
                "scrollX": true,
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend:"csv",
                        text:"<span class='fa fa-download'></span> Download CSV "
                    }
                    ]
        });
    });

    </script>
<?php
    makeFoot();
?>