<?php
	require_once 'support/config.php';
	if(!isLoggedIn()){
		toLogin();
		die();
	}
	if(!AllowUser(array(1))){
        redirect("index.php");
    }
    if(!empty($_GET['id'])){
        $department=$con->myQuery("SELECT id,asset_status_id,name FROM asset_status_labels WHERE id=?",array($_GET['id']))->fetch(PDO::FETCH_ASSOC);
        if(empty($department)){
            Modal("Invalid Status Label Selected.");
            redirect("asset_status_labels.php");
        }
    }
    $asset_statuses=$con->myQuery("SELECT id,name FROM asset_statuses")->fetchAll(PDO::FETCH_ASSOC);
	makeHead("Asset Status Labels");
?>
<?php
	 require_once("template/header.php");
	require_once("template/sidebar.php");
?>
<div class='content-wrapper'>
    <div class='content-header'>
        <h1 class='page-header text-center text-green'>
            Asset Status Labels
        </h1>
    </div>
    <section class='content'>
        <div class="row">
                <div class='col-lg-12'>
                    <?php
                        Alert();
                    ?>
                    <div class='row'>
                        <div class='col-sm-12'>
                            <div class='align-center'>
                            <form class='form-horizontal' method='POST' action='save_status_labels.php'>
                                <input type='hidden' name='id' value='<?php echo !empty($department)?$department['id']:""?>'>
                                
                                <div class='form-group'>
                                    <label class=' control-label col-md-2'> Status Label</label>
                                        <div class='col-md-3'>
                                            <input type='text' class='form-control ' name='name' placeholder='Enter Status Label' value='<?php echo !empty($department)?$department['name']:"" ?>' required>
                                        </div>
                                        <label class=' control-label col-md-2'> Status Type:</label>
                                        <div class='col-md-3'>
                                            <select class='form-control' name='asset_status_id'  style='width:100%' data-placeholder="Select a Status" <?php echo!(empty($department))?"data-selected='".$department['asset_status_id']."'":NULL ?> required>
                                            <?php
                                                echo makeOptions($asset_statuses);
                                            ?>
                                            </select>
                                        </div>
                                    
                                        <div class='col-md-2'>
                                        <a href='asset_status_labels.php' class='btn btn-flat btn-default' onclick="return confirm('<?php echo !empty($department)?'Are you sure you want to cancel the modification of the asset label?':'Are you sure you want to cancel the creation of the new asset label?';?>')">Cancel</a>
                                        <button type='submit' class='btn btn-flat btn-success'> <span class='fa fa-check'></span> Save</button>
                                        </div>
                                </div>
                                        

                            </form>
                            </div>
                        </div>
                    </div>
                    <br/>    

                    <div class='panel panel-default'>
                        
                        <div class='panel-body ' >
                            
                                <table class='table table-bordered table-condensed table-hover ' id='dataTables'>
                                    <thead>
                                        <tr>
                                            <th class='text-center'>Label Name</th>
                                            <th class='text-center'>Label Status</th>
                                            <th class='text-center' style='max-width: 60px;width:60px'>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $categories=$con->myQuery("SELECT asl.id,asl.name,ass.name as status FROM asset_status_labels asl JOIN asset_statuses ass ON asl.asset_status_id=ass.id WHERE asl.is_deleted=0")->fetchAll(PDO::FETCH_ASSOC);
                                            foreach ($categories as $category):
                                        ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($category['name'])?></td>
                                                <td><?php echo htmlspecialchars($category['status'])?></td>
                                                <td>
                                                    <a class='btn btn-flat btn-sm btn-success' href='asset_status_labels.php?id=<?php echo $category['id'];?>' ><span class='fa fa-pencil'></span></a>
                                                    <a class='btn btn-flat btn-sm btn-danger' href='delete.php?id=<?php echo $category['id']?>&t=asl' onclick='return confirm("Are you sure you want to delete this asset label?")'><span class='fa fa-trash'></span></a>
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
            "language": {
                    "zeroRecords": "Asset label not found"
                }
        });
    });
    </script>
<?php
    Modal();
	makeFoot();
?>