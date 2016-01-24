<?php
	require_once 'support/config.php';
	if(!isLoggedIn()){
		toLogin();
		die();
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
<div id='wrapper'>
<?php
	 require_once 'template/navbar.php';
?>
</div>

<div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header"> Asset Status Labels</h1>
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
                            <div class='align-center'>
                            <form class='form-inline' method='POST' action='save_status_labels.php'>
                                <input type='hidden' name='id' value='<?php echo !empty($department)?$department['id']:""?>'>
                                
                                <div class='form-group'>
                                    <label class=' control-label'> Status Label</label>
                                        <input type='text' class='form-control' name='name' placeholder='Enter Status Label' value='<?php echo !empty($department)?$department['name']:"" ?>' required>
                                    	<label class=' control-label'> Status Type:</label>
                                        
                                        <div class='form-group'>
                                    	<select class='form-control' name='asset_status_id'  data-placeholder="Select a Status" <?php echo!(empty($department))?"data-selected='".$department['asset_status_id']."'":NULL ?> required>
                                        <?php
                                        	echo makeOptions($asset_statuses);
                                        ?>
                                        </select>
                                        </div>
                                        <a href='asset_status_labels.php' class='btn btn-default'>Cancel</a>
                                        <button type='submit' class='btn btn-success'> <span class='fa fa-check'></span> Save</button>
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
                                            <th>Label Name</th>
                                            <th>Label Status</th>
                                            <th>Actions</th>
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
                                                    <a class='btn btn-sm btn-warning' href='asset_status_labels.php?id=<?php echo $category['id'];?>'><span class='fa fa-pencil'></span></a>
                                                    <a class='btn btn-sm btn-danger' href='delete.php?id=<?php echo $category['id']?>&t=asl' onclick='return confirm("This label will be deleted.")'><span class='fa fa-trash'></span></a>
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