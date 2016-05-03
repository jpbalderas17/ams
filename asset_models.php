<?php
	require_once 'support/config.php';
	if(!isLoggedIn()){
		toLogin();
		die();
	}
	makeHead("Asset Models");
?>
<?php
	 require_once("template/header.php");
	require_once("template/sidebar.php");
?>
<div class='content-wrapper'>
    <section class="content-header">
      <h1 class='page-header text-center text-green'>
        Asset Models
      </h1>
    </section>
    <section class='content'>
        <div class="row">
            <div class='col-lg-12'>
                <?php
                    Alert();
                ?>
                <div class='row'>
                    <div class='col-sm-12'>
                            <a href='frm_asset_models.php' class='btn btn-flat btn-success pull-right'> <span class='fa fa-plus'></span> Create New</a>
                    </div>
                </div>
                <br/>    

                <div class='panel panel-default'>
                    
                    <div class='panel-body ' >
                        
                            <table class='table table-bordered table-condensed table-hover ' id='dataTables'>
                                <thead>
                                    <tr>
                                        <th class='text-center'>Model Name</th>
                                        <th class='text-center'>Model No.</th>
                                        <th class='text-center'>Manufacturer</th>
                                        <th class='text-center'>Category</th>
                                        <th class='date-td'>EOL</th>
                                        <th class='text-center'>Depriciation / Terms</th>
                                        <th class='text-center'>Assets</th>

                                        <th class='text-center' style='max-width: 60px;width:60px'>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        $models=$con->myQuery("SELECT asset_models.id,asset_models.model_no,asset_models.name as model_name,manufacturers.name as manufacturer,categories.name as category,EOL,depreciations.name as depreciation_name,depreciations.terms as depreciation_terms FROM `asset_models` JOIN manufacturers ON asset_models.manufacturer_id=manufacturers.id JOIN categories ON asset_models.category_id=categories.id LEFT JOIN depreciations ON asset_models.depreciation_id=depreciations.id WHERE asset_models.is_deleted=0")->fetchAll(PDO::FETCH_ASSOC);
                                        foreach ($models as $model):
                                    ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($model['model_name'])?></td>
                                            <td><?php echo htmlspecialchars($model['model_no'])?></td>
                                            <td><?php echo htmlspecialchars($model['manufacturer'])?></td>
                                            <td><?php echo htmlspecialchars($model['category'])?></td>
                                            <td><?php echo htmlspecialchars(($model['EOL']=="0000-00-00")?"":$model['EOL'])?></td>
                                            <td><?php echo htmlspecialchars((empty($model['depreciation_name'])?"":$model['depreciation_name']."({$model['depreciation_terms']} Months)"))?></td>
                                            <td>
                                                <?php
                                                    $count=$con->myQuery("SELECT COUNT(id) FROM qry_assets WHERE model=?",array($model['model_name']))->fetchColumn();
                                                    echo $count;
                                                ?>
                                            </td>
                                            <td>
                                                <a class='btn btn-flat btn-sm btn-success' href='frm_asset_models.php?id=<?php echo $model['id'];?>'><span class='fa fa-pencil'></span></a>
                                                <a class='btn btn-flat btn-sm btn-danger' href='delete.php?id=<?php echo $model['id']?>&t=am' onclick='return confirm("Are you sure you want to delete this asset model?")'><span class='fa fa-trash'></span></a>
                                            </td>
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
    </section>
</div>


<script>
    $(document).ready(function() {
        $('#dataTables').DataTable({
                 "scrollY":"400px",
                 "scrollX": true,
        });
    });
    </script>
<?php
    Modal();
	makeFoot();
?>