<?php
	require_once 'support/config.php';
	if(!isLoggedIn()){
		toLogin();
		die();
	}
    if(!AllowUser(array(1,2))){
        redirect("index.php");
    }
    if(!empty($_GET['id'])){
        $department=$con->myQuery("SELECT id,name,terms FROM depreciations WHERE id=?",array($_GET['id']))->fetch(PDO::FETCH_ASSOC);
        if(empty($department)){
            Modal("Invalid Depreciation Selected.");
            redirect("depreciations.php");
        }
    }
	makeHead("Depreciations");
?>
<div id='wrapper'>
<?php
	 require_once 'template/navbar.php';
?>
</div>

<div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header"> Depreciations</h1>
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
                            <form class='form-inline' method='POST' action='save_depreciations.php'>
                                <input type='hidden' name='id' value='<?php echo !empty($department)?$department['id']:""?>'>
                                
                                <div class='form-group'>
                                    <label class=' control-label'> Department Name</label>
                                    <input type='text' class='form-control' name='name' placeholder='Enter Department Name' value='<?php echo !empty($department)?$department['name']:"" ?>'>

                                    <label class=' control-label'> Terms</label>
                                    <input type='text' class='form-control' name='terms' placeholder='Enter Terms' value='<?php echo !empty($department)?$department['terms']:"" ?>'>

                                        <a href='depreciations.php' class='btn btn-default'>Cancel</a>
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
                                            <th>Depreciation Name</th>
                                            <th>Terms</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $categories=$con->myQuery("SELECT id,name,terms FROM depreciations WHERE is_deleted=0")->fetchAll(PDO::FETCH_ASSOC);
                                            foreach ($categories as $category):
                                        ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($category['name'])?></td>
                                                <td><?php echo htmlspecialchars($category['terms'])?></td>
                                                <td>
                                                    <a class='btn btn-sm btn-warning' href='depreciations.php?id=<?php echo $category['id'];?>'><span class='fa fa-pencil'></span></a>
                                                    <a class='btn btn-sm btn-danger' href='delete.php?id=<?php echo $category['id']?>&t=depr' onclick='return confirm("This depreciation will be deleted.")'><span class='fa fa-trash'></span></a>
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
        });
    });
    </script>
<?php
    Modal();
	makeFoot();
?>