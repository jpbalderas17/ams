<?php
    require_once 'support/config.php';
    if(!isLoggedIn()){
        toLogin();
        die();
    }
    if(!AllowUser(array(1,2,3))){
        redirect("index.php");
    }

    if(!empty($_GET['date_start'])){
        $date_start=date_create($_GET['date_start']);
    }
    else{
        $date_start="";
    }
    if(!empty($_GET['date_end'])){
        $date_end=date_create($_GET['date_end']);
    }
    else{
        $date_end="";
    }


    makeHead("Consumable Reports");
?>

<?php
     require_once("template/header.php");
	require_once("template/sidebar.php");
?>
<div class='content-wrapper'>
    <div class='content-header'>
        <h1 class='text-center page-header text-green'>Consumables Report</h1>
    </div>
    <section class='content'>
        <div class="row">
                <div class='col-lg-12'>
                    <?php
                        Alert();
                    ?>
                    <div class='col-sm-12'>
                      <form method='get'>
                      <label class='col-md-2 text-right' >Start Date</label>
                      <div class='col-md-3'>
                        <input type='date' name='date_start' class='form-control' id='date_start' value='<?php echo !empty($_GET['date_start'])?htmlspecialchars($_GET['date_start']):''?>'>
                      </div>
                      <label class='col-md-2 text-right' >End Date</label>
                      <div class='col-md-3'>
                        <input type='date' name='date_end' class='form-control' id='date_end' value='<?php echo !empty($_GET['date_end'])?htmlspecialchars($_GET['date_end']):''?>'>
                      </div>
                      <div class='col-md-2'>
                        <button type='submit'  class='btn-flat btn btn-success' >Filter</button>
                      </div>
                      </form>
                    </div>
                    <br/>
                    <br/>

                    <div class='panel panel-default'>
                        
                        <div class='panel-body ' >
                            <div class='dataTable_wrapper '>
                                <table class='table  table-bordered table-condensed table-hover ' id='dataTables' >
                                    <thead>
                                        <tr>
                                            <th class='text-center'>Name</th>
                                            <th class='text-center'>Order Number</th>
                                            <th class='text-center'>Purchase Date</th>
                                            <th class='text-center'>Purchase Cost</th>
                                            <th class='text-center'>Quantity</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php

                                            $date_filter="";
                                            if(!empty($date_start)){
                                                $date_filter.=" AND purchase_date >= '".date_format($date_start,'Y-m-d')."'";
                                            }

                                            if(!empty($date_end)){
                                                $date_filter.=" AND purchase_date <= '".date_format($date_end,'Y-m-d')."'";
                                            }

                                            $assets=$con->myQuery("SELECT name,order_number,DATE_FORMAT(purchase_date,'%m/%d/%Y') as purchase_date,purchase_cost, quantity, id FROM consumables where is_deleted=0 {$date_filter}")->fetchAll(PDO::FETCH_ASSOC);
                                            
                                            foreach ($assets as $asset):
                                        ?>
                                            <tr>
                                                
                                                <?php
                                                    foreach ($asset as $key => $value):
                                                    if($key=='name'):
                                                ?>
                                                    <td>
                                                        <!-- <a href='view_consumables.php?id=<?= $asset['id']?>'><?php echo htmlspecialchars($value)?></a> -->
                                                        <?php echo htmlspecialchars($value)?>
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
    </section>
</div>
<script>
    $(document).ready(function() {
        $('#dataTables').DataTable({
                 "scrollY":"400px",
                "scrollX": true,
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend:"excel",
                        text:"<span style='color:#fff'><span class='fa fa-download'></span> Download Excel </span>",
                        className:"btn btn-success btn-flat",
                        "extension":".xls"
                    }
                    ]
        });
    });

    </script>
<?php
    makeFoot();
?>