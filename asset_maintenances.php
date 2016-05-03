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
<?php
	 require_once("template/header.php");
	require_once("template/sidebar.php");
?>
<div class='content-wrapper'>
    <div class='content-header'>
        <h1 class='page-header text-center text-green'>Asset Maintenance</h1>
    </div>
    <section class='content'>
                <div class="row">
                <div class='col-lg-12'>
                    <?php
                        Alert();
                    ?>
                    <div class='row'>
                        <div class='col-sm-12'>
                                <a href='frm_asset_maintenance.php' class='btn btn-flat btn-success pull-right'> <span class='fa fa-plus'></span> Create New</a>
                        </div>
                    </div>
                    <br/>    

                    <div class='panel panel-default'>
                        
                        <div class='panel-body ' >
                            
                                <table class='table table-bordered table-condensed table-hover ' id='dataTables'>
                                    <thead>
                                        <tr>
                                            <th class='text-center'>Asset Tag</th>
                                            <th class='text-center'>Asset Name</th>
                                            <th class='text-center'>Maintenance Type</th>
                                            <th class='text-center'>Title</th>
                                            <th class='text-center date-td'>Start Date</th>
                                            <th class='text-center'>Completion Date</th>
                                            <th class='text-center'>Cost</th>
                                            <th class='text-center' style='max-width: 60px;width:60px'>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $categories=$con->myQuery("SELECT asset_maintenances.id,assets.asset_tag,assets.asset_name,asset_maintenances.asset_id,asset_maintenance_types.name as maintenance_type,asset_maintenances.title,DATE_FORMAT(asset_maintenances.start_date,'%m/%d/%Y')as start_date,DATE_FORMAT(asset_maintenances.completion_date,'%m/%d/%Y')as completion_date,asset_maintenances.cost FROM `asset_maintenances` JOIN asset_maintenance_types ON asset_maintenances.asset_maintenance_type_id=asset_maintenance_types.id JOIN assets ON assets.id=asset_maintenances.asset_id WHERE asset_maintenances.is_deleted=0")->fetchAll(PDO::FETCH_ASSOC);
                                            foreach ($categories as $category):
                                        ?>
                                            <tr>
                                                <td><a href='view_asset.php?id=<?php echo $category['asset_id']; ?>'><?php echo htmlspecialchars($category['asset_tag'])?></a></td>
                                                <td><?php echo htmlspecialchars($category['asset_name'])?></td>
                                                <td><?php echo htmlspecialchars($category['maintenance_type'])?></td>
                                                <td><?php echo htmlspecialchars($category['title'])?></td>
                                                <td><?php echo htmlspecialchars($category['start_date'])?></td>
                                                <td><?php echo htmlspecialchars($category['completion_date']!="00/00/0000"?$category['completion_date']:'')?></td>
                                                <td><?php echo htmlspecialchars($category['cost'])?></td>
                                                <td>
                                                    <a class='btn btn-sm btn-flat btn-success' href='frm_asset_maintenance.php?id=<?php echo $category['id'];?>'><span class='fa fa-pencil'></span></a>
                                                    <a class='btn btn-sm btn-flat btn-danger' href='delete.php?id=<?php echo $category['id']?>&t=c' onclick='return confirm("Are you sure you want to delte this asset maintenance?")'><span class='fa fa-trash'></span></a>
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