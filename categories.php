<?php
	require_once 'support/config.php';
	if(!isLoggedIn()){
		toLogin();
		die();
	}
    if(!AllowUser(array(1,2))){
        redirect("index.php");
    }
	makeHead("Categories");
?>
<div id='wrapper'>
<?php
	 require_once 'template/navbar.php';
?>
</div>

<div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header"> Categories</h1>
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
                                <a href='frm_categories.php' class='btn btn-success pull-right'> <span class='fa fa-plus'></span> Create New</a>
                        </div>
                    </div>
                    <br/>    

                    <div class='panel panel-default'>
                        
                        <div class='panel-body ' >
                            
                                <table class='table table-bordered table-condensed table-hover ' id='dataTables'>
                                    <thead>
                                        <tr>
                                            <th>Category Name</th>
                                            <th>Type</th>
                                            <th>Assets</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $categories=$con->myQuery("SELECT categories.id,categories.name,category_types.name as asset_type,category_type_id FROM `categories` JOIN category_types ON categories.category_type_id=category_types.id WHERE is_deleted=0")->fetchAll(PDO::FETCH_ASSOC);
                                            foreach ($categories as $category):
                                        ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($category['name'])?></td>
                                                <td><?php echo htmlspecialchars($category['asset_type'])?></td>
                                                <td>
                                                    <?php
                                                        if($category['category_type_id']==1){
                                                            #ASSET
                                                            $count=$con->myQuery("SELECT COUNT(id) FROM qry_assets WHERE category=?",array($category['name']))->fetchColumn();
                                                        }
                                                        else{
                                                            #CONSUMABLES
                                                            $count=$con->myQuery("SELECT COUNT(id) FROM consumables WHERE category_id=?",array($category['id']))->fetchColumn();
                                                        }
                                                        echo $count;
                                                    ?>
                                                </td>
                                                <td>
                                                    <a class='btn btn-sm btn-warning' href='frm_categories.php?id=<?php echo $category['id'];?>'><span class='fa fa-pencil'></span></a>
                                                    <a class='btn btn-sm btn-danger' href='delete.php?id=<?php echo $category['id']?>&t=c' onclick='return confirm("This asset will be deleted.")'><span class='fa fa-trash'></span></a>
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
            <!-- /.row -->
</div>
<script>
    $(document).ready(function() {
        $('#dataTables').DataTable({
                 "scrollY": true,
                 "scrollX": true,
        });
    });
    </script>
<?php
    Modal();
	makeFoot();
?>